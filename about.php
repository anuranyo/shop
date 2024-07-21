<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>
<body class="font-sans bg-cover bg-center bg-no-repeat">
    <?php include 'php/header.php'; ?>

    <main class="container mx-auto py-20 px-4 text-white">
        <h1 class="text-black text-4xl md:text-5xl font-bold mb-8 text-center">About Us</h1>

        <section class="mb-20">
            <div class="bg-black bg-opacity-75 p-8 rounded-lg">
                <h2 class="text-3xl font-bold mb-4">Our Story</h2>
                <p class="mb-4">We started our journey with a mission to provide the highest quality cables and wires to our customers. Over the years, we have grown into a leading supplier in the industry, known for our commitment to excellence and customer satisfaction.</p>
                <p class="mb-4">Our team is dedicated to sourcing the best materials and ensuring that every product we sell meets the highest standards of quality and reliability. We believe in the power of innovation and continuously strive to improve our products and services.</p>
                <p class="mb-4">Thank you for choosing us as your trusted partner. We look forward to serving you for many years to come.</p>
            </div>
        </section>

        <section class="mb-20">
            <div class="bg-black bg-opacity-75 p-8 rounded-lg">
                <h2 class="text-3xl font-bold mb-4">Our Team</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="text-center">
                        <img src="https://placehold.co/150x150" alt="Team Member 1" class="mx-auto rounded-full mb-4">
                        <h3 class="text-xl font-bold">John Doe</h3>
                        <p>CEO</p>
                    </div>
                    <div class="text-center">
                        <img src="https://placehold.co/150x150" alt="Team Member 2" class="mx-auto rounded-full mb-4">
                        <h3 class="text-xl font-bold">Jane Smith</h3>
                        <p>CTO</p>
                    </div>
                    <div class="text-center">
                        <img src="https://placehold.co/150x150" alt="Team Member 3" class="mx-auto rounded-full mb-4">
                        <h3 class="text-xl font-bold">Emily Johnson</h3>
                        <p>COO</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="mb-20">
            <div class="bg-black bg-opacity-75 p-8 rounded-lg">
                <h2 class="text-3xl font-bold mb-4">Our Values</h2>
                <ul class="list-disc list-inside">
                    <li class="mb-2">Quality: We are committed to providing the highest quality products and services.</li>
                    <li class="mb-2">Integrity: We conduct our business with the highest level of integrity and honesty.</li>
                    <li class="mb-2">Innovation: We embrace innovation and continuously strive to improve.</li>
                    <li class="mb-2">Customer Satisfaction: We prioritize our customers and their satisfaction above all.</li>
                </ul>
            </div>
        </section>
    </main>

    <?php include 'php/footer.php'; ?>
</body>
</html>
