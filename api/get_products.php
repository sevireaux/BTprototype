<?php
header('Content-Type: application/json');
require_once 'config.php';

try {
    $stmt = $pdo->query("
        SELECT p.*, GROUP_CONCAT(c.category_name) as categories
        FROM products p
        LEFT JOIN product_categories pc ON p.product_id = pc.product_id
        LEFT JOIN categories c ON pc.category_id = c.category_id
        GROUP BY p.product_id
        ORDER BY p.created_at DESC
    ");
    
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Format categories as array
    foreach ($products as &$product) {
        $product['categories'] = $product['categories'] ? explode(',', $product['categories']) : [];
    }
    
    echo json_encode([
        'success' => true,
        'products' => $products
    ]);
    
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error: ' . $e->getMessage()
    ]);
}
?>