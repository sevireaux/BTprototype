get_cart_count.php

<?php
session_start();
header('Content-Type: application/json');
require_once 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        'success' => true,
        'count' => 0
    ]);
    exit;
}

$user_id = $_SESSION['user_id'];

try {
    // Get cart for user
    $stmt = $pdo->prepare("SELECT cart_id FROM carts WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $cart = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$cart) {
        echo json_encode([
            'success' => true,
            'count' => 0
        ]);
        exit;
    }
    
    // Get total items in cart
    $stmt = $pdo->prepare("SELECT SUM(quantity) as total_items FROM cart_items WHERE cart_id = ?");
    $stmt->execute([$cart['cart_id']]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    $count = $result['total_items'] ? intval($result['total_items']) : 0;
    
    echo json_encode([
        'success' => true,
        'count' => $count
    ]);
    
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $e->getMessage(),
        'count' => 0
    ]);
}
?>