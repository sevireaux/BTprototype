<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Start session
session_start();

// Database configuration
require_once 'config.php';

// Get POST data
$input = json_decode(file_get_contents('php://input'), true);

// Validate required fields
if (!isset($input['first_name']) || !isset($input['last_name']) || 
    !isset($input['email']) || !isset($input['message'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Please fill in all required fields'
    ]);
    exit;
}

// Sanitize input
$first_name = trim($input['first_name']);
$last_name = trim($input['last_name']);
$full_name = $first_name . ' ' . $last_name;
$email = trim($input['email']);
$contact_number = isset($input['contact_number']) ? trim($input['contact_number']) : null;
$subject = isset($input['subject']) ? trim($input['subject']) : null;
$message = trim($input['message']);

// Validate email format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid email format'
    ]);
    exit;
}

// Validate message length
if (strlen($message) < 10) {
    echo json_encode([
        'success' => false,
        'message' => 'Message must be at least 10 characters long'
    ]);
    exit;
}

try {
    // Get user_id from session if logged in
    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

    // Insert contact message into database
    $stmt = $pdo->prepare("
        INSERT INTO contacts (user_id, full_name, email, subject, message, status, created_at) 
        VALUES (?, ?, ?, ?, ?, 'pending', NOW())
    ");
    
    $stmt->execute([
        $user_id,
        $full_name,
        $email,
        $subject,
        $message
    ]);

    echo json_encode([
        'success' => true,
        'message' => 'Thank you for contacting us! We will get back to you soon.'
    ]);

} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Failed to send message. Please try again later.'
    ]);
}
?>