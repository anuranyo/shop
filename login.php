<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>
<body class="font-sans bg-cover bg-center bg-no-repeat">
    <?php include 'php/header.php'; ?>

    <main class="container mx-auto py-20 px-4">
        <div class="max-w-lg mx-auto bg-black text-white p-8 rounded-lg">
            <h1 class="text-4xl font-bold mb-8 text-center">Login</h1>
            <?php if (isset($_GET['error'])): ?>
                <p class="text-red-500 mb-5"><?php echo htmlspecialchars($_GET['error']); ?></p>
            <?php endif; ?>
            <form action="login_process.php" method="post">
                <div class="mb-4">
                    <label for="email" class="block text-gray-300 mb-2">Email</label>
                    <input type="email" id="email" name="email" class="w-full p-3 rounded-lg bg-gray-700 border border-gray-600 focus:outline-none focus:border-blue-500" placeholder="example@gmail.com">
                </div>
                <div class="mb-6 relative">
                    <label for="password" class="block text-gray-300 mb-2">Password</label>
                    <input type="password" id="password" name="password" class="w-full p-3 rounded-lg bg-gray-700 border border-gray-600 focus:outline-none focus:border-blue-500">
                    <span id="togglePassword" class="absolute right-3 top-11 text-gray-400 cursor-pointer">🙈</span>
                </div>
                <button type="submit" class="w-full bg-white text-black py-3 px-4 rounded-lg font-bold">Login</button>
            </form>
        </div>
    </main>

    <?php include 'php/footer.php'; ?>

    <script>
        document.getElementById('togglePassword').addEventListener('click', function () {
            const passwordInput = document.getElementById('password');
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            this.textContent = type === 'password' ? '🙈' : '👁️';
        });
    </script>
</body>
</html>
