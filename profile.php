<?php
// profile.php
require_once 'php/db.php';
require_once 'php/orders.php';
require_once 'php/users.php';

session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$userId = $_SESSION['user_id'];
$userClass = new User();
$userInfo = $userClass->getUserById($userId);
$orderClass = new Order();
$orders = $orderClass->getOrdersByUserId($userId);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>
<body class="font-sans bg-cover bg-center bg-no-repeat bg-gradient-to-bl from-rose-100 to-teal-100">
    <?php include 'php/header.php'; ?>

    <main class="container mx-auto py-20 px-4">
        <div class="flex items-center mb-8">
            <img src="images/profile.png" alt="Profile Picture" class="rounded-full mr-4 w-40">
            <div>
                <h1 class="text-xl font-bold"><?= htmlspecialchars($userInfo['name']) ?></h1>
                <p class="text-5xl font-semibold m-4"><?= htmlspecialchars($userInfo['username']) ?></p>
                <p class="text-lg"><?= htmlspecialchars($userInfo['email']) ?></p>
            </div>
        </div>

        <div class="flex justify-between items-center mb-8">
            <a href="#orders" class="bg-gray-200 text-black py-2 px-4 rounded-md">Orders</a>
            <a href="#purchased-items" class="bg-gray-200 text-black py-2 px-4 rounded-md">Purchased Items</a>
        </div>

        <section id="orders" class="mb-20">
            <h2 class="text-3xl font-bold mb-8">Orders</h2>
            <?php foreach ($orders as $orderId => $orderItems): ?>
                <div class="mb-8 mt-12 border-2 border-black rounded-lg p-8">
                    <h2 class="text-2xl font-bold">Order № <?= htmlspecialchars($orderId) ?></h2>
                    <p>Received on <?= htmlspecialchars(date('F j, Y', strtotime($orderItems[0]['order_date']))) ?></p>
                    <div class="bg-yellow-200 rounded-lg p-4 mb-4">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="font-bold">Total: $<?= htmlspecialchars(array_sum(array_column($orderItems, 'total'))) ?></p>
                            </div>
                            <button class="bg-black text-white py-2 px-4 rounded-full">Electronic Check</button>
                        </div>
                    </div>
                    <?php foreach ($orderItems as $item): ?>
                        <div class="flex justify-between items-center mb-4 border-t-2 border-b-2 border-black h-28">
                            <img class="w-40 h-28 object-contain" src="<?= htmlspecialchars($item['image_url']) ?>" alt="Product Image">
                            <div class="w-60">
                                <p class="font-bold"><?= htmlspecialchars($item['product_name']) ?></p>
                                <p>Article: <?= htmlspecialchars($item['product_id']) ?></p>
                                <p>Status: <?= htmlspecialchars($item['status']) ?></p>
                                <p class="mt-2"><?= htmlspecialchars($item['quantity']) ?> unit(s)</p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>
        </section>

        <section id="purchased-items" class="mb-20">
            <h2 class="text-3xl font-bold mb-8">Purchased Items</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-3 gap-4">
                <?php foreach ($orders as $orderItems): ?>
                    <?php foreach ($orderItems as $item): ?>
                        <div class="border-2 border-black rounded-lg p-4 flex flex-col items-center">
                            <div class="h-80 flex items-center"><img src="<?= htmlspecialchars($item['image_url'])?>" alt="<?= htmlspecialchars($item['product_name']) ?>" class="w-60 object-cover"></div>
                            <p class="mt-2 font-bold"><?= htmlspecialchars($item['product_name']) ?></p>
                            <p class="mt-2">$<?= htmlspecialchars($item['total'] / $item['quantity']) ?></p>
                            <p class="mt-2"><?= htmlspecialchars($item['quantity']) ?> unit(s)</p>
                        </div>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            </div>
        </section>
    </main>

    <?php include 'php/footer.php'; ?>
</body>
</html>
