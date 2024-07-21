<?php
require_once 'php/db.php';
require_once 'php/users.php';
require_once 'php/products.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$db = new Database();
$userClass = new User($db);
$isAdmin = $userClass->isAdmin($_SESSION['user_id']);

if (!$isAdmin) {
    header("Location: index.php");
    exit;
}

// Get all users and products for admin management
$productClass = new Product($db);
$products = $productClass->getAllProducts();
$users = $userClass->getAllUsers();

// Get top viewed products
$sql = 'SELECT product_id, COUNT(*) AS view_count
        FROM ViewedProducts
        GROUP BY product_id
        ORDER BY view_count DESC
        LIMIT 5';
$topViewedStmt = $db->query($sql);
$topViewedProducts = $topViewedStmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (isset($_POST['product_id'])) {
            $productId = $_POST['product_id'];
            $productName = $_POST['name'];
            $productPrice = $_POST['price'];
            $productImages = $_POST['image_url'];

            // Update product information
            $sql = 'UPDATE Products SET name = :name, price = :price, image_url = :image_url WHERE id = :id';
            $stmt = $db->query($sql);
            $stmt->execute([
                ':name' => $productName,
                ':price' => $productPrice,
                ':image_url' => $productImages,
                ':id' => $productId
            ]);
            
            header("Location: admin.php");
            exit;
        } elseif (isset($_POST['delete_product_id'])) {
            $productId = $_POST['delete_product_id'];

            // Delete product
            $sql = 'DELETE FROM Products WHERE id = :id';
            $stmt = $db->query($sql);
            $stmt->execute([':id' => $productId]);

            header("Location: admin.php");
            exit;
        } elseif (isset($_POST['user_id'])) {
            $userId = $_POST['user_id'];
            $username = $_POST['username'];
            $email = $_POST['email'];
            $role = $_POST['role'];

            // Update user information
            $sql = 'UPDATE Users SET username = :username, email = :email, role = :role WHERE id = :id';
            $stmt = $db->query($sql);
            $stmt->execute([
                ':username' => $username,
                ':email' => $email,
                ':role' => $role,
                ':id' => $userId
            ]);

            header("Location: admin.php");
            exit;
        } elseif (isset($_POST['delete_user_id'])) {
            $userId = $_POST['delete_user_id'];

            // Delete user
            $sql = 'DELETE FROM Users WHERE id = :id';
            $stmt = $db->query($sql);
            $stmt->execute([':id' => $userId]);

            header("Location: admin.php");
            exit;
        }
    } catch (Exception $e) {
        error_log($e->getMessage());
        echo 'Error: ' . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="font-sans bg-cover bg-center bg-no-repeat bg-gradient-to-bl from-rose-100 to-teal-100">
    <?php include 'php/header.php'; ?>

    <main class="container mx-auto py-20 px-4">
        <h1 class="text-4xl md:text-5xl font-bold mb-8">Admin Panel</h1>

        <div class="flex flex-row gap-6 mb-6">
            <button class="border-2 border-black text-black py-2 px-4 rounded-full mt-4 w-40"> <a href="#products">To Products</a></button>
            <button class="border-2 border-black text-black py-2 px-4 rounded-full mt-4 w-40"> <a href="#users">To Users</a></button>
            <button class="border-2 border-black text-black py-2 px-4 rounded-full mt-4 w-40"> <a href="#visited">To Most Visited</a></button>
        </div>

        <section class="mb-20">
            <h2 id="products" class="text-3xl font-bold mb-8">Manage Products</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4">
                <?php foreach ($products as $product): ?>
                    <div class="border-2 border-black rounded-lg p-4 flex flex-col items-center">
                        <?php if (!empty($product['image_url'])): ?>
                            <?php foreach (explode(',', $product['image_url']) as $image): ?>
                                <img src="<?= htmlspecialchars(trim($image)) ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="h-40 w-40 object-cover mb-4">
                            <?php endforeach; ?>
                        <?php else: ?>
                            <img src="https://placehold.co/40x40" alt="No Image" class="h-40 w-40 object-cover mb-4">
                        <?php endif; ?>
                        <p class="font-bold"><?= htmlspecialchars($product['name']) ?></p>
                        <p class="mb-2">$<?= htmlspecialchars($product['price']) ?></p>
                        <button onclick="openProductEditModal(<?= $product['id'] ?>, '<?= htmlspecialchars($product['name']) ?>', <?= $product['price'] ?>, '<?= htmlspecialchars($product['image_url']) ?>')" class="bg-yellow-500 text-black py-2 px-4 rounded-md mb-2">Edit</button>
                        <form action="" method="post">
                            <input type="hidden" name="delete_product_id" value="<?= $product['id'] ?>">
                            <button type="submit" class="bg-red-500 text-white py-2 px-4 rounded-md">Delete</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>

        <section class="mb-20">
            <h2 id="users" class="text-3xl font-bold mb-8">Manage Users</h2>
            <table class="min-w-full bg-white">
                <thead>
                    <tr class="text-left w-full">
                        <th class="py-2 px-4 border-b-2 border-gray-300">ID</th>
                        <th class="py-2 px-4 border-b-2 border-gray-300">Username</th>
                        <th class="py-2 px-4 border-b-2 border-gray-300">Email</th>
                        <th class="py-2 px-4 border-b-2 border-gray-300">Role</th>
                        <th class="py-2 px-4 border-b-2 border-gray-300 w-1/6">Actions</th>
                    </tr> 
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td class="py-2 px-4 border-b"><?= htmlspecialchars($user['id']) ?></td>
                            <td class="py-2 px-4 border-b"><?= htmlspecialchars($user['username']) ?></td>
                            <td class="py-2 px-4 border-b"><?= htmlspecialchars($user['email']) ?></td>
                            <td class="py-2 px-4 border-b"><?= htmlspecialchars($user['role']) ?></td>
                            <td class="py-2 px-4 border-b flex flex-row justify-between">
                                <button onclick="openUserEditModal(<?= $user['id'] ?>, '<?= htmlspecialchars($user['username']) ?>', '<?= htmlspecialchars($user['email']) ?>', '<?= htmlspecialchars($user['role']) ?>')" class="bg-yellow-500 text-black py-2 px-4 rounded-md">Edit</button>
                                <form action="" method="post">
                                    <input type="hidden" name="delete_user_id" value="<?= $user['id'] ?>">
                                    <button type="submit" class="bg-red-500 text-white py-2 px-4 rounded-md">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>

        <section class="mb-20">
            <h2 id="visited" class="text-3xl font-bold mb-8">Top Viewed Products</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4">
                <?php foreach ($topViewedProducts as $viewedProduct): ?>
                    <?php
                    $product = $productClass->getProductById($viewedProduct['product_id']);
                    ?>
                    <div class="border-2 border-black rounded-lg p-4 flex flex-col items-center">
                        <?php if (!empty($product['image_url'])): ?>
                            <?php foreach (explode(',', $product['image_url']) as $image): ?>
                                <img src="<?= htmlspecialchars(trim($image)) ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="h-40 w-40 object-cover mb-4">
                            <?php endforeach; ?>
                        <?php else: ?>
                            <img src="https://placehold.co/40x40" alt="No Image" class="h-40 w-40 object-cover mb-4">
                        <?php endif; ?>
                        <p class="font-bold"><?= htmlspecialchars($product['name']) ?></p>
                        <p class="mb-2">$<?= htmlspecialchars($product['price']) ?></p>
                        <p class="mb-2">Views: <?= htmlspecialchars($viewedProduct['view_count']) ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
    </main>

    <?php include 'php/footer.php'; ?>

    <!-- Product Edit Modal -->
    <div id="productEditModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-10">
        <div class="bg-white p-8 rounded-lg max-w-md w-full">
            <h2 class="text-2xl font-bold mb-4">Edit Product</h2>
            <form id="productEditForm" method="post">
                <input type="hidden" name="product_id" id="productEditId">
                <div class="mb-4">
                    <label for="productEditName" class="block text-gray-700">Product Name</label>
                    <input type="text" name="name" id="productEditName" class="w-full border border-gray-300 p-2 rounded">
                </div>
                <div class="mb-4">
                    <label for="productEditPrice" class="block text-gray-700">Price</label>
                    <input type="text" name="price" id="productEditPrice" class="w-full border border-gray-300 p-2 rounded">
                </div>
                <div class="mb-4">
                    <label for="productEditImages" class="block text-gray-700">Images (comma separated URLs)</label>
                    <input type="text" name="image_url" id="productEditImages" class="w-full border border-gray-300 p-2 rounded">
                </div>
                <div class="flex justify-end space-x-4">
                    <button type="button" onclick="closeProductEditModal()" class="bg-gray-500 text-white py-2 px-4 rounded-md">Cancel</button>
                    <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded-md">Save</button>
                </div>
            </form>
        </div>
    </div>

    <!-- User Edit Modal -->
    <div id="userEditModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
        <div class="bg-white p-8 rounded-lg max-w-md w-full">
            <h2 class="text-2xl font-bold mb-4">Edit User</h2>
            <form id="userEditForm" method="post">
                <input type="hidden" name="user_id" id="userEditId">
                <div class="mb-4">
                    <label for="userEditUsername" class="block text-gray-700">Username</label>
                    <input type="text" name="username" id="userEditUsername" class="w-full border border-gray-300 p-2 rounded">
                </div>
                <div class="mb-4">
                    <label for="userEditEmail" class="block text-gray-700">Email</label>
                    <input type="email" name="email" id="userEditEmail" class="w-full border border-gray-300 p-2 rounded">
                </div>
                <div class="mb-4">
                    <label for="userEditRole" class="block text-gray-700">Role</label>
                    <select name="role" id="userEditRole" class="w-full border border-gray-300 p-2 rounded">
                        <option value="user">User</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                <div class="flex justify-end space-x-4">
                    <button type="button" onclick="closeUserEditModal()" class="bg-gray-500 text-white py-2 px-4 rounded-md">Cancel</button>
                    <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded-md">Save</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openProductEditModal(id, name, price, images) {
            document.getElementById('productEditId').value = id;
            document.getElementById('productEditName').value = name;
            document.getElementById('productEditPrice').value = price;
            document.getElementById('productEditImages').value = images;
            document.getElementById('productEditModal').classList.remove('hidden');
        }

        function closeProductEditModal() {
            document.getElementById('productEditModal').classList.add('hidden');
        }

        function openUserEditModal(id, username, email, role) {
            document.getElementById('userEditId').value = id;
            document.getElementById('userEditUsername').value = username;
            document.getElementById('userEditEmail').value = email;
            document.getElementById('userEditRole').value = role;
            document.getElementById('userEditModal').classList.remove('hidden');
        }

        function closeUserEditModal() {
            document.getElementById('userEditModal').classList.add('hidden');
        }
    </script>
</body>
</html>
