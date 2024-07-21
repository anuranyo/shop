<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Methods</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>
<body class="font-sans bg-cover bg-center bg-no-repeat bg-gradient-to-bl from-rose-100 to-teal-100">
    <?php include 'php/header.php'; ?>

    <main class="container mx-auto py-20 px-4 text-white">
        <h1 class="text-4xl md:text-5xl font-bold mb-8 text-center">Payment Methods</h1>

        <section class="mb-20">
            <div class="bg-black bg-opacity-75 p-8 rounded-lg">
                <h2 class="text-3xl font-bold mb-4">Credit and Debit Cards</h2>
                <p class="mb-4">We accept Visa, MasterCard, and American Express credit and debit cards. All transactions are secured and encrypted.</p>
                <img src="https://placehold.co/400x100" alt="Credit and Debit Cards" class="mx-auto">
            </div>
        </section>

        <section class="mb-20">
            <div class="bg-black bg-opacity-75 p-8 rounded-lg">
                <h2 class="text-3xl font-bold mb-4">PayPal</h2>
                <p class="mb-4">Pay easily and securely with PayPal. Simply select PayPal at checkout and log in to your PayPal account to complete your purchase.</p>
                <img src="https://placehold.co/400x100" alt="PayPal" class="mx-auto">
            </div>
        </section>

        <section class="mb-20">
            <div class="bg-black bg-opacity-75 p-8 rounded-lg">
                <h2 class="text-3xl font-bold mb-4">Bank Transfer</h2>
                <p class="mb-4">You can also pay via bank transfer. Please contact our customer support for bank details and instructions on how to proceed with the payment.</p>
                <img src="https://placehold.co/400x100" alt="Bank Transfer" class="mx-auto">
            </div>
        </section>
    </main>

    <?php include 'php/footer.php'; ?>
</body>
</html>
