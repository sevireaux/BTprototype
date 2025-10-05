<?php
// user-dashboard.php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Check if user is admin (redirect to admin dashboard)
if ($_SESSION['user_role'] === 'admin') {
    header('Location: admin-dashboard.php');
    exit;
}

$user_data = [

    'username' => $_SESSION['username'],

];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart - Coffee Shop</title>
    <link rel="stylesheet" href="src/input.css">
    <link rel="stylesheet" href="dist/output.css">
</head>
<body class="bg-gray-900 text-gray-100">
    <!-- Header -->
    <?php include 'navbar.php'; ?>
    <!-- Main Content -->
    <main class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-8">Your Shopping Cart</h1>
        
        <div id="cart-container" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Cart Items -->
            <div class="lg:col-span-2">
                <div id="cart-items" class="space-y-4">
                    <div class="text-center py-8">
                        <p class="text-gray-400">Loading cart items...</p>
                    </div>
                </div>
            </div>
            
            <!-- Order Summary -->
            <div class="lg:col-span-1">
                <div class="bg-gray-800 rounded-xl p-6 sticky top-24">
                    <h2 class="text-xl font-semibold mb-4">Order Summary</h2>
                    <div class="space-y-3 mb-6">
                        <div class="flex justify-between">
                            <span>Subtotal</span>
                            <span id="subtotal">₱0.00</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Tax (10%)</span>
                            <span id="tax">₱0.00</span>
                        </div>
                        <div class="border-t border-gray-700 pt-3 flex justify-between font-bold text-xl">
                            <span>Total</span>
                            <span id="total">₱0.00</span>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-2">Order Notes (Optional)</label>
                        <textarea id="order-notes" class="w-full px-4 py-3 bg-gray-700 text-white rounded-lg border border-gray-600 focus:border-yellow-600 focus:outline-none" rows="3" placeholder="Special instructions..."></textarea>
                    </div>
                    
                    <button id="place-order-btn" class="w-full px-6 py-3 bg-gradient-to-r from-yellow-600 to-yellow-700 text-white rounded-lg font-semibold hover:shadow-lg transition">
                        Place Order
                    </button>
                    
                    <a href="products.php" class="block w-full text-center mt-3 px-6 py-3 bg-gray-700 text-white rounded-lg font-semibold hover:bg-gray-600 transition">
                        Continue Shopping
                    </a>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 mt-16 py-8">
        <div class="container mx-auto px-4 text-center">
            <p class="text-gray-400">&copy; <?php echo date('Y'); ?> Coffee Shop. All rights reserved.</p>
        </div>
    </footer>

    <script src="src/cart.js"></script>
</body>
</html>