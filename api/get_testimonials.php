<?php
header('Content-Type: application/json');
require_once 'config.php';

try {
    $stmt = $pdo->query("
        SELECT t.*, u.email
        FROM testimonials t
        LEFT JOIN users u ON t.user_id = u.user_id
        ORDER BY t.created_at DESC
    ");
    
    $testimonials = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode([
        'success' => true,
        'testimonials' => $testimonials
    ]);
    
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error: ' . $e->getMessage()
    ]);
}
?>