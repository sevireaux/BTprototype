<?php
// product.php
session_start();
require_once 'api/config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Homepage</title>
  <link rel="stylesheet" href="src/input.css">
  <link rel="stylesheet" href="dist/output.css">
</head>
<body class="bg-gray-900 text-gray-100">
  <!-- Navbar -->
   <?php include 'navbar.php'; ?>
    <!-- Mobile Sidebar -->

    <!-- Hero Section -->
    <section class="pt-[2rem] pb-20 h-[80vh] xl:h-screen relative text-white">
        <div class=" absolute w-full h-full z-10 bg-primary/[0.85]">
            <div>
                <video autoplay loop muted class="absolute top-0 left-0 w-full h-full object-cover z-[-10]"><source src="src/assets/hero/video2.mp4"
                    type="video/mp4">
                </video>
                <img src="src/assets/hero/hero-overlay.png" alt="Overlay" 
                class="absolute top-0 left-0 w-full h-full object-cover pointer-events-none bg-primary/[0.85]" >
            </div>

            <div class="container mx-auto h-full flex flex-col xl:flex-row items-center z-30 relative">
                <div
                    data-scroll
                    data-scroll-speed="0.4 text-white"
                    class="flex-1 flex flex-col text-center justify-center items-center xl:pb-12 gap-10 h-full"
                    >
                    <div class="flex flex-col items-center" >
                        <h1 class="h1 text-white">
                        <span class="text-accent font-tertiary h2">More Than a Coffee, It's a Vintage</span>
                        </h1>
                    </div>
                    <p class="lead font-light max-w-[300px] md:max-w-[430px] xl:max-w-[560px] mb-4 text-white">
                    Experience the joy of exceptional coffee in our cozy space, where every cup is
                    crafted with passion and warmth
                    </p>
                    <a href="product.php">
                    <button class="btn">Product</button></a>
                </div>
    </section>


    <section class="py-12 xl:py-0 xl:h-screen xl:w-full bg-white align-middle">
        <div class="container mx-auto xl:w-full xl:h-full flex xl:justify-center xl:items-center">
            <div class="w-full flex flex-col gap-12 xl:gap-10 xl:flex-row">
                <!-- Left Column -->
                <div class="flex-1 flex flex-col justify-between items-end text-center xl:text-right gap- xl:gap-0 max-w-md mx-auto xl:max-w-none xl:mx-0">
                    <!-- Item 1 -->
                    <div class="relative flex items-start xl:items-end">
                        <div class="w-full flex flex-col items-center xl:items-end">
                            <div class="mb-6 flex justify-center items-center">
                                <img src="src/assets/icons/icon1.png" width="56" height="56" alt="Rich Espresso Blends" class="w-14 h-14">
                            </div>
                            <h3 class="text-4xl font-bold mb-4 text-accent">Rich Espresso Blends</h3>
                            <p class="max-w-sm text-black">Indulge in the deep, robust flavors of our expertly crafted espresso blends. Perfect for a quick pick-me-up or a leisurely afternoon treat.</p>
                        </div>
                    </div>
                    
                    <!-- Item 2 -->
                    <div class="relative flex items-start xl:items-end">
                        <div class="w-full flex flex-col items-center xl:items-end">
                            <div class="mb-6 flex justify-center items-center">
                                <img src="src/assets/icons/icon2.png" width="56" height="56" alt="Classic Drip Coffee" class="w-14 h-14">
                            </div>
                            <h3 class="text-4xl font-bold mb-4 text-accent">Classic Drip Coffee</h3>
                            <p class="max-w-sm text-black">Enjoy the comforting taste of our classic drip coffee, brewed to perfection. A timeless choice for coffee enthusiasts who appreciate simplicity.</p>
                        </div>
                    </div>
                </div>
                
                <!-- Center Image -->
                <div class="hidden xl:flex justify-center items-center">
                    <div class="relative w-80 h-full max-h-screen flex items-center">
                        <img src="src/assets/cup.png" alt="Coffee Cup" class="w-full h-auto object-contain">
                    </div>
                </div>
                
                <!-- Right Column -->
                <div class="flex-1 flex flex-col justify-between text-center xl:text-left gap-12 xl:gap-0 max-w-md mx-auto xl:max-w-none xl:mx-0">
                    <!-- Item 3 -->
                    <div class="relative flex items-start">
                        <div class="w-full flex flex-col items-center xl:items-start">
                            <div class="mb-6 flex justify-center items-center">
                                <img src="src/assets/icons/icon3.png" width="56" height="56" alt="Smooth Cold Brews" class="w-14 h-14">
                            </div>
                            <h3 class="text-4xl font-bold mb-4 text-accent">Smooth Cold Brews</h3>
                            <p class="max-w-sm text-black">Refresh yourself with our smooth and invigorating cold brew options. Ideal for those warm days when you need a cool, caffeinated boost.</p>
                        </div>
                    </div>
                    
                    <!-- Item 4 text-white -->
                    <div class="relative flex items-start">
                        <div class="w-full flex flex-col items-center xl:items-start">
                            <div class="mb-6 flex justify-center items-center">
                                <img src="src/assets/icons/icon4.png" width="56" height="56" alt="Flavorful Latte Varieties" class="w-14 h-14">
                            </div>
                            <h3 class="text-4xl font-bold mb-4 text-accent">Flavorful Latte Varieties</h3>
                            <p class="max-w-sm text-black">Experience the rich and creamy flavors of our diverse latte selections. From vanilla to caramel, we have a latte to suit every taste.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-20 px-6 bg-white">
        <div class="container mx-auto max-w-6xl">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-bold mb-4 text-black">How to Order</h2>
            </div>
            
            <div class="grid md:grid-cols-2 gap-8">
                <!-- Feature Card 1 -->
                <div class="bg-gray-800 rounded-xl p-8 card-hover">
                    <div class="w-14 h-14 bg-accent rounded-lg flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold mb-4 text-white">Get your code from the staff</h3>
                    <p class="text-gray-400">Ask the staff for a one-time code when you're inside the shop.</p>
								</div>
                <!-- Feature Card 2 -->
                <div class="bg-gray-800 rounded-xl p-8 card-hover">
                    <div class="w-14 h-14 bg-accent rounded-lg flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 text-white 0 00-8 0v4h8z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold mb-4 text-white">Choose you order</h3>
                    <p class="text-gray-400">Select your preferred size, flavor, add-ons that you exactly like</p>
                </div>
                
                <!-- Feature Card 3 -->
                <div class="bg-gray-800 rounded-xl p-8 card-hover">
                    <div class="w-14 h-14 bg-accent rounded-lg flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 21h8a4 4 0 004-4v-6H4v6a4 4 0 004 4zm12-10h1a2 2 0 010 4h-1v-4zM8 7V5a2 2 0 012-2h4a2 2 0 012 2v2H8z"></path>
														<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 3c0 1 .5 1.5 .5 2.5s-.5 1.5-.5 2.5M14 3c0 1 .5 1.5 .5 2.5s-.5 1.5-.5 2.5"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold mb-4 text-white">Place your order</h3>
                    <p class="text-gray-400">Select “On-Site” on order mode and enter the code, Orders are queued based on who ordered first.</p>
                </div>

								<div class="bg-gray-800 rounded-xl p-8 card-hover">
                    <div class="w-14 h-14 bg-accent rounded-lg flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
        d="M10 2c0 1 .5 1.5 .5 2.5s-.5 1.5-.5 2.5M14 2c0 1 .5 1.5 .5 2.5s-.5 1.5-.5 2.5M4 11v6a4 4 0 004 4h8a4 4 0 004-4v-6H4zm13 0h2a2 2 0 010 4h-2v-4zM8 7V5a2 2 0 012-2h4a2 2 0 012 2v2H8z" />
</svg>

                    </div>
                    <h3 class="text-2xl font-bold mb-4 text-white">Pick up and enjoy</h3>
                    <p class="text-gray-400">Wait for your order to be prepared and pick it up from the counter</p>
                </div>
            </div>
        </div>
    </section>
   
    <!-- Footer -->
    <?php include 'footer.php';?>
    
    <script src="src/index.js">
    </script>
</body>
</html>