<?php
session_start();
header('Content-Type: application/json');

// Check if user is admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit;
}

require_once 'config.php';

try {
    $product_id = $_POST['product_id'] ?? null;
    $product_name = trim($_POST['product_name']);
    $price = floatval($_POST['price']);
    $description = trim($_POST['description'] ?? '');
    $stock_quantity = intval($_POST['stock_quantity'] ?? 0);
    $categories = json_decode($_POST['categories'], true);
    
    // Validate input
    if (empty($product_name) || $price <= 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid product data']);
        exit;
    }
    
    // Handle file upload
    $photo_url = $_POST['current_photo_url'] ?? null;
    
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = '../uploads/products/';
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        
        $file_extension = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
        $new_filename = uniqid() . '.' . $file_extension;
        $upload_path = $upload_dir . $new_filename;
        
        if (move_uploaded_file($_FILES['photo']['tmp_name'], $upload_path)) {
            $photo_url = 'uploads/products/' . $new_filename;
        }
    }
    
    // Start transaction
    $pdo->beginTransaction();
    
    if ($product_id) {
        // Update existing product
        $stmt = $pdo->prepare("
            UPDATE products 
            SET product_name = ?, price = ?, description = ?, stock_quantity = ?, photo_url = ?, updated_at = NOW()
            WHERE product_id = ?
        ");
        $stmt->execute([$product_name, $price, $description, $stock_quantity, $photo_url, $product_id]);
        
        // Delete old category associations
        $stmt = $pdo->prepare("DELETE FROM product_categories WHERE product_id = ?");
        $stmt->execute([$product_id]);
    } else {
        // Insert new product
        $stmt = $pdo->prepare("
            INSERT INTO products (product_name, price, description, stock_quantity, photo_url, is_available, created_at, updated_at)
            VALUES (?, ?, ?, ?, ?, 1, NOW(), NOW())
        ");
        $stmt->execute([$product_name, $price, $description, $stock_quantity, $photo_url]);
        $product_id = $pdo->lastInsertId();
    }
    
    // Insert category associations
    if (!empty($categories)) {
        // Get category IDs
        $placeholders = implode(',', array_fill(0, count($categories), '?'));
        $stmt = $pdo->prepare("SELECT category_id, category_name FROM categories WHERE category_name IN ($placeholders)");
        $stmt->execute($categories);
        $categoryIds = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Insert into product_categories
        $stmt = $pdo->prepare("INSERT INTO product_categories (product_id, category_id) VALUES (?, ?)");
        foreach ($categoryIds as $category) {
            $stmt->execute([$product_id, $category['category_id']]);
        }
    }
    
    $pdo->commit();
    
    echo json_encode([
        'success' => true,
        'message' => $product_id ? 'Product updated successfully' : 'Product added successfully',
        'product_id' => $product_id
    ]);
    
} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    
    echo json_encode([
        'success' => false,
        'message' => 'Error: ' . $e->getMessage()
    ]);
}
?>