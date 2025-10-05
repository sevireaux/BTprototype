<?php
session_start();

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Database configuration
require_once 'config.php';

// Get POST data
$input = json_decode(file_get_contents('php://input'), true);

// Validate input
if (!isset($input['username']) || !isset($input['password'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Username and password are required'
    ]);
    exit;
}

$username = trim($input['username']);
$password = $input['password'];

try {
    // Check if user exists (username or email)
    $stmt = $pdo->prepare("
        SELECT user_id, username, first_name, last_name, email, contact_number, password, user_role 
        FROM users 
        WHERE username = ? OR email = ?
    ");
    $stmt->execute([$username, $username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        echo json_encode([
            'success' => false,
            'message' => 'Invalid username or password'
        ]);
        exit;
    }

    // Verify password
    if (!password_verify($password, $user['password'])) {
        echo json_encode([
            'success' => false,
            'message' => 'Invalid username or password'
        ]);
        exit;
    }

    // Remove password from user data
    unset($user['password']);

    // Store user data in session
    $_SESSION['user_id'] = $user['user_id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['user_role'] = $user['user_role'];

    echo json_encode([
        'success' => true,
        'message' => 'Login successful!',
        'user' => $user
    ]);

} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Login failed: ' . $e->getMessage()
    ]);
}
?>