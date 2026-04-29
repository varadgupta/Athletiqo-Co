<?php
/**
 * Signup Process with Google reCAPTCHA v2 Validation
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
$name = sanitizeInput($data['name'] ?? '');
$email = sanitizeInput($data['email'] ?? '');
$password = $data['password'] ?? '';
$profilePhoto = $data['profilePhoto'] ?? null;
$recaptchaResponse = $data['g-recaptcha-response'] ?? '';
$remoteIp = $_SERVER['REMOTE_ADDR'] ?? '';

// Validate required fields
if (empty($name) || empty($email) || empty($password)) {
    $response['error'] = 'All required fields must be filled';
    echo json_encode($response);
    exit;
}

// Validate name
if (strlen($name) < 2) {
    $response['error'] = 'Name must be at least 2 characters long';
    echo json_encode($response);
    exit;
}

if (strlen($name) > 100) {
    $response['error'] = 'Name must be less than 100 characters';
    echo json_encode($response);
    exit;
}

// Validate email format
if (!validateEmail($email)) {
    $response['error'] = 'Please enter a valid email address';
    echo json_encode($response);
    exit;
}

// Validate password strength
$passwordValidation = validatePassword($password);
if ($passwordValidation !== true) {
    $response['error'] = $passwordValidation;
    echo json_encode($response);
    exit;
}

// Check rate limiting for signup attempts
$signupKey = 'signup_attempt_' . md5($email . $remoteIp);
$signupAttempts = apcu_fetch($signupKey) ?: 0;

if ($signupAttempts >= 5) { // 5 signup attempts per hour
    $response['error'] = 'Too many signup attempts. Please try again later.';
    echo json_encode($response);
    exit;
}

// Verify reCAPTCHA (only if token is provided)
if (!empty($recaptchaResponse)) {
    $recaptchaResult = verifyRecaptcha($recaptchaResponse, $remoteIp);

    if (!$recaptchaResult['success']) {
        logSecurityEvent('recaptcha_failed', [
            'email' => $email,
            'ip' => $remoteIp,
            'action' => 'signup',
            'error' => $recaptchaResult['error'] ?? 'Unknown reCAPTCHA error'
        ]);
        
        $response['error'] = 'Security verification failed. Please try again.';
        echo json_encode($response);
        exit;
    }
    
    // reCAPTCHA verification successful, proceed with signup
    logSecurityEvent('recaptcha_passed', [
        'email' => $email,
        'ip' => $remoteIp,
        'action' => 'signup'
    ]);
} else {
    // No reCAPTCHA token provided - using fallback canvas security
    logSecurityEvent('fallback_security_used', [
        'email' => $email,
        'ip' => $remoteIp,
        'action' => 'signup'
    ]);
}

// Database connection (for demo, using simulated data)
// In production, you would connect to your actual database
$users = getUsersFromDatabase(); // This would be a database query

// Check if email already exists
foreach ($users as $existingUser) {
    if ($existingUser['email'] === $email) {
        logSecurityEvent('signup_email_exists', [
            'email' => $email,
            'ip' => $remoteIp
        ]);
        
        $response['error'] = 'An account with this email already exists';
        echo json_encode($response);
        exit;
    }
}

// Process profile photo if provided
$profilePhotoPath = null;
if ($profilePhoto && !empty($profilePhoto)) {
    // Validate base64 image
    $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $profilePhoto));
    
    if ($imageData === false) {
        $response['error'] = 'Invalid profile photo format';
        echo json_encode($response);
        exit;
    }
    
    // Check image size (max 2MB)
    if (strlen($imageData) > 2 * 1024 * 1024) {
        $response['error'] = 'Profile photo must be less than 2MB';
        echo json_encode($response);
        exit;
    }
    
    // Validate image type
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mimeType = finfo_buffer($finfo, $imageData);
    finfo_close($finfo);
    
    if (!in_array($mimeType, ['image/jpeg', 'image/png', 'image/gif', 'image/webp'])) {
        $response['error'] = 'Profile photo must be a valid image (JPG, PNG, GIF, or WebP)';
        echo json_encode($response);
        exit;
    }
    
    // In production, you would save the image to a file system or cloud storage
    // For demo, we'll just simulate the path
    $profilePhotoPath = 'uploads/profiles/' . uniqid() . '.jpg';
    
    // Example of how you would save the file in production:
    // file_put_contents($profilePhotoPath, $imageData);
}

// Hash password using bcrypt
$hashedPassword = hashPassword($password);

// Create new user
$newUser = [
    'id' => generateUserId(),
    'name' => $name,
    'email' => $email,
    'password' => $hashedPassword,
    'profile_photo' => $profilePhotoPath,
    'status' => 'active',
    'created_at' => date('Y-m-d H:i:s'),
    'last_login' => null,
    'ip_address' => $remoteIp
];

// Save user to database (in production, this would be an INSERT query)
saveUserToDatabase($newUser);

// Increment signup rate limit counter
apcu_store($signupKey, $signupAttempts + 1, 3600); // 1 hour expiry

// Log successful signup
logSecurityEvent('signup_successful', [
    'user_id' => $newUser['id'],
    'email' => $email,
    'ip' => $remoteIp
]);

// Send welcome email (in production)
// sendWelcomeEmail($email, $name);

// Prepare success response
$response['success'] = true;
$response['data'] = [
    'userId' => $newUser['id'],
    'userName' => $newUser['name'],
    'userEmail' => $newUser['email'],
    'signupTime' => $newUser['created_at']
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
            'password' => password_hash('Password123', PASSWORD_BCRYPT),
            'status' => 'active',
            'created_at' => '2024-01-01 00:00:00'
        ],
        [
            'id' => 2,
            'name' => 'Jane Smith',
            'email' => 'jane@athletiqo.com',
            'password' => password_hash('Password123', PASSWORD_BCRYPT),
            'status' => 'active',
            'created_at' => '2024-01-02 00:00:00'
        ]
    ];
}

/**
 * Save user to database (simulated for demo)
 * In production, this would be an INSERT query
 */
function saveUserToDatabase($user) {
    // In production, you would execute:
    // INSERT INTO users (name, email, password, profile_photo, status, created_at, ip_address) 
    // VALUES (?, ?, ?, ?, ?, ?, ?)
    // This is a placeholder for the actual database operation
    
    // For demo, we'll just log the user creation
    error_log('User created: ' . json_encode($user));
}

/**
 * Generate unique user ID
 */
function generateUserId() {
    // In production, you might use AUTO_INCREMENT from database
    // For demo, we'll generate a random ID
    return mt_rand(1000, 9999);
}

/**
 * Send welcome email (for production)
 */
function sendWelcomeEmail($email, $name) {
    // In production, you would send an actual email
    // Example using PHPMailer or similar library
    $subject = 'Welcome to Athletiqo!';
    $message = "Dear $name,\n\nThank you for joining Athletiqo! Your account has been created successfully.\n\nBest regards,\nThe Athletiqo Team";
    
    // mail($email, $subject, $message);
    // This is a placeholder for the actual email sending functionality
}

/**
 * Additional validation functions
 */

/**
 * Validate phone number (optional field)
 */
function validatePhone($phone) {
    // Remove all non-numeric characters
    $phone = preg_replace('/[^0-9]/', '', $phone);
    
    // Check if it's a valid phone number (10-15 digits)
    return strlen($phone) >= 10 && strlen($phone) <= 15;
}

/**
 * Check if username is available
 */
function isUsernameAvailable($username) {
    // In production, you would check against your database
    $users = getUsersFromDatabase();
    
    foreach ($users as $user) {
        if (isset($user['username']) && $user['username'] === $username) {
            return false;
        }
    }
    
    return true;
}

/**
 * Generate secure random token
 */
function generateSecureToken($length = 32) {
    return bin2hex(random_bytes($length));
}

/**
 * Rate limiting helper
 */
function checkRateLimit($identifier, $maxAttempts = 10, $timeWindow = 3600) {
    $key = 'rate_limit_' . md5($identifier);
    $attempts = apcu_fetch($key) ?: 0;
    
    if ($attempts >= $maxAttempts) {
        return false;
    }
    
    apcu_store($key, $attempts + 1, $timeWindow);
    return true;
}
?>
