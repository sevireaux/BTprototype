<?php
// Start session
session_start();

// If user is already logged in, redirect to dashboard
if (isset($_SESSION['user_id'])) {
    if ($_SESSION['user_role'] === 'admin') {
        header('Location: admin-dashboard.php');
    } else {
        header('Location: user-dashboard.php');
    }
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Beantage</title>
    <link rel="stylesheet" href="dist/output.css">
    <link rel="stylesheet" href="src/input.css">
</head>
<body class="bg-stone-100 min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md fade-in">
        <!-- Logo/Header -->
        <div class="text-center mb-8">
            <h1 class="text-4xl font-bold text-stone-800 mb-2">Beantage</h1>
            <p class="text-stone-600">Create your account</p>
        </div>

        <!-- Registration Form -->
        <div class="bg-gray-800 rounded-lg shadow-lg p-8">
            <form id="registerForm" class="space-y-4">
                <!-- Username -->
                <div>
                    <label for="username" class="block text-white font-semibold mb-2">Username</label>
                    <input type="text" id="username" name="username" required
                        class="input-focus w-full px-4 py-3 rounded-lg border-2 border-stone-300 focus:border-amber-600 focus:outline-none bg-white">
                </div>

                <!-- First Name -->
                <div>
                    <label for="first_name" class="block text-white font-semibold mb-2">First Name</label>
                    <input type="text" id="first_name" name="first_name" required
                        class="input-focus w-full px-4 py-3 rounded-lg border-2 border-stone-300 focus:border-amber-600 focus:outline-none bg-white">
                </div>

                <!-- Last Name -->
                <div>
                    <label for="last_name" class="block text-white font-semibold mb-2">Last Name</label>
                    <input type="text" id="last_name" name="last_name" required
                        class="input-focus w-full px-4 py-3 rounded-lg border-2 border-stone-300 focus:border-amber-600 focus:outline-none bg-white">
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-white font-semibold mb-2">Email</label>
                    <input type="email" id="email" name="email" required
                        class="input-focus w-full px-4 py-3 rounded-lg border-2 border-stone-300 focus:border-amber-600 focus:outline-none bg-white">
                </div>

                <!-- Contact Number -->
                <div>
                    <label for="contact_number" class="block text-white font-semibold mb-2">Contact Number</label>
                    <input type="tel" id="contact_number" name="contact_number" required
                        class="input-focus w-full px-4 py-3 rounded-lg border-2 border-stone-300 focus:border-amber-600 focus:outline-none bg-white">
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-white font-semibold mb-2">Password</label>
                    <input type="password" id="password" name="password" required minlength="6"
                        class="input-focus w-full px-4 py-3 rounded-lg border-2 border-stone-300 focus:border-amber-600 focus:outline-none bg-white">
                    <p class="text-stone-300 text-sm mt-1">Minimum 6 characters</p>
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="confirm_password" class="block text-white font-semibold mb-2">Confirm Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" required
                        class="input-focus w-full px-4 py-3 rounded-lg border-2 border-stone-300 focus:border-amber-600 focus:outline-none bg-white">
                </div>

                <!-- Error Message -->
                <div id="errorMessage" class="hidden bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded"></div>

                <!-- Success Message -->
                <div id="successMessage" class="hidden bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded"></div>

                <!-- Register Button -->
                <button type="submit" 
                    class="w-full bg-amber-600 hover:bg-amber-700 text-white font-bold py-3 px-4 rounded-lg transition duration-300 transform hover:scale-105">
                    Register
                </button>
            </form>

            <!-- Login Link -->
            <div class="mt-6 text-center">
                <p class="text-white">
                    Already have an account? 
                    <a href="login.php" class="text-amber-500 hover:text-amber-600 font-semibold underline transition duration-300">
                        Login here
                    </a>
                </p>
            </div>
        </div>
    </div>

    <script src="src/register.js"></script>
</body>
</html>