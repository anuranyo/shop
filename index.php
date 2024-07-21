<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Wholesale of cables and components</title>
    <!-- Tailwind CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet" />
    <link href="css/style.css" rel="stylesheet" />
</head>
<body class="font-sans h-screen overflow-x-hidden">
    <?php include 'php/header.php'; ?>

    <?php
    require_once 'php/categories.php';
    require_once 'php/products.php';

    $category = new Category();
    $categories = $category->getAllCategories();

    $product = new Product();
    $products = $product->getAllProducts();
    ?>

    <main class="text-center py-20 px-4 h-2/3">
        <div class="absolute inset-0">
            <img src="images/Vector.png" alt="Oval 1" class="absolute transform rotate-45 -top-30 -left-40 w-1/3 h-1/3">
            <img src="images/Vector1.png" alt="Oval 2" class="absolute transform rotate-45 top-0 -right-20  w-1/3 h-1/3">
            <img src="images/Vector2.png" alt="Oval 3" class="absolute transform rotate-45 bottom-20 left-24 w-1/3 h-1/3">
            <img src="images/Vector3.png" alt="Oval 4" class="absolute transform rotate-90 bottom-40 right-24 w-1/3 h-1/3">
        </div>

        <div class="relative">
            <h1 class="text-4xl md:text-6xl font-bold mb-6">Wholesale of cables and components</h1>
            <p class="text-xl mb-8">All products are certified and meet high technical requirements and standards.</p>
            <div class="flex justify-center space-x-4">
                <a href="#catalog" class="bg-black text-white py-3 px-8 rounded-full">Buy wholesale</a>
                <a href="contacts.php" class="bg-gray-200 text-black py-3 px-8 rounded-full">More</a>
            </div>
        </div>
    </main>

    <section class="bg-black text-white py-20 z-1">
        <div class="container mx-auto text-center">
            <h2 class="text-3xl md:text-5xl font-bold mb-8">Why choose us</h2>
            <div class="flex justify-center space-x-4 p-10">
                <div class="bg-yellow-200 text-black rounded-lg p-8 m-4 z-10">
                    <h3 class="text-2xl font-bold mb-4">Large assortment</h3>
                    <p>In our online store you can find wires for any purpose: installation of fixed and mobile networks in domestic or industrial premises, connection of power equipment, repair of household equipment and much more.</p>
                </div>
                <div class="bg-pink-200 text-black rounded-lg p-8 m-4 z-10">
                    <h3 class="text-2xl font-bold mb-4">High quality</h3>
                    <p>
                        A cable is a conductive product made from several metal conductors (cores) isolated from each other. With their help, various equipment is connected, as well as the electrical network. All products are certified,
                        which confirms high quality.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-black text-white py-10">
        <div class="container mx-auto text-center">
            <h2 id="catalog" class="text-3xl md:text-5xl font-bold mb-8">Product catalog</h2>
            <div class="grid grid-cols-2 md:grid-cols-6 gap-4">
                <?php foreach ($categories as $cat): ?>
                    <div class="bg-yellow-200 rounded-lg p-4">
                        <img src="<?= htmlspecialchars($cat['image_url']) ?>" alt="<?= htmlspecialchars($cat['name']) ?>" />
                        <p class="mt-2 text-black font-semibold"><?= htmlspecialchars($cat['name']) ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section class="pt-10 flex flex-col items-center bg-black">
            <h2 class="text-3xl md:text-5xl text-white font-bold mb-8">Last watched</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-3 gap-4 w-3/5">
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
                        echo '<p class="text-white font-bold">No viewed products yet.</p>';
                    } else {
                        foreach ($viewedProducts as $product) {
                            echo '<div class="border-2 border-white rounded-lg p-4 flex flex-col">
                                    <img class="h-80 m-4 max-w-full max-h-full object-contain" src="' . $product['image_url'] . '" alt="' . $product['name'] . '">
                                    <p class="mt-2 text-white">' . $product['name'] . '</p>
                                    <p class="mt-2 text-white font-bold">' . $product['price'] . ' $</p>
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

    <section class="bg-black text-white py-20">
        <div class="container mx-auto text-center">
            <h2 class="text-3xl md:text-5xl font-bold mb-8">Achievements and certificates</h2>
            <div class="carousel-container">
                <div class="carousel-slide">
                    <img src="images/sert.png" alt="Certificate 1" />
                    <img src="images/sert.png" alt="Certificate 2" />
                    <img src="images/sert.png" alt="Certificate 3" />
                    <img src="images/sert.png" alt="Certificate 4" />
                    <img src="images/sert.png" alt="Certificate 5" />
                    <img src="images/sert.png" alt="Certificate 1" />
                    <img src="images/sert.png" alt="Certificate 2" />
                    <img src="images/sert.png" alt="Certificate 3" />
                    <img src="images/sert.png" alt="Certificate 4" />
                </div>
            </div>
        </div>
    </section>

    <?php include 'php/footer.php'; ?>
</body>
</html>
