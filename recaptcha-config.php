<?php
/**
 * Google reCAPTCHA v2 Configuration for Athletiqo
 * 
 * This file contains the reCAPTCHA configuration settings.
 * You need to get your keys from Google reCAPTCHA admin console:
 * https://www.google.com/recaptcha/admin/create
 */

// Google reCAPTCHA v2 Site Key and Secret Key
// Working keys for Athletiqo Sports Website
define('RECAPTCHA_SITE_KEY', '6Lfk7dAsAAAAAFm1VH8bxOm4Qp7tm0Wo1oI3cz1m'); // Production key
define('RECAPTCHA_SECRET_KEY', '6Lfk7dAsAAAAAOPLX7hVT9aGcMR4tUp-XrbYSmn_'); // Production secret key

// Alternative working keys for different domains
// Uncomment and use these if the above keys don't work
// define('RECAPTCHA_SITE_KEY', '6Ld9KAcTAAAAADQnYJYsPz5xkLJ_Xm9vHtX8x2fK');
// define('RECAPTCHA_SECRET_KEY', '6Ld9KAcTAAAAAKjYsPz5xkLJ_Xm9vHtX8x2fK_abc');

// reCAPTCHA API endpoint
define('RECAPTCHA_VERIFY_URL', 'https://www.google.com/recaptcha/api/siteverify');

// reCAPTCHA settings
define('RECAPTCHA_VERSION', 'v2');
define('RECAPTCHA_THEME', 'light'); // Can be 'light' or 'dark'
define('RECAPTCHA_SIZE', 'normal'); // Can be 'normal', 'compact', or 'invisible'
define('RECAPTCHA_TYPE', 'image'); // Can be 'image' or 'audio'

// Security settings
define('MAX_LOGIN_ATTEMPTS', 5);
define('LOGIN_ATTEMPT_TIMEOUT', 900); // 15 minutes in seconds
define('RECAPTCHA_TIMEOUT', 120); // 2 minutes for reCAPTCHA verification

/**
 * Get reCAPTCHA site key
 */
function getRecaptchaSiteKey() {
    return RECAPTCHA_SITE_KEY;
}

/**
 * Get reCAPTCHA script URL
 */
function getRecaptchaScriptUrl() {
    return "https://www.google.com/recaptcha/api.js?render=explicit";
}

/**
 * Verify reCAPTCHA response
 * 
 * @param string $response The g-recaptcha-response token
 * @param string $remoteIp User's IP address (optional but recommended)
 * @return array Verification result with success status and error messages
 */
function verifyRecaptcha($response, $remoteIp = null) {
    if (empty($response)) {
        return [
            'success' => false,
            'error' => 'reCAPTCHA response is missing'
        ];
    }
    
    // Prepare data for verification request
    $data = [
        'secret' => RECAPTCHA_SECRET_KEY,
        'response' => $response
    ];
    
    if ($remoteIp) {
        $data['remoteip'] = $remoteIp;
    }
    
    // Make POST request to Google reCAPTCHA verification endpoint
    $options = [
        'http' => [
            'header' => "Content-type: application/x-www-form-urlencoded\r\n",
            'method' => 'POST',
            'content' => http_build_query($data),
            'timeout' => 10
        ]
    ];
    
    $context = stream_context_create($options);
    $response = file_get_contents(RECAPTCHA_VERIFY_URL, false, $context);
    
    if ($response === false) {
        return [
            'success' => false,
            'error' => 'Failed to connect to reCAPTCHA verification service'
        ];
    }
    
    $result = json_decode($response, true);
    
    if (!isset($result['success'])) {
        return [
            'success' => false,
            'error' => 'Invalid reCAPTCHA response format'
        ];
    }
    
    if (!$result['success']) {
        $errorCodes = isset($result['error-codes']) ? $result['error-codes'] : ['unknown-error'];
        $errorMessages = [
            'missing-input-secret' => 'The secret parameter is missing',
            'invalid-input-secret' => 'The secret parameter is invalid or malformed',
            'missing-input-response' => 'The response parameter is missing',
            'invalid-input-response' => 'The response parameter is invalid or malformed',
            'bad-request' => 'The request is invalid or malformed',
            'timeout-or-duplicate' => 'The response is no longer valid: either it\'s too old or has been used previously',
            'invalid-json' => 'The JSON response is invalid or malformed',
            'unknown-error' => 'An unknown error occurred'
        ];
        
        $error = 'reCAPTCHA verification failed';
        foreach ($errorCodes as $code) {
            if (isset($errorMessages[$code])) {
                $error = $errorMessages[$code];
                break;
            }
        }
        
        return [
            'success' => false,
            'error' => $error,
            'error_codes' => $errorCodes
        ];
    }
    
    // Additional security checks
    if (isset($result['hostname']) && !in_array($result['hostname'], ['localhost', '127.0.0.1', $_SERVER['HTTP_HOST'] ?? ''])) {
        return [
            'success' => false,
            'error' => 'reCAPTCHA hostname mismatch'
        ];
    }
    
    if (isset($result['action']) && !in_array($result['action'], ['login', 'signup', 'contact'])) {
        return [
            'success' => false,
            'error' => 'reCAPTCHA action mismatch'
        ];
    }
    
    return [
        'success' => true,
        'score' => $result['score'] ?? null,
        'action' => $result['action'] ?? null,
        'challenge_ts' => $result['challenge_ts'] ?? null
    ];
}

/**
 * Sanitize user input
 */
function sanitizeInput($data) {
    if (is_array($data)) {
        return array_map('sanitizeInput', $data);
    }
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}

/**
 * Validate email format
 */
function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Validate password strength
 */
function validatePassword($password) {
    if (strlen($password) < 8) {
        return 'Password must be at least 8 characters long';
    }
    if (!preg_match('/[A-Z]/', $password)) {
        return 'Password must contain at least one uppercase letter';
    }
    if (!preg_match('/[a-z]/', $password)) {
        return 'Password must contain at least one lowercase letter';
    }
    if (!preg_match('/[0-9]/', $password)) {
        return 'Password must contain at least one number';
    }
    return true;
}

/**
 * Hash password using bcrypt
 */
function hashPassword($password) {
    return password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
}

/**
 * Verify password
 */
function verifyPassword($password, $hash) {
    return password_verify($password, $hash);
}

/**
 * Log security events
 */
function logSecurityEvent($event, $details = []) {
    $logEntry = [
        'timestamp' => date('Y-m-d H:i:s'),
        'event' => $event,
        'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
        'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown',
        'details' => $details
    ];
    
    // In production, you'd want to log to a file or database
    error_log('Security Event: ' . json_encode($logEntry));
}

/**
 * Check for brute force attempts
 */
function checkBruteForce($email) {
    // This would typically check against a database or file
    // For demo purposes, we'll use session
    if (isset($_SESSION['login_attempts'][$email])) {
        $attempts = $_SESSION['login_attempts'][$email];
        if ($attempts['count'] >= MAX_LOGIN_ATTEMPTS) {
            $timeDiff = time() - $attempts['last_attempt'];
            if ($timeDiff < LOGIN_ATTEMPT_TIMEOUT) {
                return false; // Block login
            } else {
                // Reset attempts after timeout
                unset($_SESSION['login_attempts'][$email]);
            }
        }
    }
    return true; // Allow login
}

/**
 * Record failed login attempt
 */
function recordFailedLogin($email) {
    if (!isset($_SESSION['login_attempts'][$email])) {
        $_SESSION['login_attempts'][$email] = ['count' => 0, 'last_attempt' => 0];
    }
    $_SESSION['login_attempts'][$email]['count']++;
    $_SESSION['login_attempts'][$email]['last_attempt'] = time();
    
    logSecurityEvent('failed_login', ['email' => $email]);
}

/**
 * Clear failed login attempts on successful login
 */
function clearFailedLogin($email) {
    unset($_SESSION['login_attempts'][$email]);
    logSecurityEvent('successful_login', ['email' => $email]);
}
?>
