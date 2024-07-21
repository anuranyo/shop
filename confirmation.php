<?php
// confirmation.php
require_once 'php/db.php';
require_once 'php/orders.php';

session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$userId = $_SESSION['user_id'];
$orderId = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;

$orderClass = new Order();
$order = $orderClass->getOrderById($orderId);

if (!$order || $order['user_id'] !== $userId) {
    header("Location: index.php");
    exit;
}

$orderItems = $orderClass->getOrderByOrderId($orderId);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>
<body class="font-sans bg-cover bg-center bg-no-repeat bg-gradient-to-bl from-rose-100 to-teal-100">
    <?php include 'php/header.php'; ?>

    <main class="container mx-auto py-20 px-4">
        <h1 class="text-4xl md:text-5xl font-bold mb-8">Order Confirmation</h1>
        <div class="bg-yellow-200 rounded-lg p-4 mb-4">
            <h2 class="text-2xl font-bold mb-4">Order #<?= htmlspecialchars($orderId) ?></h2>
            <p>Order Date: <?= htmlspecialchars(date('F j, Y', strtotime($order['order_date']))) ?></p>
            <p>Status: <?= htmlspecialchars($order['status']) ?></p>
            <p>Total: $<?= htmlspecialchars($order['total_price']) ?></p>
            <h3 class="text-xl font-bold mt-4 mb-4">Order Items</h3>
            <ul>
                <?php foreach ($orderItems as $item): ?>
                    <li class="mb-4">
                        <div class="flex justify-between">
                            <div>
                                <h4 class="font-bold"><?= htmlspecialchars($item['name']) ?></h4>
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
        </div>
        <a href="index.php" class="bg-black text-white py-2 px-4 rounded-full">Continue Shopping</a>
    </main>

    <?php include 'php/footer.php'; ?>
</body>
</html>
