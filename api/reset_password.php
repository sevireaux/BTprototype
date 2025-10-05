<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Database configuration
require_once 'config.php';

// Get POST data
$input = json_decode(file_get_contents('php://input'), true);

// Validate input
if (!isset($input['email'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Email is required'
    ]);
    exit;
}

$email = trim($input['email']);

// Validate email format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid email format'
    ]);
    exit;
}

try {
    // Check if email exists
    $stmt = $pdo->prepare("SELECT user_id, email, first_name FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        // For security, don't reveal if email exists or not
        echo json_encode([
            'success' => true,
            'message' => 'If an account exists with this email, you will receive password reset instructions.'
        ]);
        exit;
    }

    // Generate reset token
    $reset_token = bin2hex(random_bytes(32));
    $reset_expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));

    // Store reset token in database (you may need to create a password_resets table)
    // For now, we'll just simulate sending an email
    
    // In production, you would:
    // 1. Store the token in a password_resets table
    // 2. Send an email with a reset link containing the token
    // 3. Create a reset password page that validates the token
    
    // Example email content:
    $reset_link = "http://yourdomain.com/reset_password_form.php?token=" . $reset_token;
    
    // TODO: Implement actual email sending using PHPMailer or similar
    // mail($email, "Password Reset Request", "Click here to reset: " . $reset_link);

    echo json_encode([
        'success' => true,
        'message' => 'Password reset instructions have been sent to your email address.',
        // For development/testing only - remove in production:
        'dev_reset_link' => $reset_link
    ]);

} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'An error occurred. Please try again later.'
    ]);
}
?>