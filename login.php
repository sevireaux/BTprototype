<?php
// login.php
session_start();

// If already logged in, redirect to dashboard
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
    <title>Login - Beantage</title>
    <link rel="stylesheet" href="dist/output.css">
    <link rel="stylesheet" href="src/input.css">
</head>
<body class="bg-stone-100 min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md fade-in">
        <!-- Logo/Header -->
        <div class="text-center mb-8">
            <h1 class="text-4xl font-bold text-stone-800 mb-2">Beantage</h1>
            <p class="text-stone-600">Welcome back!</p>
        </div>

        <!-- Login Form -->
        <div class="bg-gray-800 rounded-lg shadow-lg p-8" id="loginSection">
            <form id="loginForm" class="space-y-4">
                <!-- Username or Email -->
                <div>
                    <label for="username" class="block text-white font-semibold mb-2">Username or Email</label>
                    <input type="text" id="username" name="username" required
                        class="input-focus w-full px-4 py-3 rounded-lg border-2 border-stone-300 focus:border-amber-600 focus:outline-none bg-white">
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-white font-semibold mb-2">Password</label>
                    <input type="password" id="password" name="password" required
                        class="input-focus w-full px-4 py-3 rounded-lg border-2 border-stone-300 focus:border-amber-600 focus:outline-none bg-white">
                </div>

                <!-- Forgot Password Link -->
                <div class="text-right">
                    <a href="#" id="forgotPasswordLink" class="text-amber-800 hover:text-amber-900 text-sm font-semibold underline transition duration-300">
                        Forgot Password?
                    </a>
                </div>

                <!-- Error Message -->
                <div id="errorMessage" class="hidden bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded"></div>

                <!-- Success Message -->
                <div id="successMessage" class="hidden bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded"></div>

                <!-- Login Button -->
                <button type="submit" 
                    class="w-full bg-amber-600 hover:bg-amber-700 text-white font-bold py-3 px-4 rounded-lg transition duration-300 transform hover:scale-105">
                    Login
                </button>
            </form>

            <!-- Register Link -->
            <div class="mt-6 text-center">
                <p class="text-accent">
                    Don't have an account? 
                    <a href="register.php" class="text-amber-800 hover:text-amber-900 font-semibold underline transition duration-300">
                        Register here
                    </a>
                </p>
            </div>
        </div>

        <!-- Reset Password Form (Hidden by default) -->
        <div class="bg-amber-200 rounded-lg shadow-lg p-8 hidden" id="resetPasswordSection">
            <button id="backToLogin" class="mb-4 text-amber-800 hover:text-amber-900 font-semibold flex items-center transition duration-300">
                ← Back to Login
            </button>
            
            <h2 class="text-2xl font-bold text-stone-800 mb-4">Reset Password</h2>
            
            <form id="resetPasswordForm" class="space-y-4">
                <!-- Email for Reset -->
                <div>
                    <label for="reset_email" class="block text-stone-800 font-semibold mb-2">Email Address</label>
                    <input type="email" id="reset_email" name="reset_email" required
                        class="input-focus w-full px-4 py-3 rounded-lg border-2 border-stone-300 focus:border-amber-600 focus:outline-none bg-white">
                    <p class="text-sm text-stone-600 mt-2">We'll send you instructions to reset your password.</p>
                </div>

                <!-- Error Message -->
                <div id="resetErrorMessage" class="hidden bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded"></div>

                <!-- Success Message -->
                <div id="resetSuccessMessage" class="hidden bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded"></div>

                <!-- Submit Button -->
                <button type="submit" 
                    class="w-full bg-amber-600 hover:bg-amber-700 text-white font-bold py-3 px-4 rounded-lg transition duration-300 transform hover:scale-105">
                    Send Reset Link
                </button>
            </form>
        </div>
    </div>
    <script src="src/login.js"></script>
    <script src="src/index.js"></script>
</body>