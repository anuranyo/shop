<?php
// product.php
require_once 'php/db.php';
require_once 'php/products.php';

session_start();

// Query parameter for product id
$product_id = isset($_GET['product_id']) ? (int)$_GET['product_id'] : 0;

$productClass = new Product();
$product = $productClass->getProductById($product_id);
$reviews = $productClass->getProductReviews($product_id);
$image1 = $productClass->getProductImage1($product_id);
$image2 = $productClass->getProductImage2($product['name']);
$category = $productClass->getCategoryById($product['category_id']);

if (!$product) {
    echo "Product not found.";
    exit;
}

// Insert or update viewed product into database
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $db = new Database();
    
    // Check if the product is already viewed by the user
    $existingView = $db->query('SELECT * FROM ViewedProducts WHERE user_id = :user_id AND product_id = :product_id', [
        'user_id' => $user_id,
        'product_id' => $product_id
    ])->fetch(PDO::FETCH_ASSOC);

    $currentDate = date("Y-m-d h:i:sa");

    if ($existingView) {
        // Update the viewed_at timestamp if already exists
        $sql = 'UPDATE ViewedProducts SET viewed_at = :viewed_at WHERE user_id = :user_id AND product_id = :product_id';
    } else {
        // Insert a new record if not exists
        $sql = 'INSERT INTO ViewedProducts (user_id, product_id, viewed_at) VALUES (:user_id, :product_id, :viewed_at)';
    }
    $params = [
        'user_id' => $user_id,
        'product_id' => $product_id,
        'viewed_at' => $currentDate
    ];
    $db->query($sql, $params);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $comment = isset($_POST['comment']) ? trim($_POST['comment']) : '';
    $rating = isset($_POST['rating']) ? (float)$_POST['rating'] : 0;
    $user_id = $_SESSION['user_id']; // Assuming user ID is stored in session after login

    // Validate input
    if ($comment && $rating >= 1 && $rating <= 5) {
        $db = new Database();
        $sql = 'INSERT INTO Reviews (product_id, user_id, comment, rating) VALUES (:product_id, :user_id, :comment, :rating)';
        $params = [
            'product_id' => $product_id,
            'user_id' => $user_id,
            'comment' => $comment,
            'rating' => $rating,
        ];
        if ($db->query($sql, $params)) {
            // Refresh page
            header("Location: product.php?product_id=" . $product_id);
            exit;
        } else {
            echo "Failed to submit review.";
        }
    } else {
        echo "Invalid input.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($product['name']) ?> - Product Details</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>
<body class="font-sans bg-cover bg-center bg-no-repeat" style="background: linear-gradient(135deg, #D7E1EC , #FFFFFF);">
    <?php include 'php/header.php'; ?>

    <main class="container mx-auto py-20 px-4">
        <nav class="text-gray-600 mb-8">
            <a href="index.php" class="hover:text-gray-400">Home</a> &gt; <a href="catalog.php" class="hover:text-gray-400">Product Catalog</a> &gt; <a href="category.php?category=<?= htmlspecialchars($category['name']) ?>" class="hover:text-gray-400"><?= htmlspecialchars($category['name']) ?></a> &gt; <a href="#" class="hover:text-gray-400"><?= htmlspecialchars($product['name']) ?></a>
        </nav>
        <h1 class="text-4xl md:text-5xl font-bold mb-8"><?= htmlspecialchars($product['name']) ?></h1>

        <div class="flex flex-wrap justify-between mb-8">
            <div class="w-full md:w-1/2 mb-4" style="height: 400px;">
                <img src="<?= htmlspecialchars($image1) ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="mb-4 h-full object-cover" id="main-image">
                <div class="flex space-x-2 mt-4">
                    <img src="<?= htmlspecialchars($image1) ?>" alt="Product Image 1" class="w-20 h-20 cursor-pointer thumbnail">
                    <?php if ($image2 != null): ?>
                        <img src="<?= htmlspecialchars($image2) ?>" alt="Product Image 2" class="w-20 h-20 cursor-pointer thumbnail">
                    <?php endif; ?>
                </div>
            </div>
            <div class="w-full md:w-1/2 border-2 border-black rounded-lg p-4">
                <h2 class="text-2xl font-bold mb-4">Specifications</h2>
                <ul class="mb-4">
                    <li>Description: <?= htmlspecialchars($product['description']) ?></li>
                    <li>Price: $<?= htmlspecialchars($product['price']) ?></li>
                    <li>Rating: <?= htmlspecialchars($product['average_rating']) ?></li>
                </ul>
                <h2 class="text-2xl font-bold mb-4">Availability</h2>
                <p class="mb-4">In Stock: <?= htmlspecialchars($product['available_units']) ?> units</p>
                <p class="mb-4"><?= htmlspecialchars($product['available_units']) > 0 ? 'Available for order' : 'Not available' ?></p>
                <h2 class="text-2xl font-bold mb-4">Total Price</h2>
                <div class="flex items-center space-x-4 mb-4">
                    <button class="bg-gray-200 text-black py-2 px-4 rounded-md decrease-quantity">-</button>
                    <input type="number" class="quantity w-12 text-center" value="1" min="1" max="<?= htmlspecialchars($product['available_units']) ?>" />
                    <button class="bg-gray-200 text-black py-2 px-4 rounded-md increase-quantity">+</button>
                </div>
                <p class="text-3xl font-bold mb-4">$<?= htmlspecialchars($product['price']) ?></p>
                <button class="bg-black text-white py-2 px-4 rounded-full">Add to cart</button>
            </div>
        </div>

        <section class="mb-20 mt-20">
            <?php if (isset($_SESSION['user_id'])): ?>
                <form action="" method="post" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
                    <input type="hidden" name="product_id" value="<?= htmlspecialchars($product_id) ?>">
                    <div class="mb-4">
                        <label for="rating" class="block text-gray-700 text-sm font-bold mb-2">Rating</label>
                        <input type="number" step="0.1" min="1" max="5" name="rating" id="rating" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="e.g. 4.5">
                    </div>
                    <div class="mb-4">
                        <label for="comment" class="block text-gray-700 text-sm font-bold mb-2">Comment</label>
                        <textarea name="comment" id="comment" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" rows="4"></textarea>
                    </div>
                    <div class="flex items-center justify-between">
                        <button type="submit" class="bg-black text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Submit Review</button>
                    </div>
                </form>
            <?php else: ?>
                <p class="mt-8">Please <a href="login.php" class="text-blue-500">log in</a> to add a review.</p>
            <?php endif; ?>

            <h2 class="text-3xl md:text-5xl font-bold mb-8 mt-12">Customer Reviews</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <?php foreach ($reviews as $review): ?>
                    <div class="bg-gray-800 text-white rounded-lg p-4">
                        <p class="font-bold"><?= htmlspecialchars($review['user_name']) ?></p>
                        <p><?= htmlspecialchars($review['comment']) ?></p>
                        <p>Rating: <?= htmlspecialchars($review['rating']) ?>/5</p>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
        
    </main>

    <?php include 'php/footer.php'; ?>

    <script>
        document.querySelectorAll('.thumbnail').forEach(item => {
            item.addEventListener('click', event => {
                document.getElementById('main-image').src = event.target.src;
            });
        });

        document.querySelector('.decrease-quantity').addEventListener('click', () => {
            const quantityElement = document.querySelector('.quantity');
            let quantity = parseInt(quantityElement.value);
            if (quantity > 1) {
                quantity--;
                quantityElement.value = quantity;
            }
        });

        document.querySelector('.increase-quantity').addEventListener('click', () => {
            const quantityElement = document.querySelector('.quantity');
            let quantity = parseInt(quantityElement.value);
            const maxQuantity = parseInt(quantityElement.max);
            if (quantity < maxQuantity) {
                quantity++;
                quantityElement.value = quantity;
            }
        });
    </script>
</body>
</html>
