<?php
session_start();

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once 'config.php';

// Check if user is admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    echo json_encode([
        'success' => false,
        'message' => 'Unauthorized access. Admin only.'
    ]);
    exit;
}

try {
    // Get total orders
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM orders");
    $total_orders = $stmt->fetch()['total'];

    // Get total sales
    $stmt = $pdo->query("SELECT SUM(total_amount) as total FROM orders WHERE status = 'completed'");
    $total_sales = $stmt->fetch()['total'] ?? 0;

    // Get trending products (top 5)
    $stmt = $pdo->query("
        SELECT 
            p.product_name,
            SUM(oi.quantity) as total_sold,
            SUM(oi.subtotal) as revenue
        FROM order_items oi
        JOIN products p ON oi.product_id = p.product_id
        JOIN orders o ON oi.order_id = o.order_id
        WHERE o.status = 'completed'
        GROUP BY oi.product_id
        ORDER BY total_sold DESC
        LIMIT 5
    ");
    $trending_products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Get recent orders (last 10)
    $stmt = $pdo->query("
        SELECT 
            o.order_id,
            o.order_number,
            o.total_amount,
            o.status,
            o.order_date,
            CONCAT(u.first_name, ' ', u.last_name) as customer_name
        FROM orders o
        JOIN users u ON o.user_id = u.user_id
        ORDER BY o.order_date DESC
        LIMIT 10
    ");
    $recent_orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Get sales data for the last 7 days
    $stmt = $pdo->query("
        SELECT 
            DATE(order_date) as date,
            SUM(total_amount) as sales
        FROM orders
        WHERE status = 'completed'
        AND order_date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
        GROUP BY DATE(order_date)
        ORDER BY date ASC
    ");
    $sales_data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true,
        'total_orders' => $total_orders,
        'total_sales' => $total_sales,
        'trending_products' => $trending_products,
        'recent_orders' => $recent_orders,
        'sales_data' => $sales_data
    ]);

} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error loading dashboard: ' . $e->getMessage()
    ]);
}
?>