<?php
session_start();
require_once 'config.php';

// Check if user is already logged in
if (isset($_SESSION['user_id'])) {
    header('Location: index.html');
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = sanitize_input($_POST['email']);
    $password = $_POST['password'];
    $captcha = sanitize_input($_POST['captcha']);
    
    // Validate CAPTCHA
    if (!isset($_SESSION['captcha_code']) || !isset($_SESSION['captcha_time'])) {
        $error = 'CAPTCHA session expired. Please try again.';
    } elseif (time() - $_SESSION['captcha_time'] > 300) { // 5 minutes expiry
        $error = 'CAPTCHA expired. Please try again.';
        unset($_SESSION['captcha_code'], $_SESSION['captcha_time']);
    } elseif (strtoupper($captcha) !== strtoupper($_SESSION['captcha_code'])) {
        $error = 'Incorrect CAPTCHA code. Please try again.';
        unset($_SESSION['captcha_code'], $_SESSION['captcha_time']);
    } else {
        // CAPTCHA is valid, proceed with login
        try {
            $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_email'] = $user['email'];
                
                // Clear CAPTCHA after successful login
                unset($_SESSION['captcha_code'], $_SESSION['captcha_time']);
                
                header('Location: index.html');
                exit();
            } else {
                $error = 'Invalid email or password';
            }
        } catch (PDOException $e) {
            $error = 'Database error. Please try again.';
        }
    }
}

function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Athletiqo Co.</title>
    <link rel="stylesheet" href="style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        .auth-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, var(--bg-primary), var(--bg-secondary));
            padding: 2rem;
        }
        
        .auth-card {
            background: var(--bg-card);
            border-radius: 20px;
            box-shadow: 0 20px 60px var(--shadow-medium);
            padding: 3rem;
            width: 100%;
            max-width: 450px;
            border: 1px solid var(--border-color);
        }
        
        .auth-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .auth-header h1 {
            font-size: 2rem;
            margin-bottom: 0.5rem;
            background: var(--accent-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: var(--text-primary);
        }
        
        .form-group input {
            width: 100%;
            padding: 1rem;
            border: 2px solid var(--border-color);
            border-radius: 12px;
            font-size: 1rem;
            background: var(--bg-primary);
            color: var(--text-primary);
            transition: all 0.3s ease;
        }
        
        .form-group input:focus {
            outline: none;
            border-color: var(--accent-primary);
            box-shadow: 0 0 0 3px rgba(255, 87, 34, 0.1);
        }
        
        .password-toggle {
            position: relative;
        }
        
        .password-toggle button {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--text-secondary);
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 6px;
            transition: all 0.3s ease;
        }
        
        .password-toggle button:hover {
            background: var(--bg-secondary);
            color: var(--text-primary);
        }
        
        .error-message {
            background: rgba(220, 53, 69, 0.1);
            color: var(--danger);
            padding: 1rem;
            border-radius: 12px;
            margin-bottom: 1.5rem;
            border-left: 4px solid var(--danger);
            font-size: 0.9rem;
        }
        
        .auth-links {
            text-align: center;
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid var(--border-color);
        }
        
        .auth-links a {
            color: var(--accent-primary);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }
        
        .auth-links a:hover {
            color: var(--accent-secondary);
        }
        
        .theme-toggle {
            position: absolute;
            top: 2rem;
            right: 2rem;
        }
    </style>
</head>
<body>
    <button class="theme-toggle" onclick="toggleTheme()" aria-label="Toggle theme">
        <svg viewBox="0 0 24 24" fill="currentColor">
            <circle cx="12" cy="12" r="5"></circle>
            <line x1="12" y1="1" x2="12" y2="3"></line>
            <line x1="12" y1="21" x2="12" y2="23"></line>
            <line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line>
            <line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line>
            <line x1="1" y1="12" x2="3" y2="12"></line>
            <line x1="21" y1="12" x2="23" y2="12"></line>
            <line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line>
            <line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line>
        </svg>
    </button>

    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <h1>Welcome Back</h1>
                <p style="color: var(--text-secondary);">Login to your Athletiqo account</p>
            </div>
            
            <?php if ($error): ?>
                <div class="error-message">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" required 
                           value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>"
                           placeholder="Enter your email">
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="password-toggle">
                        <input type="password" id="password" name="password" required 
                               placeholder="Enter your password">
                        <button type="button" onclick="togglePassword('password')">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                <circle cx="12" cy="12" r="3"/>
                            </svg>
                        </button>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="captcha">Security Verification</label>
                    <div style="display: flex; gap: 1rem; align-items: center;">
                        <input type="text" id="captcha" name="captcha" required 
                               placeholder="Enter code" style="flex: 1;"
                               maxlength="6">
                        <img src="captcha.php" alt="CAPTCHA" 
                             style="height: 50px; border: 2px solid var(--border-color); border-radius: 8px; cursor: pointer;"
                             onclick="this.src='captcha.php?' + new Date().getTime()"
                             title="Click to refresh">
                    </div>
                    <div style="font-size: 0.85rem; color: var(--text-secondary); margin-top: 0.5rem;">
                        Click on the image to refresh the code
                    </div>
                </div>
                
                <button type="submit" class="btn btn-primary" style="width: 100%; padding: 1rem; font-size: 1.1rem;">
                    Login to Account
                </button>
            </form>
            
            <div class="auth-links">
                <p>Don't have an account? <a href="signup.php">Sign Up</a></p>
                <p style="margin-top: 1rem;"><a href="forgot-password.php">Forgot Password?</a></p>
            </div>
        </div>
    </div>

    <script src="script.js"></script>
    <script>
        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            const button = input.nextElementSibling;
            
            if (input.type === 'password') {
                input.type = 'text';
                button.innerHTML = `
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/>
                        <line x1="1" y1="1" x2="23" y2="23"/>
                    </svg>
                `;
            } else {
                input.type = 'password';
                button.innerHTML = `
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                        <circle cx="12" cy="12" r="3"/>
                    </svg>
                `;
            }
        }
    </script>
</body>
</html>
