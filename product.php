<?php
// product.php
session_start();
require_once 'api/config.php';

// Check if user is logged in for cart functionality
$isLoggedIn = isset($_SESSION['user_id']);
$userId = $isLoggedIn ? $_SESSION['user_id'] : null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beantage - Products</title>
    <link rel="stylesheet" href="dist/output.css">
    <link rel="stylesheet" href="src/input.css">
</head>
<body class="bg-white text-white min-h-screen">
    
    <?php include 'navbar.php'; ?>

    <!-- Main Page -->
    <div id="mainPage" class="mt-20">

        <!-- Categories -->
        <div class="text-center my-12 px-8 pt-[2rem]">
            <h2 class="text-3xl font-bold mb-8 text-black">Categories</h2>
            <div class="flex gap-4 justify-center flex-wrap">
                <button class="category-btn bg-primary text-white px-8 py-3 rounded-full font-semibold hover:bg-primary hover:text-white transition transform hover:-translate-y-1" data-category="all">All Products</button>
                <button class="category-btn bg-white text-dark px-8 py-3 rounded-full font-semibold hover:bg-primary hover:text-white transition transform hover:-translate-y-1" data-category="Best Sellers">Best Sellers</button>
                <button class="category-btn bg-white text-dark px-8 py-3 rounded-full font-semibold hover:bg-primary hover:text-white transition transform hover:-translate-y-1" data-category="Drinks">Drinks</button>
                <button class="category-btn bg-white text-dark px-8 py-3 rounded-full font-semibold hover:bg-primary hover:text-white transition transform hover:-translate-y-1" data-category="Bread & Pastries">Bread & Pastries</button>
                <button class="category-btn bg-white text-dark px-8 py-3 rounded-full font-semibold hover:bg-primary hover:text-white transition transform hover:-translate-y-1" data-category="Merchandise">Merchandise</button>
            </div>
        </div>

        <!-- All Products -->
        <div class="max-w-7xl mx-auto px-8 mb-12 duration-300 ease-out" id="all-products">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-bold text-black">All Products</h3>
                <a href="#" class="view-more text-primary font-semibold hover:text-amber-600 transition" data-category="all">View more →</a>
            </div>
            <div class="bg-cream bg-opacity-90 p-8 rounded-lg grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6" id="all-products-grid">
                <div class="col-span-full text-center py-8">
                    <p class="text-stone-600">Loading products...</p>
                </div>
            </div>
        </div>

        <!-- Best Sellers -->
        <div class="max-w-7xl mx-auto px-8 mb-12 duration-300 ease-out" id="best-sellers">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-bold text-black">Best Sellers</h3>
                <a href="#" class="view-more text-primary font-semibold hover:text-amber-600 transition" data-category="Best Sellers">View more →</a>
            </div>
            <div class="bg-cream bg-opacity-90 p-8 rounded-lg grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6" id="bestsellers-grid">
                <div class="col-span-full text-center py-8">
                    <p class="text-stone-600">Loading products...</p>
                </div>
            </div>
        </div>

        <!-- Drinks -->
        <div class="max-w-7xl mx-auto px-8 mb-12 duration-300 ease-out" id="drinks">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-bold text-black">Drinks</h3>
                <a href="#" class="view-more text-primary font-semibold hover:text-amber-600 transition" data-category="Drinks">View more →</a>
            </div>
            <div class="bg-cream bg-opacity-90 p-8 rounded-lg grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6" id="drinks-grid">
                <div class="col-span-full text-center py-8">
                    <p class="text-stone-600">Loading products...</p>
                </div>
            </div>
        </div>

        <!-- Bread & Pastries -->
        <div class="max-w-7xl mx-auto px-8 mb-12 duration-300 ease-out" id="bread-pastries">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-bold text-black">Bread & Pastries</h3>
                <a href="#" class="view-more text-primary font-semibold hover:text-amber-600 transition" data-category="Bread & Pastries">View more →</a>
            </div>
            <div class="bg-cream bg-opacity-90 p-8 rounded-lg grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6" id="bread-grid">
                <div class="col-span-full text-center py-8">
                    <p class="text-stone-600">Loading products...</p>
                </div>
            </div>
        </div>

        <!-- Merchandise -->
        <div class="max-w-7xl mx-auto px-8 mb-12 duration-300 ease-out" id="merchandise">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-bold text-black">Merchandise</h3>
                <a href="#" class="view-more text-primary font-semibold hover:text-amber-600 transition" data-category="Merchandise">View more →</a>
            </div>
            <div class="bg-cream bg-opacity-90 p-8 rounded-lg grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6" id="merchandise-grid">
                <div class="col-span-full text-center py-8">
                    <p class="text-stone-600">Loading products...</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Product Detail Modal (hidden by default) -->
    <div id="productModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center p-4">
        <div class="bg-white rounded-lg max-w-2xl w-full p-8 relative max-h-[90vh] overflow-y-auto">
            <button onclick="closeProductModal()" class="absolute top-4 right-4 text-stone-600 hover:text-stone-800">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
            
            <div id="productModalContent">
                <!-- Content will be dynamically loaded -->
            </div>
        </div>
    </div>

    <!-- View More Modal -->
    <div id="viewMoreModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center p-4">
        <div class="bg-white rounded-lg max-w-6xl w-full p-8 relative max-h-[90vh] overflow-y-auto">
            <button onclick="closeViewMoreModal()" class="absolute top-4 right-4 text-stone-600 hover:text-stone-800">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
            
            <h2 id="viewMoreTitle" class="text-3xl font-bold text-stone-800 mb-6">All Products</h2>
            
            <div id="viewMoreContent" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                <!-- Content will be dynamically loaded -->
            </div>
        </div>
    </div>

    <!-- Footer -->
    <?php include 'footer.php';?>
    
    <script>
        // Pass PHP session data to JavaScript
        const isUserLoggedIn = <?php echo json_encode($isLoggedIn); ?>;
        const currentUserId = <?php echo json_encode($userId); ?>;
    </script>
    <script src="src/product.js"></script>
</body>
</html>