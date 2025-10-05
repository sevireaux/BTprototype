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

// Get JSON data
 $jsonData = file_get_contents('php://input');
 $data = json_decode($jsonData, true);

if (!isset($data['cart_item_id'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Missing cart item ID'
    ]);
    exit;
}

 $cartItemId = $data['cart_item_id'];

try {
    // Verify the cart item belongs to the current user
    $stmt = $pdo->prepare("
        SELECT ci.cart_item_id 
        FROM cart_items ci
        JOIN carts c ON ci.cart_id = c.cart_id
        WHERE ci.cart_item_id = ? AND c.user_id = ?
    ");
    $stmt->execute([$cartItemId, $_SESSION['user_id']]);
    $cartItem = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$cartItem) {
        echo json_encode([
            'success' => false,
            'message' => 'Cart item not found'
        ]);
        exit;
    }
    
    // Delete the cart item
    $stmt = $pdo->prepare("DELETE FROM cart_items WHERE cart_item_id = ?");
    $stmt->execute([$cartItemId]);
    
    echo json_encode([
        'success' => true,
        'message' => 'Item removed from cart'
    ]);
    
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $e->getMessage()
    ]);
}
?>