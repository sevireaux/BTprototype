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

 $notes = isset($data['notes']) ? $data['notes'] : '';

try {
    // Get cart for user
    $stmt = $pdo->prepare("SELECT cart_id FROM carts WHERE user_id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $cart = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$cart) {
        echo json_encode([
            'success' => false,
            'message' => 'Your cart is empty'
        ]);
        exit;
    }
    
    // Get cart items
    $stmt = $pdo->prepare("
        SELECT ci.cart_item_id, ci.product_id, ci.quantity, p.price, p.stock_quantity
        FROM cart_items ci
        JOIN products p ON ci.product_id = p.product_id
        WHERE ci.cart_id = ?
    ");
    $stmt->execute([$cart['cart_id']]);
    $cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($cartItems)) {
        echo json_encode([
            'success' => false,
            'message' => 'Your cart is empty'
        ]);
        exit;
    }
    
    // Check stock availability
    foreach ($cartItems as $item) {
        if ($item['quantity'] > $item['stock_quantity']) {
            echo json_encode([
                'success' => false,
                'message' => 'Insufficient stock for one or more items'
            ]);
            exit;
        }
    }
    
    // Generate unique order number
    $orderNumber = 'ORD-' . date('YmdHis') . '-' . $_SESSION['user_id'];
    
    // Calculate total amount
    $totalAmount = 0;
    foreach ($cartItems as $item) {
        $totalAmount += $item['price'] * $item['quantity'];
    }
    
    // Start transaction
    $pdo->beginTransaction();
    
    // Create order
    $stmt = $pdo->prepare("
        INSERT INTO orders (order_number, user_id, total_amount, status, notes)
        VALUES (?, ?, ?, 'pending', ?)
    ");
    $stmt->execute([$orderNumber, $_SESSION['user_id'], $totalAmount, $notes]);
    $orderId = $pdo->lastInsertId();
    
    // Create order items
    foreach ($cartItems as $item) {
        $subtotal = $item['price'] * $item['quantity'];
        
        $stmt = $pdo->prepare("
            INSERT INTO order_items (order_id, product_id, quantity, unit_price, subtotal)
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->execute([$orderId, $item['product_id'], $item['quantity'], $item['price'], $subtotal]);
        
        // Update product stock
        $stmt = $pdo->prepare("
            UPDATE products SET stock_quantity = stock_quantity - ? WHERE product_id = ?
        ");
        $stmt->execute([$item['quantity'], $item['product_id']]);
    }
    
    // Clear cart
    $stmt = $pdo->prepare("DELETE FROM cart_items WHERE cart_id = ?");
    $stmt->execute([$cart['cart_id']]);
    
    // Commit transaction
    $pdo->commit();
    
    echo json_encode([
        'success' => true,
        'message' => 'Order placed successfully',
        'order_id' => $orderId,
        'order_number' => $orderNumber
    ]);
    
} catch (PDOException $e) {
    // Rollback transaction on error
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $e->getMessage()
    ]);
}
?>