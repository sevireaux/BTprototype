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
    <title>Contact Us - Beantage</title>
    <link rel="stylesheet" href="dist/output.css">
    <link rel="stylesheet" href="src/input.css">
</head>
<body class="bg-stone-100">
    
    <?php include 'navbar.php'; ?>

    

    <!-- Contact Form Section -->
    <section class="container mx-auto px-4 mb-16">
        <div class="max-w-3xl mx-auto">
            <div class="text-center mb-8">
                <h2 class="text-3xl font-bold text-stone-800 mb-2">Get in Touch</h2>
                <p class="text-stone-600">Have questions? We'd love to hear from you.</p>
            </div>

            <div class="bg-white rounded-lg shadow-xl p-8">
                <form id="contactForm" class="space-y-6">
                    
                    <!-- Name Fields -->
                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label for="first_name" class="block text-stone-700 font-semibold mb-2">
                                First Name <span class="text-red-600">*</span>
                            </label>
                            <input type="text" id="first_name" name="first_name" required
                                class="w-full px-4 py-3 rounded-lg border-2 border-stone-300 focus:border-amber-600 focus:outline-none">
                        </div>
                        <div>
                            <label for="last_name" class="block text-stone-700 font-semibold mb-2">
                                Last Name <span class="text-red-600">*</span>
                            </label>
                            <input type="text" id="last_name" name="last_name" required
                                class="w-full px-4 py-3 rounded-lg border-2 border-stone-300 focus:border-amber-600 focus:outline-none">
                        </div>
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-stone-700 font-semibold mb-2">
                            Email <span class="text-red-600">*</span>
                        </label>
                        <input type="email" id="email" name="email" required
                            class="w-full px-4 py-3 rounded-lg border-2 border-stone-300 focus:border-amber-600 focus:outline-none">
                    </div>

                    <!-- Contact Number (Optional) -->
                    <div>
                        <label for="contact_number" class="block text-stone-700 font-semibold mb-2">
                            Contact Number <span class="text-stone-500 text-sm">(Optional)</span>
                        </label>
                        <input type="tel" id="contact_number" name="contact_number"
                            class="w-full px-4 py-3 rounded-lg border-2 border-stone-300 focus:border-amber-600 focus:outline-none">
                    </div>

                    <!-- Subject -->
                    <div>
                        <label for="subject" class="block text-stone-700 font-semibold mb-2">
                            Subject <span class="text-stone-500 text-sm">(Optional)</span>
                        </label>
                        <input type="text" id="subject" name="subject"
                            class="w-full px-4 py-3 rounded-lg border-2 border-stone-300 focus:border-amber-600 focus:outline-none">
                    </div>

                    <!-- Message -->
                    <div>
                        <label for="message" class="block text-stone-700 font-semibold mb-2">
                            Message <span class="text-red-600">*</span>
                        </label>
                        <textarea id="message" name="message" rows="5" required
                            class="w-full px-4 py-3 rounded-lg border-2 border-stone-300 focus:border-amber-600 focus:outline-none"></textarea>
                    </div>

                    <!-- Error Message -->
                    <div id="errorMessage" class="hidden bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded"></div>

                    <!-- Success Message -->
                    <div id="successMessage" class="hidden bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded"></div>

                    <!-- Submit Button -->
                    <button type="submit" 
                        class="w-full bg-amber-600 hover:bg-amber-700 text-white font-bold py-3 px-6 rounded-lg transition duration-300 transform hover:scale-105">
                        Send Message
                    </button>

                </form>
            </div>
        </div>
    </section>

    <!-- Contact Information -->


    <!-- Footer -->
    <?php include 'footer.php';?>
    <script src="src/contact.js"></script>
</body>
</html>