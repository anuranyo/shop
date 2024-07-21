<?php
// cart.php
require_once 'php/db.php';
require_once 'php/products.php';
require_once 'php/cart.php';

session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$userId = $_SESSION['user_id'];
$cartClass = new Cart();
$cartItems = $cartClass->getCartItemsByUserId($userId);

// Handle actions: select all, remove selected, add to wishlist, remove item, update quantity
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = isset($_POST['action']) ? $_POST['action'] : '';
    $productId = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;

    if ($action === 'remove_selected') {
        $cartClass->removeAllItems($userId);
    } elseif ($action === 'remove_item' && $productId > 0) {
        $cartClass->removeItem($userId, $productId);
    } elseif ($action === 'increase_quantity' && $productId > 0) {
        $cartClass->updateQuantity($userId, $productId, 1);
    } elseif ($action === 'decrease_quantity' && $productId > 0) {
        $cartClass->updateQuantity($userId, $productId, -1);
    }

    header("Location: cart.php");
    exit;
}

$totalPrice = $cartClass->getTotalPrice($userId);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>
<body class="font-sans bg-cover bg-center bg-no-repeat bg-gradient-to-bl from-rose-100 to-teal-100">
    <?php include 'php/header.php'; ?>

    <main class="container mx-auto py-20 px-4">
        <nav class="text-gray-600 mb-8">
            <a href="index.php" class="hover:text-gray-400">Home</a> &gt; <a href="cart.php" class="hover:text-gray-400">Cart</a>
        </nav>
        <h1 class="text-4xl md:text-5xl font-bold mb-8">Cart</h1>
        
        <form method="post" class="flex space-x-4 mb-4">
            <input type="hidden" name="action" value="remove_selected">
            <button type="submit" class="bg-gray-200 text-black py-2 px-4 rounded-md">Remove All</button>
        </form>
        
        <div class="flex flex-wrap justify-between mb-8">
            <div class="w-full md:w-2/4 mb-4">
                <?php foreach ($cartItems as $item): ?>
                    <div class="bg-yellow-200 rounded-lg p-4 mb-4 flex justify-between items-center">
                        <div class="flex items-center">
                            <img src="<?= htmlspecialchars($item['image_url']) ?>" alt="<?= htmlspecialchars($item['name']) ?>" class="mr-4 w-20 h-20 object-cover">
                            <div>
                                <h2 class="text-xl font-bold"><?= htmlspecialchars($item['name']) ?></h2>
                                <p>Article: <?= htmlspecialchars($item['product_id']) ?></p>
                                <p>Category: <a href="category.php?category=<?= htmlspecialchars($item['category_name']) ?>" class="text-blue-500 hover:underline"><?= htmlspecialchars($item['category_name']) ?></a></p>
                                <div class="flex items-center mt-2">
                                    <form method="post" class="mr-4">
                                        <input type="hidden" name="action" value="decrease_quantity">
                                        <input type="hidden" name="product_id" value="<?= htmlspecialchars($item['product_id']) ?>">
                                        <button type="submit" class="bg-gray-200 text-black py-2 px-4 rounded-md">-</button>
                                    </form>
                                    <span class="mx-2"><?= htmlspecialchars($item['quantity']) ?></span>
                                    <form method="post" class="mr-4">
                                        <input type="hidden" name="action" value="increase_quantity">
                                        <input type="hidden" name="product_id" value="<?= htmlspecialchars($item['product_id']) ?>">
                                        <button type="submit" class="bg-gray-200 text-black py-2 px-4 rounded-md">+</button>
                                    </form>
                                    <form method="post" class="mr-4">
                                        <input type="hidden" name="action" value="remove_item">
                                        <input type="hidden" name="product_id" value="<?= htmlspecialchars($item['product_id']) ?>">
                                        <button type="submit">🗑 Remove</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <p class="text-xl font-bold">$<?= htmlspecialchars($item['price']) ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="w-full md:w-1/3 bg-pink-200 rounded-lg p-4 ">
                <h2 class="text-2xl font-bold mb-4">Your Cart</h2>
                <p class="mb-4"><?= count($cartItems) ?> items</p>
                <p class="mb-4">Discount: $0</p>
                <p class="mb-4">Shipping: Free</p>
                <p class="text-3xl font-bold mb-4">Total: $<?= htmlspecialchars($totalPrice ?? 0) ?></p>
                <a href="checkout.php" class="bg-black text-white py-2 px-4 rounded-full w-full block text-center">Proceed to Checkout</a>
            </div>
        </div>
    </main>

    <?php include 'php/footer.php'; ?>
</body>
</html>
