<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Catalog</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>
<body class="font-sans bg-black bg-cover bg-center bg-no-repeat" style="background: linear-gradient(135deg, #D7E1EC , #FFFFFF);">
    <?php include 'php/header.php'; ?>

    <main class="catalog container mx-auto py-20 px-4">
        <nav class="text-gray-600 mb-8">
            <a href="index.php" class="hover:text-gray-400">Home</a> &gt; <a href="#" class="hover:text-gray-400">Product Catalog</a>
        </nav>
        <h1 class="text-4xl md:text-5xl font-bold mb-8">Product Catalog</h1>

        <section class="mb-20">
            <div class="grid grid-cols-2 md:grid-cols-6 gap-4">
                <?php
                require_once 'php/categories.php';
                $categoryObj = new Category();
                $categories = $categoryObj->getAllCategories();

                foreach ($categories as $category) {
                    echo '<a href="category.php?category=' . $category['name'] . '" class="border-2 border-black rounded-lg p-4">
                            <img src="' . $category['image_url'] . '" alt="' . $category['name'] . '" />
                            <p class="mt-2 text-black font-semibold">' . $category['name'] . '</p>
                          </a>';
                }
                ?>
            </div>
        </section>

        <section class="mb-20">
            <h2 class="text-3xl md:text-5xl font-bold mb-8">Last watched</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-3 gap-4">
                <?php
                require_once 'php/db.php';
                session_start();

                if (isset($_SESSION['user_id'])) {
                    $userId = $_SESSION['user_id'];
                    $db = new Database();
                    $viewedProductsQuery = $db->query('
                        SELECT p.name, p.description, p.price, p.image_url, p.rating 
                        FROM ViewedProducts vp 
                        JOIN Products p ON vp.product_id = p.id 
                        WHERE vp.user_id = :userId 
                        ORDER BY vp.viewed_at DESC 
                        LIMIT 3
                    ', ['userId' => $userId]);

                    $viewedProducts = $viewedProductsQuery->fetchAll(PDO::FETCH_ASSOC);

                    if (empty($viewedProducts)) {
                        echo '<p class="text-black font-bold">No viewed products yet.</p>';
                    } else {
                        foreach ($viewedProducts as $product) {
                            echo '<div class="border-2 border-black rounded-lg p-4 flex flex-col">
                                    <img class="h-80 m-4 max-w-full max-h-full" src="' . $product['image_url'] . '" alt="' . $product['name'] . '">
                                    <p class="mt-2">' . $product['name'] . '</p>
                                    <p class="mt-2 font-bold">' . $product['price'] . ' $</p>
                                    <button class="bg-black text-white py-2 px-4 rounded-full mt-4">Add to cart</button>
                                  </div>';
                        }
                    }
                } else {
                    echo '<p class="text-white">Please log in to see your viewed products.</p>';
                }
                ?>
            </div>
        </section>
    </main>

    <?php include 'php/footer.php'; ?>
</body>
</html>
