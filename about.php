<?php
// Start session
session_start();

// Include database configuration
require_once 'api/config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Beantage</title>
    <link rel="stylesheet" href="dist/output.css">
    <link rel="stylesheet" href="src/input.css">
</head>
<body class="bg-stone-100">
    
    <?php include 'navbar.php'; ?>

    <!-- Page Header -->
    <section class="py-12 text-center">
        <h1 class="text-4xl font-bold text-stone-800 mb-2">About</h1>
    </section>

    <!-- Our Story Section -->
    <section class="container mx-auto px-4 mb-16 ">
        <div class="bg-stone-700 rounded-lg overflow-hidden shadow-xl">
            <div class="grid md:grid-cols-2 gap-0">
                <!-- Image Side -->
                <div class="h-96 md:h-auto bg-stone-600 flex items-center justify-center overflow-hidden">
                    <img src="src/assets/aboutBG.jpg" alt="Our Story" class="w-full h-full object-cover" onerror="this.parentElement.innerHTML='<div class=\'text-stone-400 text-center p-8\'>Coffee Story Image</div>'">
                </div>
                
                <!-- Content Side -->
                <div class="p-8 md:p-12 flex flex-col justify-center">
                    <h2 class="text-3xl font-bold text-white mb-6">Our Story</h2>
                    <div class="text-stone-200 space-y-4 leading-relaxed">
                        <p>At Bean There . Coffee, every cup tells a story and it begins with our passion for great coffee and the people who share it.</p>
                        <p>We carefully select our beans from trusted roasters, ensuring each brew is rich in flavor and crafted with love. From our freshly baked snacks to our signature drinks, everything is prepared with attention to detail - because we believe every visit should be worth remembering.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Mission and Vision Section -->
    <section class="container mx-auto px-4 mb-16">
        <div class="grid md:grid-cols-2 gap-8">
            
            <!-- Our Mission -->
            <div class="bg-white rounded-lg shadow-lg p-8 hover:-translate-y-2 transition duration-300">
                <div class="mb-6">
                    <div class="w-16 h-16 bg-amber-700 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <h2 class="text-3xl font-bold text-stone-800 mb-4">Our Mission</h2>
                </div>
                <p class="text-stone-700 leading-relaxed">
                    To serve premium coffee from the finest beans. Creating a welcoming environment where every cup brings people together with quality and passion.
                </p>
            </div>

            <!-- Our Vision -->
            <div class="bg-white rounded-lg shadow-lg p-8 hover:-translate-y-2 transition duration-300">
                <div class="mb-6">
                    <div class="w-16 h-16 bg-amber-700 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    </div>
                    <h2 class="text-3xl font-bold text-stone-800 mb-4">Our Vision</h2>
                </div>
                <p class="text-stone-700 leading-relaxed">
                    To be go-to destination for coffee lovers, offering an exceptional experience while promoting sustainability and community connection.
                </p>
            </div>

        </div>
    </section>

    <!-- Values Section (Optional Enhancement) -->
    <section class="container mx-auto px-4 mb-16">
        <h2 class="text-3xl font-bold text-center text-stone-800 mb-12">Our Values</h2>
        <div class="grid md:grid-cols-3 gap-8">
            
            <!-- Quality -->
            <div class="text-center p-6">
                <div class="w-20 h-20 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-stone-800 mb-3">Quality</h3>
                <p class="text-stone-600">We never compromise on the quality of our beans and ingredients.</p>
            </div>

            <!-- Community -->
            <div class="text-center p-6">
                <div class="w-20 h-20 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-stone-800 mb-3">Community</h3>
                <p class="text-stone-600">Building connections one cup at a time.</p>
            </div>

            <!-- Sustainability -->
            <div class="text-center p-6">
                <div class="w-20 h-20 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-stone-800 mb-3">Sustainability</h3>
                <p class="text-stone-600">Committed to eco-friendly practices and ethical sourcing.</p>
            </div>

        </div>
    </section>

    <!-- Call to Action -->
    <section class="bg-accent text-white py-16 mb-0">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-3xl font-bold mb-4">Ready to Experience Great Coffee?</h2>
            <p class="text-xl mb-8">Visit us today and taste the difference.</p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="product.php" class="bg-white text-amber-700 px-8 py-3 rounded-lg font-bold hover:bg-stone-100 transition transform hover:scale-105">
                    View Our Products
                </a>
                <a href="contact.php" class="bg-transparent border-2 border-white text-white px-8 py-3 rounded-lg font-bold hover:bg-white hover:text-amber-700 transition transform hover:scale-105">
                    Contact Us
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <?php include 'footer.php';?>
    <script src="src/index.js"></script>
</body>
</html>