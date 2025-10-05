<?php
session_start();

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id']) && !isset($_GET['user_id'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Unauthorized access'
    ]);
    exit;
}

$user_id = $_GET['user_id'] ?? $_SESSION['user_id'];

try {
    // Get user's orders with details
    $stmt = $pdo->prepare("
        SELECT 
            o.order_id,
            o.order_number,
            o.total_amount,
            o.status,
            o.order_date,
            o.completed_at,
            COUNT(oi.order_item_id) as item_count
        FROM orders o
        LEFT JOIN order_items oi ON o.order_id = oi.order_id
        WHERE o.user_id = ?
        GROUP BY o.order_id
        ORDER BY o.order_date DESC
        LIMIT 20
    ");
    
    $stmt->execute([$user_id]);
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Get total order count
    $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM orders WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $total_orders = $stmt->fetch()['total'];

    echo json_encode([
        'success' => true,
        'orders' => $orders,
        'total_orders' => $total_orders
    ]);

} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error fetching orders: ' . $e->getMessage()
    ]);
}
?>