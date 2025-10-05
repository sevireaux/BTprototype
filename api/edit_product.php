<?php
session_start();
header('Content-Type: application/json');
require_once 'config.php';

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    echo json_encode([
        'success' => false,
        'message' => 'Access denied'
    ]);
    exit;
}

// Get product ID from query string
if (!isset($_GET['product_id'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Product ID not provided'
    ]);
    exit;
}

 $productId = $_GET['product_id'];

try {
    // Get product details
    $stmt = $pdo->prepare("SELECT * FROM products WHERE product_id = ?");
    $stmt->execute([$productId]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$product) {
        echo json_encode([
            'success' => false,
            'message' => 'Product not found'
        ]);
        exit;
    }
    
    // Get product categories
    $stmt = $pdo->prepare("
        SELECT c.category_name 
        FROM product_categories pc
        JOIN categories c ON pc.category_id = c.category_id
        WHERE pc.product_id = ?
    ");
    $stmt->execute([$productId]);
    $categories = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    // Add categories to product array
    $product['categories'] = $categories;
    
    echo json_encode([
        'success' => true,
        'product' => $product
    ]);
    
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $e->getMessage()
    ]);
}
?>