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
if (!isset($input['username']) || !isset($input['first_name']) || !isset($input['last_name']) || 
    !isset($input['email']) || !isset($input['contact_number']) || !isset($input['password'])) {
    echo json_encode([
        'success' => false,
        'message' => 'All fields are required'
    ]);
    exit;
}

// Sanitize input
$username = trim($input['username']);
$first_name = trim($input['first_name']);
$last_name = trim($input['last_name']);
$email = trim($input['email']);
$contact_number = trim($input['contact_number']);
$password = $input['password'];

// Validate email format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid email format'
    ]);
    exit;
}

// Validate password length
if (strlen($password) < 6) {
    echo json_encode([
        'success' => false,
        'message' => 'Password must be at least 6 characters long'
    ]);
    exit;
}

try {
    // Check if username already exists
    $stmt = $pdo->prepare("SELECT user_id FROM users WHERE username = ?");
    $stmt->execute([$username]);
    if ($stmt->fetch()) {
        echo json_encode([
            'success' => false,
            'message' => 'Username already exists'
        ]);
        exit;
    }

    // Check if email already exists
    $stmt = $pdo->prepare("SELECT user_id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        echo json_encode([
            'success' => false,
            'message' => 'Email already exists'
        ]);
        exit;
    }

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert new user
    $stmt = $pdo->prepare("
        INSERT INTO users (username, first_name, last_name, email, contact_number, password, user_role) 
        VALUES (?, ?, ?, ?, ?, ?, 'customer')
    ");
    
    $stmt->execute([$username, $first_name, $last_name, $email, $contact_number, $hashed_password]);

    echo json_encode([
        'success' => true,
        'message' => 'Registration successful! Redirecting to login...'
    ]);

} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Registration failed: ' . $e->getMessage()
    ]);
}
?>