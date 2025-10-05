<?php
session_start();
header('Content-Type: application/json');
require_once 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        'success' => false,
        'message' => 'User not logged in'
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
            'items' => []
        ]);
        exit;
    }
    
    // Get cart items with product details
    $stmt = $pdo->prepare("
        SELECT ci.cart_item_id, ci.quantity, p.product_id, p.product_name, p.price, p.description, p.photo_url,
               (p.price * ci.quantity) as subtotal
        FROM cart_items ci
        JOIN products p ON ci.product_id = p.product_id
        WHERE ci.cart_id = ?
    ");
    $stmt->execute([$cart['cart_id']]);
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode([
        'success' => true,
        'items' => $items
    ]);
    
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $e->getMessage()
    ]);
}
?>