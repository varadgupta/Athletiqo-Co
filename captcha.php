<?php
session_start();

// Generate random CAPTCHA code
function generateCaptchaCode($length = 6) {
    $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
}

// Create CAPTCHA image
function createCaptchaImage($code) {
    // Image dimensions
    $width = 200;
    $height = 60;
    
    // Create image
    $image = imagecreatetruecolor($width, $height);
    
    // Colors
    $bgColor = imagecolorallocate($image, 240, 240, 240);
    $textColor = imagecolorallocate($image, 50, 50, 50);
    $lineColor = imagecolorallocate($image, 200, 200, 200);
    $dotColor = imagecolorallocate($image, 150, 150, 150);
    
    // Fill background
    imagefill($image, 0, 0, $bgColor);
    
    // Add random lines for noise
    for ($i = 0; $i < 5; $i++) {
        imageline($image, 
            rand(0, $width), rand(0, $height),
            rand(0, $width), rand(0, $height),
            $lineColor
        );
    }
    
    // Add random dots
    for ($i = 0; $i < 50; $i++) {
        imagesetpixel($image, rand(0, $width), rand(0, $height), $dotColor);
    }
    
    // Add text with some distortion
    $fontSize = 24;
    $angle = rand(-5, 5);
    $x = rand(20, 40);
    $y = rand(35, 45);
    
    imagettftext($image, $fontSize, $angle, $x, $y, $textColor, __DIR__ . '/arial.ttf', $code);
    
    // Output image
    header('Content-Type: image/png');
    header('Cache-Control: no-cache, no-store, must-revalidate');
    header('Pragma: no-cache');
    header('Expires: 0');
    
    imagepng($image);
    imagedestroy($image);
}

// Generate and store CAPTCHA
$captchaCode = generateCaptchaCode();
$_SESSION['captcha_code'] = $captchaCode;
$_SESSION['captcha_time'] = time();

// Output the image
createCaptchaImage($captchaCode);
?>
