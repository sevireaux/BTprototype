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

if (!isset($data['cart_item_id']) || !isset($data['quantity'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Missing required data'
    ]);
    exit;
}

 $cartItemId = $data['cart_item_id'];
 $newQuantity = $data['quantity'];

if ($newQuantity < 1) {
    echo json_encode([
        'success' => false,
        'message' => 'Quantity must be at least 1'
    ]);
    exit;
}

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
    
    // Update the quantity
    $stmt = $pdo->prepare("UPDATE cart_items SET quantity = ? WHERE cart_item_id = ?");
    $stmt->execute([$newQuantity, $cartItemId]);
    
    echo json_encode([
        'success' => true,
        'message' => 'Cart item updated successfully'
    ]);
    
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $e->getMessage()
    ]);
}
?>