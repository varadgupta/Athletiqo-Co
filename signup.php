<?php
session_start();
require_once 'config.php';

// Check if user is already logged in
if (isset($_SESSION['user_id'])) {
    header('Location: index.html');
    exit();
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitize_input($_POST['name']);
    $email = sanitize_input($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $captcha = sanitize_input($_POST['captcha']);
    
    // Validation
    if (empty($name) || empty($email) || empty($password)) {
        $error = 'All fields are required';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address';
    } elseif (strlen($password) < 8) {
        $error = 'Password must be at least 8 characters long';
    } elseif ($password !== $confirm_password) {
        $error = 'Passwords do not match';
    } elseif (!isset($_SESSION['captcha_code']) || !isset($_SESSION['captcha_time'])) {
        $error = 'CAPTCHA session expired. Please try again.';
    } elseif (time() - $_SESSION['captcha_time'] > 300) { // 5 minutes expiry
        $error = 'CAPTCHA expired. Please try again.';
        unset($_SESSION['captcha_code'], $_SESSION['captcha_time']);
    } elseif (strtoupper($captcha) !== strtoupper($_SESSION['captcha_code'])) {
        $error = 'Incorrect CAPTCHA code. Please try again.';
        unset($_SESSION['captcha_code'], $_SESSION['captcha_time']);
    } else {
        try {
            // Check if email already exists
            $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->execute([$email]);
            
            if ($stmt->fetch()) {
                $error = 'Email address already exists';
            } else {
                // Create new user
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("INSERT INTO users (name, email, password, created_at) VALUES (?, ?, ?, NOW())");
                $stmt->execute([$name, $email, $hashed_password]);
                
                // Clear CAPTCHA after successful registration
                unset($_SESSION['captcha_code'], $_SESSION['captcha_time']);
                
                $success = 'Account created successfully! You can now login.';
                
                // Redirect to login after 2 seconds
                header('refresh:2;url=login.php');
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
    <title>Sign Up - Athletiqo Co.</title>
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
        
        .success-message {
            background: rgba(40, 167, 69, 0.1);
            color: var(--success);
            padding: 1rem;
            border-radius: 12px;
            margin-bottom: 1.5rem;
            border-left: 4px solid var(--success);
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
        
        .password-requirements {
            font-size: 0.85rem;
            color: var(--text-secondary);
            margin-top: 0.5rem;
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
                <h1>Create Account</h1>
                <p style="color: var(--text-secondary);">Join the Athletiqo community</p>
            </div>
            
            <?php if ($error): ?>
                <div class="error-message">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="success-message">
                    <?php echo htmlspecialchars($success); ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <div class="form-group">
                    <label for="name">Full Name</label>
                    <input type="text" id="name" name="name" required 
                           value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>"
                           placeholder="Enter your full name">
                </div>
                
                <div class="form-group">
                    <label for="profile_photo">Profile Photo (Optional)</label>
                    <div style="display: flex; gap: 1rem; align-items: center;">
                        <div style="position: relative;">
                            <img id="photo_preview" src="" alt="Profile Preview" 
                                 style="width: 100px; height: 100px; border-radius: 50%; object-fit: cover; border: 3px solid var(--border-color); display: none;">
                            <div id="photo_placeholder" 
                                 style="width: 100px; height: 100px; border-radius: 50%; background: var(--bg-secondary); border: 3px solid var(--border-color); display: flex; align-items: center; justify-content: center; font-size: 2rem; color: var(--text-secondary);">
                                👤
                            </div>
                            <input type="file" id="profile_photo" name="profile_photo" accept="image/*" 
                                   style="position: absolute; opacity: 0; width: 100%; height: 100%; cursor: pointer;"
                                   onchange="previewPhoto(event)">
                        </div>
                        <div>
                            <div style="font-size: 0.9rem; color: var(--text-secondary); margin-bottom: 0.5rem;">
                                Click to upload profile photo
                            </div>
                            <div style="font-size: 0.8rem; color: var(--text-muted);">
                                Max size: 2MB, JPG/PNG only
                            </div>
                        </div>
                    </div>
                </div>
                
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
                               placeholder="Create a password" minlength="8">
                        <button type="button" onclick="togglePassword('password')">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                <circle cx="12" cy="12" r="3"/>
                            </svg>
                        </button>
                    </div>
                    <div class="password-requirements">
                        Minimum 8 characters
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="confirm_password">Confirm Password</label>
                    <div class="password-toggle">
                        <input type="password" id="confirm_password" name="confirm_password" required 
                               placeholder="Confirm your password" minlength="8">
                        <button type="button" onclick="togglePassword('confirm_password')">
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
                    Create Account
                </button>
            </form>
            
            <div class="auth-links">
                <p>Already have an account? <a href="login.php">Login</a></p>
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

        function previewPhoto(event) {
            const file = event.target.files[0];
            const preview = document.getElementById('photo_preview');
            const placeholder = document.getElementById('photo_placeholder');
            
            if (file) {
                // Check file size (2MB limit)
                if (file.size > 2 * 1024 * 1024) {
                    alert('File size must be less than 2MB');
                    event.target.value = '';
                    return;
                }
                
                // Check file type
                if (!file.type.match('image.*')) {
                    alert('Please select an image file (JPG, PNG, etc.)');
                    event.target.value = '';
                    return;
                }
                
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                    placeholder.style.display = 'none';
                };
                reader.readAsDataURL(file);
            } else {
                preview.style.display = 'none';
                placeholder.style.display = 'flex';
            }
        }
    </script>
</body>
</html>
