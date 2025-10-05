<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
$isLoggedIn = isset($_SESSION['user_id']);
$userRole = $_SESSION['user_role'] ?? null;
$firstName = $_SESSION['first_name'] ?? '';
?>

<footer class="py-12 px-6 border-t border-gray-800 bg-gray-900">
        <div class="container mx-auto max-w-6xl flex-col">
            <div class="pb-[2rem]">
                <div class="text-2xl font-bold mb-4 text-white flex justify-center"><img src="src/assets/logowhite.png" width="200px" height="61px"></div>
                <p class="text-gray-400 flex justify-center">Building the future, one project at a time.</p>
            </div>
            <div class="grid md:grid-cols-5 text-white gap-8 mb-8">
                
                <div>
                    <a href="index.php"><h4 class="font-semibold mb-4 text-white">Home</h4></a>
                </div>
                <div>
                    <a href="product.php"><h4 class="font-semibold mb-4 text-white">Product</h4></a>
                </div>
                <div>
                    <a href="about.php"><h4 class="font-semibold mb-4 text-white">About</h4></a>
                </div>
                <div>
                    <a href="contact.php"><h4 class="font-semibold mb-4 text-white">Contact</h4></a>
                </div>
                <div>
                    <?php if ($isLoggedIn): ?>
                <a href="<?= $userRole === 'admin' ? 'admin-dashboard.php' : 'user-dashboard.php' ?>" >
                    <h4 class="font-semibold mb-4 text-white">Dashboard</h4></a>
                <?php endif;?>
          </div>
                </div>

            </div>
            <div class="pt-8 border-t border-gray-800 text-center text-gray-400">
                <p>&copy; 2025 BeanTage. All rights reserved.</p>
            </div>
        </div>
    </footer>