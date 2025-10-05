const menuBtn = document.getElementById('menuBtn');
const closeBtn = document.getElementById('closeBtn');
const sidebar = document.getElementById('sidebar');
const sidebarOverlay = document.getElementById('sidebarOverlay');
       
function openSidebar() {
sidebar.classList.remove('translate-x-full');
document.body.style.overflow = 'hidden';
}
        
function closeSidebar() {
sidebar.classList.add('translate-x-full');
document.body.style.overflow = '';
}
   
menuBtn.addEventListener('click', openSidebar);
closeBtn.addEventListener('click', closeSidebar);
sidebarOverlay.addEventListener('click', closeSidebar);
   
// Close sidebar on escape key

document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
        closeSidebar();
    }
});





/* ============================================================ */







// Toggle between login and reset password forms
        document.getElementById('forgotPasswordLink').addEventListener('click', function(e) {
            e.preventDefault();
            document.getElementById('loginSection').classList.add('hidden');
            document.getElementById('resetPasswordSection').classList.remove('hidden');
        });

        document.getElementById('backToLogin').addEventListener('click', function(e) {
            e.preventDefault();
            document.getElementById('resetPasswordSection').classList.add('hidden');
            document.getElementById('loginSection').classList.remove('hidden');
        });

 

        // Toggle forms
        document.getElementById('forgotPasswordLink').addEventListener('click', function(e) {
            e.preventDefault();
            document.getElementById('loginSection').classList.add('hidden');
            document.getElementById('resetPasswordSection').classList.remove('hidden');
        });

        document.getElementById('backToLogin').addEventListener('click', function(e) {
            e.preventDefault();
            document.getElementById('resetPasswordSection').classList.add('hidden');
            document.getElementById('loginSection').classList.remove('hidden');
        });

        // Reset Password Form Handler
        document.getElementById('resetPasswordForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const resetErrorMessage = document.getElementById('resetErrorMessage');
            const resetSuccessMessage = document.getElementById('resetSuccessMessage');
            resetErrorMessage.classList.add('hidden');
            resetSuccessMessage.classList.add('hidden');

            const formData = {
                email: document.getElementById('reset_email').value
            };

            try {
                const response = await fetch('api/reset_password.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(formData)
                });

                const data = await response.json();

                if (data.success) {
                    resetSuccessMessage.textContent = data.message;
                    resetSuccessMessage.classList.remove('hidden');
                    document.getElementById('reset_email').value = '';
                } else {
                    resetErrorMessage.textContent = data.message;
                    resetErrorMessage.classList.remove('hidden');
                }
            } catch (error) {
                resetErrorMessage.textContent = 'An error occurred. Please try again.';
                resetErrorMessage.classList.remove('hidden');
            }
        });

        // Reset Password Form Submission
        document.getElementById('resetPasswordForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const resetErrorMessage = document.getElementById('resetErrorMessage');
            const resetSuccessMessage = document.getElementById('resetSuccessMessage');
            resetErrorMessage.classList.add('hidden');
            resetSuccessMessage.classList.add('hidden');

            const formData = {
                email: document.getElementById('reset_email').value
            };

            try {
                const response = await fetch('api/reset_password.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(formData)
                });

                const data = await response.json();

                if (data.success) {
                    resetSuccessMessage.textContent = data.message;
                    resetSuccessMessage.classList.remove('hidden');
                    
                    // Clear form
                    document.getElementById('reset_email').value = '';
                } else {
                    resetErrorMessage.textContent = data.message;
                    resetErrorMessage.classList.remove('hidden');
                }
            } catch (error) {
                resetErrorMessage.textContent = 'An error occurred. Please try again.';
                resetErrorMessage.classList.remove('hidden');
            }
        });


 // Check if user is logged in
        window.addEventListener('DOMContentLoaded', function() {
            const user = JSON.parse(sessionStorage.getItem('user') || '{}');
            
            if (!user.user_id) {
                window.location.href = 'login.html';
                return;
            }

            // Display user info
            document.getElementById('userName').textContent = `${user.first_name} ${user.last_name}`;
            document.getElementById('userUsername').textContent = `@${user.username}`;

            // Load user's order history
            loadOrderHistory(user.user_id);
        });

        // Navigation active state
        document.querySelectorAll('.nav-item').forEach(item => {
            item.addEventListener('click', function(e) {
                e.preventDefault();
                document.querySelectorAll('.nav-item').forEach(nav => nav.classList.remove('active'));
                this.classList.add('active');
                
                // Handle navigation based on href
                const section = this.getAttribute('href').substring(1);
                handleNavigation(section);
            });
        });

        function handleNavigation(section) {
            // Add logic to show different content sections
            console.log('Navigate to:', section);
        }

        // Sign out
        document.getElementById('signOutBtn').addEventListener('click', function() {
            if (confirm('Are you sure you want to sign out?')) {
                sessionStorage.removeItem('user');
                window.location.href = 'login.html';
            }
        });

        // Load order history
        async function loadOrderHistory(userId) {
            try {
                const response = await fetch(`api/get_orders.php?user_id=${userId}`);
                const data = await response.json();
                
                if (data.success && data.orders.length > 0) {
                    // Display orders in the order history section
                    // Implementation depends on your API response structure
                }
            } catch (error) {
                console.error('Error loading orders:', error);
            }
        }







        window.addEventListener('DOMContentLoaded', function() {
        const user = JSON.parse(sessionStorage.getItem('user') || '{}');
        const loginBtn = document.querySelector('.logbtn');
        
        if (user.user_id && loginBtn) {
            // User is logged in, replace login button with user menu
            const parentDiv = loginBtn.parentElement;
            parentDiv.innerHTML = `
                <div class="flex items-center space-x-4">
                    <a href="${user.user_role === 'admin' ? 'admin-dashboard.html' : 'user-dashboard.html'}" 
                       class="text-stone-700 hover:text-amber-700 transition font-semibold">
                        ${user.first_name}
                    </a>
                    <button onclick="logout()" class="logbtn md:w-[80px]">
                        LOGOUT
                    </button>
                </div>
            `;
        }
    });
    
    function logout() {
        if (confirm('Are you sure you want to log out?')) {
            sessionStorage.removeItem('user');
            window.location.href = 'index.html';
        }
    }



    // Handle dashboard link based on user role
document.getElementById('dashboardLink').addEventListener('click', function(e) {
    e.preventDefault();
    const user = JSON.parse(sessionStorage.getItem('user') || '{}');
    
    if (!user.user_id) {
        // Not logged in, redirect to login
        window.location.href = 'login.html';
    } else if (user.user_role === 'admin') {
        // Admin user
        window.location.href = 'admin-dashboard.html';
    } else {
        // Regular user
        window.location.href = 'user-dashboard.html';
    }
});


 window.addEventListener('DOMContentLoaded', function() {
            const user = JSON.parse(sessionStorage.getItem('user') || '{}');
            
            if (!user.user_id || user.user_role !== 'admin') {
                alert('Access denied! Admin only.');
                window.location.href = 'login.html';
                return;
            }

            // Display admin info
            document.getElementById('adminName').textContent = `${user.first_name} ${user.last_name}`;
            document.getElementById('adminUsername').textContent = `@${user.username}`;

            // Load dashboard data
            loadDashboardData();
        });

        // Navigation
        document.querySelectorAll('.nav-item').forEach(item => {
            item.addEventListener('click', function(e) {
                const href = this.getAttribute('href');
                
                // If it's CMS.html, let it navigate normally
                if (href === 'CMS.html') {
                    return;
                }
                
                e.preventDefault();
                document.querySelectorAll('.nav-item').forEach(nav => nav.classList.remove('active'));
                this.classList.add('active');
                
                const section = href.substring(1);
                handleNavigation(section);
            });
        });

        function handleNavigation(section) {
            console.log('Navigate to:', section);
            // Add logic to show different sections
        }

        // Sign out
        document.getElementById('signOutBtn').addEventListener('click', function() {
            if (confirm('Are you sure you want to sign out?')) {
                sessionStorage.removeItem('user');
                window.location.href = 'login.html';
            }
        });

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
            
            // Sample data structure - adjust based on your API
            const labels = salesData.map(d => d.date) || ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
            const data = salesData.map(d => d.sales) || [1200, 1900, 3000, 5000, 2300, 3200, 4100];

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



        