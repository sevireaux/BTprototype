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

<nav class="fixed top-0 left-0 right-0 bg-white navbar-glow z-50 shadow-[0_2px_40px_#fff]">
    <div class="container mx-auto px-6 py-4 text-white">
        <div class="flex items-center justify-between">
          <!-- Logo -->
          <div class="text-2xl font-bold text-gray-900">
            <img src="src/assets/logo3.png" class="w-[160px] h-[56px] xl:w-[200px] xl:h-[72px] z-[60]">
          </div>
                
                <!-- Desktop Menu -->
          <div class="hidden md:flex items-center space-x-8">
            <a href="index.php" class="app__nav">Home</a>
            <a href="product.php" class="app__nav">Products</a>
            <a href="about.php" class="app__nav">About</a>
            <a href="contact.php" class="app__nav">Contact</a>
            <?php if ($isLoggedIn): ?>
                <a href="<?= $userRole === 'admin' ? 'admin-dashboard.php' : 'user-dashboard.php' ?>" class="app__nav">Dashboard</a>
          </div>
                
                <!-- CTA Button Desktop -->
          <div class=" justify-between w-[10%] hidden md:flex" >
            <a href="logout.php"><button class="logbtn md:p[80px] hidden md:block">LOGOUT</button></a>
            <?php else: ?>
            <a href="login.php"><button class="logbtn md:w-[80px] hidden md:block">
              LOGIN
            </button></a>
            <?php endif;?>
            <a href="cart.php">
            <img src="src/assets/cart.svg" width="24" height="24"></a>
          </div>
                
            <!-- Mobile Menu Button -->
            <button id="menuBtn" class="md:hidden text-gray-900 focus:outline-none">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
              </svg>
            </button>
          </div>
        </div>
    </nav>
    
    <!-- Mobile Sidebar -->
    <div id="sidebar" class="fixed inset-0 z-[100] transform translate-x-full transition-transform duration-300 ease-in-out">
        <div class="sidebar-overlay absolute inset-0 bg-black bg-opacity-50" id="sidebarOverlay"></div>
        <div class="absolute right-0 top-0 bottom-0 w-64 bg-white shadow-2xl">
            <div class="p-6">
                <div class="flex justify-between items-center mb-8">
                    <div class="text-xl font-bold text-white"></div>
                        <button id="closeBtn" class="text-black hover:text-gray-400 focus:outline-none">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                <div class="flex flex-col space-y-4 text-white">
                    <a href="index.php" class="app__nav">Home</a>
                    <a href="product.php" class="app__nav">Product</a>
                    <a href="about.php" class="app__nav">About</a>
                    <a href="contact.php" class="app__nav">Contact</a>
                    <?php if ($isLoggedIn): ?>
                <a href="<?= $userRole === 'admin' ? 'admin-dashboard.php' : 'user-dashboard.php' ?>" class="app__nav">Dashboard</a>
                    <img src="src/assets/cart.svg" width="24" height="24">
                    <a href="logout.php"><button class="logbtn mt-12">LOGOUT</button></a>
                    <?php else: ?>
                    <a href="login.php"><button class="logbtn mt-12">
                        LOGIN
                    </button></a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <script src="src//navbar.js"></script>