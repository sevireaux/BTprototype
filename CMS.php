CMS.php

<?php
// CMS.php
session_start();

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

require_once 'api/config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coffee Shop CMS</title>
    <link rel="stylesheet" href="src/input.css">
    <link rel="stylesheet" href="dist/output.css">
</head>
<body class="bg-white text-gray-100">
    <!-- Sidebar -->
    <div class="flex h-screen">
        <aside class="w-64 bg-gray-800 border-r border-gray-700">
            <div class="p-6">
                <h1 class="text-2xl font-bold gradient-text">Coffee CMS</h1>
            </div>
            <nav class="mt-6">
                <button onclick="showTab('testimonials')" class="tab-btn w-full text-left px-6 py-3 hover:bg-gray-700 transition bg-gray-700">
                     Testimonials
                </button>
                <button onclick="showTab('products')" class="tab-btn w-full text-left px-6 py-3 hover:bg-gray-700 transition">
                     Products
                </button>
                <button onclick="showTab('orders')" class="tab-btn w-full text-left px-6 py-3 hover:bg-gray-700 transition">
                     Order Queue
                </button>
                <button onclick="showTab('history')" class="tab-btn w-full text-left px-6 py-3 hover:bg-gray-700 transition">
                     Order History
                </button>
            </nav>
            <div class="absolute bottom-4 left-4 right-4">
                <a href="admin-dashboard.php" class="block w-full text-center px-4 py-3 bg-amber-600 hover:bg-amber-700 text-gray-800 rounded-lg font-semibold transition">
                    ← Back to Dashboard
                </a>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 overflow-y-auto custom-scrollbar">
            <!-- Testimonials Section -->
            <div id="testimonials" class="tab-content p-8">
                <div class="flex justify-between items-center mb-8">
                    <h2 class="text-3xl font-bold text-gray-800">Testimonials Management</h2>
                    <button onclick="openTestimonialModal()" class="px-6 py-3 bg-gradient-to-r from-yellow-600 to-yellow-700 text-white rounded-lg font-semibold hover:shadow-lg transition">
                        + Add to Homepage
                    </button>
                </div>
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6" id="testimonialsGrid">
                    <div class="col-span-full text-center py-8">
                        <p class="text-gray-400">Loading testimonials...</p>
                    </div>
                </div>
            </div>

            <!-- Products Section -->
            <div id="products" class="tab-content hidden p-8">
                <div class="flex justify-between items-center mb-8">
                    <h2 class="text-3xl font-bold text-gray-800">Products Management</h2>
                    <button onclick="openProductModal()" class="px-6 py-3 bg-gradient-to-r from-yellow-600 to-yellow-700 text-white rounded-lg font-semibold hover:shadow-lg transition">
                        + Add Product
                    </button>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="productsGrid">
                    <div class="col-span-full text-center py-8">
                        <p class="text-gray-400">Loading products...</p>
                    </div>
                </div>
            </div>

            <!-- Orders Section -->
            <div id="orders" class="tab-content hidden p-8">
                <h2 class="text-3xl font-bold text-white mb-8">Order Queue</h2>
                
                <div class="space-y-4" id="ordersQueue">
                    <div class="text-center py-8">
                        <p class="text-gray-800">Loading orders...</p>
                    </div>
                </div>
            </div>

            <!-- History Section -->
            <div id="history" class="tab-content hidden p-8">
                <h2 class="text-3xl font-bold text-gray-800 mb-8">Order History</h2>
                
                <div class="bg-gray-800 rounded-xl overflow-hidden border border-gray-700">
                    <table class="w-full">
                        <thead class="bg-gray-700">
                            <tr>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-300">Order #</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-300">Customer</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-300">Total</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-300">Status</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-300">Date</th>
                            </tr>
                        </thead>
                        <tbody id="historyTable">
                            <tr>
                                <td colspan="5" class="text-center py-8 text-gray-400">Loading history...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <!-- Testimonial Modal -->
    <div id="testimonialModal" class="modal">
        <div class="bg-gray-800 rounded-xl p-8 max-w-2xl w-full mx-4 border border-gray-700">
            <h3 class="text-2xl font-bold text-white mb-6">Add Testimonial to Homepage</h3>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Select User Message</label>
                    <select id="userMessage" class="w-full px-4 py-3 bg-gray-700 text-white rounded-lg border border-gray-600 focus:border-yellow-600 focus:outline-none">
                        <option>Loading messages...</option>
                    </select>
                </div>
                <div class="flex gap-3 mt-6">
                    <button onclick="addTestimonial()" class="flex-1 px-6 py-3 bg-gradient-to-r from-yellow-600 to-yellow-700 text-white rounded-lg font-semibold hover:shadow-lg transition">
                        Add to Homepage
                    </button>
                    <button onclick="closeTestimonialModal()" class="px-6 py-3 bg-gray-700 text-white rounded-lg hover:bg-gray-600 transition">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Product Modal -->
    <div id="productModal" class="modal">
        <div class="bg-gray-800 rounded-xl p-8 max-w-2xl w-full mx-4 border border-gray-700 max-h-screen overflow-y-auto custom-scrollbar">
            <h3 class="text-2xl font-bold text-white mb-6">Add/Edit Product</h3>
            <form id="productForm" class="space-y-4">
                <input type="hidden" id="productId">
                <input type="hidden" id="currentPhotoUrl">
                
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Product Name</label>
                    <input type="text" id="productName" required class="w-full px-4 py-3 bg-gray-700 text-white rounded-lg border border-gray-600 focus:border-yellow-600 focus:outline-none" placeholder="e.g., Caramel Macchiato">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Price (₱)</label>
                    <input type="number" id="productPrice" step="0.01" required class="w-full px-4 py-3 bg-gray-700 text-white rounded-lg border border-gray-600 focus:border-yellow-600 focus:outline-none" placeholder="150.00">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Stock Quantity</label>
                    <input type="number" id="productStock" class="w-full px-4 py-3 bg-gray-700 text-white rounded-lg border border-gray-600 focus:border-yellow-600 focus:outline-none" placeholder="100">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Product Photo</label>
                    <input type="file" id="productPhoto" accept="image/*" class="w-full px-4 py-3 bg-gray-700 text-white rounded-lg border border-gray-600 focus:border-yellow-600 focus:outline-none">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Description</label>
                    <textarea id="productDesc" rows="3" class="w-full px-4 py-3 bg-gray-700 text-white rounded-lg border border-gray-600 focus:border-yellow-600 focus:outline-none" placeholder="Product description"></textarea>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Categories</label>
                    <div class="space-y-2">
                        <label class="flex items-center">
                            <input type="checkbox" name="categories" value="All Products" class="mr-2 w-5 h-5 text-yellow-600 bg-gray-700 border-gray-600 rounded focus:ring-yellow-600">
                            <span class="text-gray-300">All Products</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="categories" value="Best Sellers" class="mr-2 w-5 h-5 text-yellow-600 bg-gray-700 border-gray-600 rounded focus:ring-yellow-600">
                            <span class="text-gray-300">Best Sellers</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="categories" value="Drinks" class="mr-2 w-5 h-5 text-yellow-600 bg-gray-700 border-gray-600 rounded focus:ring-yellow-600">
                            <span class="text-gray-300">Drinks</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="categories" value="Bread & Pastries" class="mr-2 w-5 h-5 text-yellow-600 bg-gray-700 border-gray-600 rounded focus:ring-yellow-600">
                            <span class="text-gray-300">Bread & Pastries</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="categories" value="Merchandise" class="mr-2 w-5 h-5 text-yellow-600 bg-gray-700 border-gray-600 rounded focus:ring-yellow-600">
                            <span class="text-gray-300">Merchandise</span>
                        </label>
                    </div>
                </div>
                
                <div class="flex gap-3 mt-6">
                    <button type="button" onclick="saveProduct()" class="flex-1 px-6 py-3 bg-gradient-to-r from-yellow-600 to-yellow-700 text-white rounded-lg font-semibold hover:shadow-lg transition">
                        Save Product
                    </button>
                    <button type="button" onclick="closeProductModal()" class="px-6 py-3 bg-gray-700 text-white rounded-lg hover:bg-gray-600 transition">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <script src="src/CMS.js"></script>
</body>
</html>

