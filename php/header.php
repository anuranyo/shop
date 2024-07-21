<?php
session_start();

//if admin
$isAdmin = false;
if (isset($_SESSION['user_id'])) {
    require_once 'php/users.php';
    $userClass = new User();
    $isAdmin = $userClass->isAdmin($_SESSION['user_id']);
}
?>

<header class="relative bg-black text-white p-4 flex justify-between items-center z-10">
    <div class="flex items-center space-x-2">
        <img src="https://placehold.co/40x40" alt="Logo" class="h-10 w-10">
        <span class="text-2xl font-bold"><a href="index.php">ablerrr</a></span>
    </div>
    <nav class="hidden md:flex space-x-6">
        <a href="catalog.php" class="hover:text-gray-400">Product Catalog</a>
        <a href="about.php" class="hover:text-gray-400">About Us</a>
        <a href="payment_methods.php" class="hover:text-gray-400">Payment Methods</a>
        <a href="contacts.php" class="hover:text-gray-400">Contacts</a>
    </nav>
    <div class="flex space-x-4 items-center">
        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="cart.php" class="text-2xl"><ion-icon name="cart-outline"></ion-icon></a>
            <?php if ($isAdmin): ?>
                <a href="admin.php" class="text-2xl"><ion-icon name="settings-outline"></ion-icon></a>
            <?php endif; ?>
            <a href="logout.php" class="text-2xl"><ion-icon name="log-out-outline"></ion-icon></a>
            <span class="flex items-center space-x-2">
                <span><a href="profile.php"><?php echo htmlspecialchars($_SESSION['username']); ?></a></span>
                <img src="https://placehold.co/40x40" alt="User Avatar" class="rounded-full h-10 w-10">
            </span>
        <?php else: ?>
            <a href="register.php" class="bg-white text-black py-2 px-4 rounded-md">Register</a>
            <a href="login.php" class="bg-gray-800 py-2 px-4 rounded-md">Login</a>
        <?php endif; ?>
    </div>
</header>

<!-- Include Ionicons -->
<script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
