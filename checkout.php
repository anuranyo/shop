<?php
// checkout.php
require_once 'php/db.php';
require_once 'php/orders.php';
require_once 'php/cart.php';
require_once 'php/products.php';

session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$userId = $_SESSION['user_id'];
$cartClass = new Cart();
$orderClass = new Order();
$cartItems = $cartClass->getCartItemsByUserId($userId);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Insert order into the Orders table
    $orderDate = date('Y-m-d H:i:s');
    $status = 'Pending';

    $products = [];
    $totalPrice = 0;
    foreach ($cartItems as $item) {
        $total = $item['price'] * $item['quantity'];
        $products[] = [
            'product_id' => $item['product_id'],
            'quantity' => $item['quantity'],
            'total' => $total
        ];
        $totalPrice += $total;
    }

    $order_id = $orderClass->addOrder($userId, $orderDate, $status, $products);

    // Clear the cart
    $cartClass->removeAllItems($userId);

    // Redirect to a confirmation page
    header("Location: confirmation.php?order_id=$order_id");
    exit;
} else {
    $totalPrice = 0;
    foreach ($cartItems as $item) {
        $totalPrice += $item['price'] * $item['quantity'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>
<body class="font-sans bg-cover bg-center bg-no-repeat bg-gradient-to-bl from-rose-100 to-teal-100">
    <?php include 'php/header.php'; ?>

    <main class="container mx-auto py-20 px-4">
        <h1 class="text-4xl md:text-5xl font-bold mb-8">Checkout</h1>
        <form action="checkout.php" method="post">
            <div class="bg-yellow-200 rounded-lg p-4 mb-4">
                <h2 class="text-2xl font-bold mb-4">Order Summary</h2>
                <ul>
                    <?php foreach ($cartItems as $item): ?>
                        <li class="mb-4">
                            <div class="flex justify-between">
                                <div>
                                    <h3 class="font-bold"><?= htmlspecialchars($item['name']) ?></h3>
                                    <p>Quantity: <?= htmlspecialchars($item['quantity']) ?></p>
                                    <p>Price: $<?= htmlspecialchars($item['price']) ?></p>
                                </div>
                                <div>
                                    <p>Total: $<?= htmlspecialchars($item['quantity'] * $item['price']) ?></p>
                                </div>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <p class="text-3xl font-bold">Total: $<?= htmlspecialchars($totalPrice) ?></p>
            </div>
            <button type="submit" class="bg-black text-white py-2 px-4 rounded-full w-full">Confirm and Pay</button>
        </form>
    </main>

    <?php include 'php/footer.php'; ?>
</body>
</html>
