<?php
/**
 * Login Process with Google reCAPTCHA v2 Validation
 * Athletiqo Sports E-commerce Platform
 */

// Start session
session_start();

// Include configuration
require_once 'recaptcha-config.php';

// Set headers for JSON response
header('Content-Type: application/json');
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header('X-XSS-Protection: 1; mode=block');

// Initialize response
$response = [
    'success' => false,
    'error' => '',
    'data' => null
];

// Check if request is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $response['error'] = 'Invalid request method';
    echo json_encode($response);
    exit;
}

// Get JSON input
$jsonInput = file_get_contents('php://input');
$data = json_decode($jsonInput, true);

if (!$data) {
    $response['error'] = 'Invalid JSON data';
    echo json_encode($response);
    exit;
}

// Extract and sanitize inputs
$email = sanitizeInput($data['email'] ?? '');
$password = $data['password'] ?? '';
$recaptchaResponse = $data['g-recaptcha-response'] ?? '';
$remoteIp = $_SERVER['REMOTE_ADDR'] ?? '';

// Validate required fields
if (empty($email) || empty($password)) {
    $response['error'] = 'Email and password are required';
    echo json_encode($response);
    exit;
}

// Validate email format
if (!validateEmail($email)) {
    $response['error'] = 'Please enter a valid email address';
    echo json_encode($response);
    exit;
}

// Check for brute force attempts
if (!checkBruteForce($email)) {
    $response['error'] = 'Too many failed login attempts. Please try again later.';
    echo json_encode($response);
    exit;
}

// Verify reCAPTCHA
$recaptchaResult = verifyRecaptcha($recaptchaResponse, $remoteIp);

if (!$recaptchaResult['success']) {
    logSecurityEvent('recaptcha_failed', [
        'email' => $email,
        'ip' => $remoteIp,
        'error' => $recaptchaResult['error'] ?? 'Unknown reCAPTCHA error'
    ]);
    
    $response['error'] = 'Security verification failed. Please try again.';
    echo json_encode($response);
    exit;
}

// reCAPTCHA verification successful, proceed with login
logSecurityEvent('recaptcha_passed', [
    'email' => $email,
    'ip' => $remoteIp
]);

// Database connection (for demo, using simulated data)
// In production, you would connect to your actual database
$users = getUsersFromDatabase(); // This would be a database query

// Find user by email
$user = null;
foreach ($users as $existingUser) {
    if ($existingUser['email'] === $email) {
        $user = $existingUser;
        break;
    }
}

if (!$user) {
    recordFailedLogin($email);
    $response['error'] = 'Invalid email or password';
    echo json_encode($response);
    exit;
}

// Verify password
if (!verifyPassword($password, $user['password'])) {
    recordFailedLogin($email);
    logSecurityEvent('login_failed_password', [
        'email' => $email,
        'ip' => $remoteIp
    ]);
    
    $response['error'] = 'Invalid email or password';
    echo json_encode($response);
    exit;
}

// Check if account is active
if (isset($user['status']) && $user['status'] !== 'active') {
    $response['error'] = 'Account is not active. Please contact support.';
    echo json_encode($response);
    exit;
}

// Login successful
clearFailedLogin($email);

// Set session variables
$_SESSION['user_id'] = $user['id'];
$_SESSION['user_name'] = $user['name'];
$_SESSION['user_email'] = $user['email'];
$_SESSION['login_time'] = time();
$_SESSION['ip_address'] = $remoteIp;

// Log successful login
logSecurityEvent('login_successful', [
    'user_id' => $user['id'],
    'email' => $email,
    'ip' => $remoteIp
]);

// Update last login time in database (in production)
// updateLastLogin($user['id']);

// Prepare success response
$response['success'] = true;
$response['data'] = [
    'userId' => $user['id'],
    'userName' => $user['name'],
    'userEmail' => $user['email'],
    'loginTime' => date('Y-m-d H:i:s')
];

echo json_encode($response);

/**
 * Get users from database (simulated for demo)
 * In production, this would query your actual database
 */
function getUsersFromDatabase() {
    // Simulated user data for demo
    // In production, you would have a proper database connection
    return [
        [
            'id' => 1,
            'name' => 'John Doe',
            'email' => 'john@athletiqo.com',
            'password' => password_hash('Password123', PASSWORD_BCRYPT), // Hashed password
            'status' => 'active',
            'created_at' => '2024-01-01 00:00:00'
        ],
        [
            'id' => 2,
            'name' => 'Jane Smith',
            'email' => 'jane@athletiqo.com',
            'password' => password_hash('Password123', PASSWORD_BCRYPT), // Hashed password
            'status' => 'active',
            'created_at' => '2024-01-02 00:00:00'
        ]
    ];
}

/**
 * Update last login time (for production database)
 */
function updateLastLogin($userId) {
    // In production, you would update the user's last_login timestamp
    // Example: UPDATE users SET last_login = NOW() WHERE id = ?
    // This is a placeholder for the actual database operation
}

/**
 * Rate limiting for login attempts
 */
function checkRateLimit($email) {
    $key = 'login_attempt_' . md5($email . $_SERVER['REMOTE_ADDR']);
    $attempts = apcu_fetch($key) ?: 0;
    
    if ($attempts >= 10) { // 10 attempts per hour
        return false;
    }
    
    apcu_store($key, $attempts + 1, 3600); // 1 hour expiry
    return true;
}
?>
