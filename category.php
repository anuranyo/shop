<?php
require_once 'php/db.php';
require_once 'php/products.php';
require_once 'php/cart.php';

session_start();

// Get category name from query parameter
$category_name = isset($_GET['category']) ? htmlspecialchars($_GET['category']) : '';

// Fetch category ID from the database using the category name
$db = new Database();
$sql = 'SELECT id FROM Categories WHERE name = :name';
$category = $db->query($sql, ['name' => $category_name])->fetch(PDO::FETCH_ASSOC);

if (!$category) {
    echo "Category not found.";
    exit;
}

$category_id = $category['id'];

$productClass = new Product($db);
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'popularity';

switch ($sort) {
    case 'alphabetical':
        $products = $productClass->getProductsByCategoryIdSorted($category_id, 'name');
        break;
    case 'price':
        $products = $productClass->getProductsByCategoryIdSorted($category_id, 'price');
        break;
    case 'popularity':
    default:
        $products = $productClass->getProductsByCategoryIdSorted($category_id, 'popularity');
        break;
}

// Handle Add to Cart action
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_to_cart') {
    $productId = isset($_POST['product_id']) ? $_POST['product_id'] : 0;
    if ($productId && isset($_SESSION['user_id'])) {
        $cartClass = new Cart($db);
        $cartClass->addToCart($_SESSION['user_id'], $productId);
    }
    header("Location: category.php?category=$category_name&sort=$sort");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $category_name; ?> Products</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <style>
        .list-view .product-item {
            display: flex;
            align-items: center;
        }
        .list-view .product-item img {
            width: 100px;
            height: auto;
            margin-right: 16px;
        }
        .list-view .product-details {
            flex-grow: 1;
        }
    </style>
</head>
<body class="font-sans bg-cover bg-center bg-no-repeat" style="background: linear-gradient(135deg, #D7E1EC , #FFFFFF);">
    <?php include 'php/header.php'; ?>

    <main class="container mx-auto py-20 px-4">
        <nav class="text-gray-600 mb-8">
            <a href="index.php" class="hover:text-gray-400">Home</a> &gt; <a href="catalog.php" class="hover:text-gray-400">Product Catalog</a> &gt; <a href="#" class="hover:text-gray-400"><?php echo $category_name; ?></a>
        </nav>
        <h1 class="text-4xl md:text-5xl font-bold mb-8"><?php echo $category_name; ?></h1>

        <div class="flex justify-between items-center mb-8">
            <div class="flex space-x-4">
                <button onclick="sortProducts('popularity')" class="bg-gray-800 text-white py-2 px-4 rounded-md">Popularity</button>
                <button onclick="sortProducts('alphabetical')" class="bg-gray-800 text-white py-2 px-4 rounded-md">Alphabetical</button>
                <button onclick="sortProducts('price')" class="bg-gray-800 text-white py-2 px-4 rounded-md">Price</button>
            </div>
            <div class="flex space-x-4">
                <button onclick="toggleView('grid')" class="bg-gray-800 text-white py-2 px-4 rounded-md">Grid View</button>
                <button onclick="toggleView('list')" class="bg-gray-800 text-white py-2 px-4 rounded-md">List View</button>
            </div>
        </div>

        <section id="products" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 mb-20">
            <?php foreach ($products as $product): ?>
                <div class="product-item border-2 border-black rounded-lg p-4 flex flex-col">
                    <img class="h-64" src="<?php echo htmlspecialchars($product['image_url']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                    <div class="product-details">
                        <a href="product.php?product_id=<?php echo htmlspecialchars($product['id']); ?>" class="mt-2 hover:underline block"><?php echo htmlspecialchars($product['name']); ?></a>
                        <p class="mt-2 font-bold">$<?php echo htmlspecialchars($product['price']); ?></p>
                        <form method="post">
                            <input type="hidden" name="action" value="add_to_cart">
                            <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                            <button type="submit" class="bg-black text-white py-2 px-4 rounded-full mt-4">Add to cart</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        </section>
    </main>

    <?php include 'php/footer.php'; ?>

    <script>
        function sortProducts(sortBy) {
            const urlParams = new URLSearchParams(window.location.search);
            urlParams.set('sort', sortBy);
            window.location.search = urlParams.toString();
        }

        function toggleView(view) {
            const productsSection = document.getElementById('products');
            if (view === 'list') {
                productsSection.classList.add('list-view');
                productsSection.classList.remove('grid', 'grid-cols-2', 'md:grid-cols-3', 'lg:grid-cols-4');
            } else {
                productsSection.classList.remove('list-view');
                productsSection.classList.add('grid', 'grid-cols-2', 'md:grid-cols-3', 'lg:grid-cols-4');
            }
        }
    </script>
</body>
</html>
