<?php
// auth_check.php - Include this at the top of protected pages
session_start();

function isLoggedIn() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

function isAdmin() {
    return isLoggedIn() && $_SESSION['user_role'] === 'admin';
}

function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: login.php');
        exit;
    }
}

function requireAdmin() {
    if (!isAdmin()) {
        header('Location: login.php');
        exit;
    }
}

function getUserData() {
    if (!isLoggedIn()) {
        return null;
    }
    
    return [
        'user_id' => $_SESSION['user_id'],
        'username' => $_SESSION['username'],
        'first_name' => $_SESSION['first_name'],
        'last_name' => $_SESSION['last_name'],
        'email' => $_SESSION['email'],
        'user_role' => $_SESSION['user_role']
    ];
}
?>


<?php
// admin-dashboard.php
session_start();

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

$admin_data = [
    'username' => $_SESSION['username']
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Beantage</title>
    <link rel="stylesheet" href="dist/output.css">
    <link rel="stylesheet" href="src/input.css">
</head>
<body class="bg-stone-100">
    
    <?php include 'navbar.php'; ?>

    <div class="container mx-auto px-4 py-8 mt-20">
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-4xl font-bold text-stone-800">Admin Dashboard</h2>
            <a href="CMS.php" class="bg-amber-600 hover:bg-amber-700 text-white font-bold py-3 px-6 rounded-lg transition">
                Content Management
            </a>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <!-- Total Orders -->
            <div class="bg-accent rounded-lg p-6 shadow-lg fade-in">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-stone-600 font-semibold mb-2">Total Orders</p>
                        <h3 class="text-4xl font-bold text-stone-800" id="totalOrders">0</h3>
                        <p class="text-stone-600 text-sm mt-2"> All time orders</p>
                    </div>
                    <div class="text-6xl"></div>
                </div>
            </div>

            <!-- Total Sales -->
            <div class="bg-accent rounded-lg p-6 shadow-lg fade-in">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-stone-600 font-semibold mb-2">Total Sales</p>
                        <h3 class="text-4xl font-bold text-stone-800" id="totalSales">₱0.00</h3>
                        <p class="text-stone-600 text-sm mt-2"> Revenue generated</p>
                    </div>
                    <div class="text-6xl"></div>
                </div>
            </div>
        </div>

        <!-- Sales Analytics Chart -->
        <div class="bg-accent rounded-lg p-6 shadow-lg mb-8 fade-in">
            <h3 class="text-2xl font-bold text-stone-800 mb-6">Sales Analytics</h3>
            <div class="bg-white rounded-lg p-4">
                <canvas id="salesChart" height="80"></canvas>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Trending Products -->
            <div class="bg-accent rounded-lg p-6 shadow-lg fade-in">
                <h3 class="text-2xl font-bold text-stone-800 mb-6">Trending Products</h3>
                <div class="space-y-4" id="trendingProducts">
                    <div class="bg-stone-50 rounded-lg p-4 flex items-center justify-between">
                        <div>
                            <p class="font-semibold text-stone-800">Loading...</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Orders -->
            <div class="bg-accent rounded-lg p-6 shadow-lg fade-in">
                <h3 class="text-2xl font-bold text-stone-800 mb-6">Recent Orders</h3>
                <div class="space-y-3" id="recentOrders">
                    <div class="bg-stone-50 rounded-lg p-4">
                        <p class="text-stone-600 text-sm">Loading orders...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="src/index.js"></script>
    <script>
        // Load dashboard data
        async function loadDashboardData() {
            try {
                const response = await fetch('api/admin_dashboard.php');
                const data = await response.json();

                if (data.success) {
                    // Update stats
                    document.getElementById('totalOrders').textContent = data.total_orders || 0;
                    document.getElementById('totalSales').textContent = `₱${(data.total_sales || 0).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}`;

                    // Update trending products
                    displayTrendingProducts(data.trending_products || []);

                    // Update recent orders
                    displayRecentOrders(data.recent_orders || []);

                    // Create sales chart
                    createSalesChart(data.sales_data || []);
                }
            } catch (error) {
                console.error('Error loading dashboard:', error);
            }
        }

        function displayTrendingProducts(products) {
            const container = document.getElementById('trendingProducts');
            
            if (products.length === 0) {
                container.innerHTML = '<p class="text-stone-600 text-sm">No trending products yet</p>';
                return;
            }

            container.innerHTML = products.map((product, index) => `
                <div class="bg-stone-50 rounded-lg p-4 flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <span class="text-2xl font-bold text-amber-700">#${index + 1}</span>
                        <div>
                            <p class="font-semibold text-stone-800">${product.product_name}</p>
                            <p class="text-stone-600 text-sm">${product.total_sold} sold</p>
                        </div>
                    </div>
                    <span class="text-amber-700 font-bold">₱${parseFloat(product.revenue).toLocaleString('en-US', {minimumFractionDigits: 2})}</span>
                </div>
            `).join('');
        }

        function displayRecentOrders(orders) {
            const container = document.getElementById('recentOrders');
            
            if (orders.length === 0) {
                container.innerHTML = '<p class="text-stone-600 text-sm">No recent orders</p>';
                return;
            }

            container.innerHTML = orders.map(order => `
                <div class="bg-stone-50 rounded-lg p-4 flex items-center justify-between">
                    <div>
                        <p class="font-semibold text-stone-800">${order.order_number}</p>
                        <p class="text-stone-600 text-sm">${order.customer_name}</p>
                    </div>
                    <div class="text-right">
                        <p class="font-bold text-stone-800">₱${parseFloat(order.total_amount).toLocaleString('en-US', {minimumFractionDigits: 2})}</p>
                        <span class="text-xs px-2 py-1 rounded ${getStatusColor(order.status)}">${order.status}</span>
                    </div>
                </div>
            `).join('');
        }

        function getStatusColor(status) {
            const colors = {
                'pending': 'bg-yellow-200 text-yellow-800',
                'preparing': 'bg-blue-200 text-blue-800',
                'completed': 'bg-green-200 text-green-800',
                'cancelled': 'bg-red-200 text-red-800'
            };
            return colors[status] || 'bg-stone-200 text-stone-800';
        }

        function createSalesChart(salesData) {
            const ctx = document.getElementById('salesChart').getContext('2d');
            
            const labels = salesData.map(d => d.date) || ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
            const data = salesData.map(d => d.sales) || [0, 0, 0, 0, 0, 0, 0];

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Sales (₱)',
                        data: data,
                        borderColor: '#d97706',
                        backgroundColor: 'rgba(217, 119, 6, 0.1)',
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top'
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return '₱' + value.toLocaleString();
                                }
                            }
                        }
                    }
                }
            });
        }
        
        // Load data on page load
        loadDashboardData();
    </script>
</body>
</html>