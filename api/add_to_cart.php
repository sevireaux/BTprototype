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

// Get JSON input
$input = json_decode(file_get_contents('php://input'), true);

if (!isset($input['product_id']) || !isset($input['quantity'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Product ID and quantity are required'
    ]);
    exit;
}

$product_id = intval($input['product_id']);
$quantity = intval($input['quantity']);

if ($quantity <= 0) {
    echo json_encode([
        'success' => false,
        'message' => 'Quantity must be greater than 0'
    ]);
    exit;
}

try {
    // Check if product exists and has enough stock
    $stmt = $pdo->prepare("SELECT product_id, stock_quantity, is_available FROM products WHERE product_id = ?");
    $stmt->execute([$product_id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$product) {
        echo json_encode([
            'success' => false,
            'message' => 'Product not found'
        ]);
        exit;
    }
    
    if (!$product['is_available']) {
        echo json_encode([
            'success' => false,
            'message' => 'Product is not available'
        ]);
        exit;
    }
    
    if ($product['stock_quantity'] < $quantity) {
        echo json_encode([
            'success' => false,
            'message' => 'Not enough stock available'
        ]);
        exit;
    }
    
    // Check if user has an active cart, if not create one
    $stmt = $pdo->prepare("SELECT cart_id FROM carts WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $cart = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$cart) {
        // Create new cart
        $stmt = $pdo->prepare("INSERT INTO carts (user_id) VALUES (?)");
        $stmt->execute([$user_id]);
        $cart_id = $pdo->lastInsertId();
    } else {
        $cart_id = $cart['cart_id'];
    }
    
    // Check if product already in cart
    $stmt = $pdo->prepare("SELECT cart_item_id, quantity FROM cart_items WHERE cart_id = ? AND product_id = ?");
    $stmt->execute([$cart_id, $product_id]);
    $cart_item = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($cart_item) {
        // Update quantity
        $new_quantity = $cart_item['quantity'] + $quantity;
        
        // Check if new quantity exceeds stock
        if ($new_quantity > $product['stock_quantity']) {
            echo json_encode([
                'success' => false,
                'message' => 'Adding this quantity would exceed available stock'
            ]);
            exit;
        }
        
        $stmt = $pdo->prepare("UPDATE cart_items SET quantity = ? WHERE cart_item_id = ?");
        $stmt->execute([$new_quantity, $cart_item['cart_item_id']]);
    } else {
        // Insert new cart item
        $stmt = $pdo->prepare("INSERT INTO cart_items (cart_id, product_id, quantity) VALUES (?, ?, ?)");
        $stmt->execute([$cart_id, $product_id, $quantity]);
    }
    
    echo json_encode([
        'success' => true,
        'message' => 'Product added to cart successfully'
    ]);
    
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $e->getMessage()
    ]);
}
?>