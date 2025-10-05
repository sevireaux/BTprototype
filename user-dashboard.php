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
    <title>Account - Beantage</title>
    <link rel="stylesheet" href="dist/output.css">
    <link rel="stylesheet" href="src/input.css">
    <style>
        .nav-item {
            transition: all 0.3s ease;
        }
        .nav-item:hover {
            transform: translateX(5px);
            background-color: rgba(217, 119, 6, 0.2);
        }
        .nav-item.active {
            background-color: rgba(217, 119, 6, 0.3);
            border-left: 4px solid #d97706;
        }
    </style>
</head>
<body class="bg-stone-100">
    
    <?php include 'navbar.php'; ?>

    <div class="container mx-auto px-4 py-8 mt-20">
        <h2 class="text-4xl font-bold text-center mb-8">Account</h2>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Panel - Order History & Coupons -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Order History -->
                <div class="bg-amber-200 rounded-lg p-6 fade-in">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-2xl font-bold text-stone-800">Order History</h3>
                        <a href="#" class="text-stone-700 hover:text-amber-900 underline text-sm">View more</a>
                    </div>
                    <div class="grid grid-cols-3 gap-4" id="orderHistory">
                        <!-- Order cards will be loaded here -->
                        <div class="bg-stone-300 rounded-lg h-32 flex items-center justify-center">
                            <p class="text-stone-600 text-sm">Loading orders...</p>
                        </div>
                    </div>
                </div>

                <!-- Coupons -->
                <div class="bg-amber-200 rounded-lg p-6 fade-in">
                    <h3 class="text-2xl font-bold text-stone-800 mb-6">Coupons</h3>
                    <div class="grid grid-cols-6 gap-3" id="coupons">
                        <!-- Coupon cards will be loaded here -->
                        <div class="bg-stone-50 rounded h-16"></div>
                        <div class="bg-stone-50 rounded h-16"></div>
                        <div class="bg-stone-50 rounded h-16"></div>
                        <div class="bg-stone-50 rounded h-16"></div>
                        <div class="bg-stone-50 rounded h-16"></div>
                        <div class="bg-stone-50 rounded h-16"></div>
                    </div>
                </div>
            </div>

            <!-- Right Panel - User Info & Navigation -->
            <div class="bg-amber-200 rounded-lg p-6 fade-in h-fit sticky top-24">
                <!-- User Info -->
                <div class="text-center mb-8 pb-6 border-b-2 border-amber-300">
                    <p class="text-stone-600 text-sm" id="userUsername">
                        @<?php echo htmlspecialchars($user_data['username']); ?>
                    </p>
                </div>

                <!-- Navigation Menu -->
                <nav class="space-y-2">
                    <a href="#orders" class="nav-item active block px-4 py-3 text-stone-800 font-semibold rounded">
                        Orders
                    </a>
                    <a href="#personal-data" class="nav-item block px-4 py-3 text-stone-800 font-semibold rounded">
                        Personal Data
                    </a>
                    <a href="#change-password" class="nav-item block px-4 py-3 text-stone-800 font-semibold rounded">
                        Change Password
                    </a>
                    
                    <div class="pt-6 mt-6 border-t-2 border-amber-300">
                        <a href="api/logout.php" class="w-full block text-center text-red-600 hover:text-red-700 font-bold py-2 transition">
                            Sign Out
                        </a>
                    </div>
                </nav>
            </div>
        </div>

        <!-- Rewards Section -->
        <div class="mt-8 bg-amber-200 rounded-lg p-8 fade-in">
            <h2 class="text-3xl font-bold text-center mb-8">Rewards</h2>
            
            <div class="space-y-6">
                <!-- Reward 1 -->
                <div class="bg-stone-50 rounded-lg p-6">
                    <div class="mb-3">
                        <p class="font-bold text-stone-800">Task:</p>
                        <p class="text-stone-700 text-sm ml-4">Reach 50 orders</p>
                        <p class="font-bold text-stone-800 mt-2">Reward:</p>
                        <p class="text-stone-700 text-sm ml-4">Coupon in exchange for a free small drink of your choice</p>
                    </div>
                    <div class="relative pt-2">
                        <div class="flex items-center justify-between mb-2">
                            <div class="w-full bg-stone-300 rounded-full h-4 overflow-hidden">
                                <div class="bg-blue-500 h-4 rounded-full transition-all duration-500" style="width: 0%" id="progress1"></div>
                            </div>
                            <span class="text-stone-800 font-semibold ml-4 whitespace-nowrap" id="progress1Text">0 / 50</span>
                        </div>
                    </div>
                </div>

                <!-- Reward 2 -->
                <div class="bg-stone-50 rounded-lg p-6">
                    <div class="mb-3">
                        <p class="font-bold text-stone-800">Task:</p>
                        <p class="text-stone-700 text-sm ml-4">Reach 250 orders</p>
                        <p class="font-bold text-stone-800 mt-2">Reward:</p>
                        <p class="text-stone-700 text-sm ml-4">Coupon in exchange for a free medium drink of your choice</p>
                    </div>
                    <div class="relative pt-2">
                        <div class="flex items-center justify-between mb-2">
                            <div class="w-full bg-stone-300 rounded-full h-4 overflow-hidden">
                                <div class="bg-blue-500 h-4 rounded-full transition-all duration-500" style="width: 0%" id="progress2"></div>
                            </div>
                            <span class="text-stone-800 font-semibold ml-4 whitespace-nowrap" id="progress2Text">0 / 250</span>
                        </div>
                    </div>
                </div>

                <!-- Reward 3 -->
                <div class="bg-stone-50 rounded-lg p-6">
                    <div class="mb-3">
                        <p class="font-bold text-stone-800">Task:</p>
                        <p class="text-stone-700 text-sm ml-4">Reach 1250 orders</p>
                        <p class="font-bold text-stone-800 mt-2">Reward:</p>
                        <p class="text-stone-700 text-sm ml-4">Coupon in exchange for a free large drink and a Beantage tumbler</p>
                    </div>
                    <div class="relative pt-2">
                        <div class="flex items-center justify-between mb-2">
                            <div class="w-full bg-stone-300 rounded-full h-4 overflow-hidden">
                                <div class="bg-blue-500 h-4 rounded-full transition-all duration-500" style="width: 0%" id="progress3"></div>
                            </div>
                            <span class="text-stone-800 font-semibold ml-4 whitespace-nowrap" id="progress3Text">0 / 1250</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
        <?include 'footer.php';?>
    <script src="src/index.js"></script>
    <script>
        async function loadUserData() {
            try {
                const response = await fetch('api/get_orders.php?user_id=<?php echo $_SESSION['user_id']; ?>');
                const data = await response.json();
                
                if (data.success) {
                    // Update progress bars
                    const totalOrders = data.total_orders || 0;
                    
                    // Reward 1 (50 orders)
                    const progress1 = Math.min((totalOrders / 50) * 100, 100);
                    document.getElementById('progress1').style.width = progress1 + '%';
                    document.getElementById('progress1Text').textContent = totalOrders + ' / 50';
                    
                    // Reward 2 (250 orders)
                    const progress2 = Math.min((totalOrders / 250) * 100, 100);
                    document.getElementById('progress2').style.width = progress2 + '%';
                    document.getElementById('progress2Text').textContent = totalOrders + ' / 250';
                    
                    // Reward 3 (1250 orders)
                    const progress3 = Math.min((totalOrders / 1250) * 100, 100);
                    document.getElementById('progress3').style.width = progress3 + '%';
                    document.getElementById('progress3Text').textContent = totalOrders + ' / 1250';
                    
                    // Display recent orders
                    displayOrders(data.orders);
                }
            } catch (error) {
                console.error('Error loading user data:', error);
            }
        }
        
        function displayOrders(orders) {
            const container = document.getElementById('orderHistory');
            
            if (!orders || orders.length === 0) {
                container.innerHTML = '<div class="col-span-3 bg-stone-300 rounded-lg h-32 flex items-center justify-center"><p class="text-stone-600 text-sm">No orders yet</p></div>';
                return;
            }
            
            container.innerHTML = orders.slice(0, 3).map(order => `
                <div class="bg-stone-50 rounded-lg p-4 hover:shadow-lg transition">
                    <p class="font-semibold text-stone-800 text-sm">${order.order_number}</p>
                    <p class="text-stone-600 text-xs mt-1">₱${parseFloat(order.total_amount).toFixed(2)}</p>
                    <p class="text-xs mt-2 ${getStatusColor(order.status)}">${order.status}</p>
                </div>
            `).join('');
        }
        
        function getStatusColor(status) {
            const colors = {
                'pending': 'text-yellow-600',
                'preparing': 'text-blue-600',
                'completed': 'text-green-600',
                'cancelled': 'text-red-600'
            };
            return colors[status] || 'text-stone-600';
        }
        
        // Navigation active state
        document.querySelectorAll('.nav-item').forEach(item => {
            item.addEventListener('click', function(e) {
                const href = this.getAttribute('href');
                if (href.startsWith('#')) {
                    e.preventDefault();
                    document.querySelectorAll('.nav-item').forEach(nav => nav.classList.remove('active'));
                    this.classList.add('active');
                }
            });
        });
        
        // Load data on page load
        loadUserData();
    </script>
</body>