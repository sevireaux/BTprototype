<?php
session_start();
require_once 'api/config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Get order ID from URL
if (!isset($_GET['order_id'])) {
    header('Location: index.php');
    exit;
}

 $orderId = $_GET['order_id'];
 $user_id = $_SESSION['user_id'];

try {
    // Get order details
    $stmt = $pdo->prepare("
        SELECT o.order_id, o.order_number, o.total_amount, o.status, o.order_date, o.notes
        FROM orders o
        WHERE o.order_id = ? AND o.user_id = ?
    ");
    $stmt->execute([$orderId, $user_id]);
    $order = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$order) {
        header('Location: index.php');
        exit;
    }
    
    // Get order items
    $stmt = $pdo->prepare("
        SELECT oi.product_id, oi.quantity, oi.unit_price, oi.subtotal, p.product_name, p.photo_url
        FROM order_items oi
        JOIN products p ON oi.product_id = p.product_id
        WHERE oi.order_id = ?
    ");
    $stmt->execute([$orderId]);
    $orderItems = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation - Coffee Shop</title>
    <link rel="stylesheet" href="src/input.css">
    <link rel="stylesheet" href="dist/output.css">
</head>
<body class="bg-gray-900 text-gray-100">
    <!-- Header -->
    <header class="bg-gray-800 shadow-lg sticky top-0 z-50">
        <div class="container mx-auto px-4 py-4 flex justify-between items-center">
            <a href="index.php" class="text-2xl font-bold gradient-text">Coffee Shop</a>
            <nav class="flex items-center space-x-6">
                <a href="index.php" class="hover:text-yellow-500 transition">Home</a>
                <a href="products.php" class="hover:text-yellow-500 transition">Products</a>
                <a href="cart.php" class="flex items-center hover:text-yellow-500 transition">
                    <span>Cart</span>
                    <span id="cart-count" class="ml-2 bg-yellow-600 text-white rounded-full px-2 py-1 text-xs">0</span>
                </a>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="profile.php" class="hover:text-yellow-500 transition">Profile</a>
                    <?php if ($_SESSION['user_role'] === 'admin'): ?>
                        <a href="admin-dashboard.php" class="hover:text-yellow-500 transition">Admin</a>
                    <?php endif; ?>
                    <a href="logout.php" class="hover:text-yellow-500 transition">Logout</a>
                <?php else: ?>
                    <a href="login.php" class="hover:text-yellow-500 transition">Login</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container mx-auto px-4 py-8">
        <div class="max-w-3xl mx-auto">
            <div class="bg-green-900 bg-opacity-50 border border-green-700 rounded-xl p-6 mb-8">
                <h1 class="text-3xl font-bold text-green-400 mb-2">Order Confirmed!</h1>
                <p class="text-gray-300">Thank you for your order. We've received it and will start preparing it shortly.</p>
            </div>
            
            <div class="bg-gray-800 rounded-xl p-6 mb-8">
                <h2 class="text-xl font-semibold mb-4">Order Details</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div>
                        <p class="text-gray-400 text-sm">Order Number</p>
                        <p class="text-lg font-semibold"><?php echo htmlspecialchars($order['order_number']); ?></p>
                    </div>
                    <div>
                        <p class="text-gray-400 text-sm">Order Date</p>
                        <p class="text-lg"><?php echo date('F j, Y, g:i a', strtotime($order['order_date'])); ?></p>
                    </div>
                    <div>
                        <p class="text-gray-400 text-sm">Status</p>
                        <p class="text-lg"><span class="status-badge status-<?php echo $order['status']; ?>"><?php echo ucfirst($order['status']); ?></span></p>
                    </div>
                    <div>
                        <p class="text-gray-400 text-sm">Total Amount</p>
                        <p class="text-lg font-bold text-yellow-600">₱<?php echo number_format($order['total_amount'], 2); ?></p>
                    </div>
                </div>
                
                <?php if (!empty($order['notes'])): ?>
                <div class="mb-6">
                    <p class="text-gray-400 text-sm mb-1">Order Notes</p>
                    <p class="text-gray-300"><?php echo htmlspecialchars($order['notes']); ?></p>
                </div>
                <?php endif; ?>
                
                <h3 class="text-lg font-semibold mb-3">Order Items</h3>
                <div class="space-y-3">
                    <?php foreach ($orderItems as $item): ?>
                    <div class="flex items-center gap-4 pb-3 border-b border-gray-700">
                        <div class="w-16 h-16 bg-gray-700 rounded-lg overflow-hidden flex-shrink-0">
                            <?php if ($item['photo_url']): ?>
                                <img src="<?php echo htmlspecialchars($item['photo_url']); ?>" alt="<?php echo htmlspecialchars($item['product_name']); ?>" class="w-full h-full object-cover">
                            <?php else: ?>
                                <div class="w-full h-full flex items-center justify-center text-gray-500">No Image</div>
                            <?php endif; ?>
                        </div>
                        <div class="flex-grow">
                            <h4 class="font-semibold"><?php echo htmlspecialchars($item['product_name']); ?></h4>
                            <p class="text-gray-400">₱<?php echo number_format($item['unit_price'], 2); ?> × <?php echo $item['quantity']; ?></p>
                        </div>
                        <div class="text-right">
                            <p class="font-semibold">₱<?php echo number_format($item['subtotal'], 2); ?></p>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <div class="flex gap-4">
                <a href="index.php" class="flex-1 block text-center px-6 py-3 bg-gray-700 text-white rounded-lg font-semibold hover:bg-gray-600 transition">
                    Continue Shopping
                </a>
                <a href="profile.php" class="flex-1 block text-center px-6 py-3 bg-yellow-600 text-white rounded-lg font-semibold hover:bg-yellow-700 transition">
                    View My Orders
                </a>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 mt-16 py-8">
        <div class="container mx-auto px-4 text-center">
            <p class="text-gray-400">&copy; <?php echo date('Y'); ?> Coffee Shop. All rights reserved.</p>
        </div>
    </footer>

    <script>
        // Update cart count in header
        async function updateCartCount() {
            try {
                const response = await fetch('api/get_cart_count.php');
                const data = await response.json();
                
                if (data.success) {
                    document.getElementById('cart-count').textContent = data.count;
                }
            } catch (error) {
                console.error('Error updating cart count:', error);
            }
        }
        
        document.addEventListener('DOMContentLoaded', function() {
            updateCartCount();
        });
    </script>
</body>
</html>