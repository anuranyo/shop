<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contacts</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>
<body class="font-sans bg-cover bg-center bg-no-repeat">
    <?php include 'php/header.php'; ?>

    <main class="container mx-auto py-20 px-4 text-white">
        <h1 class="text-4xl md:text-5xl font-bold mb-8 text-center">Contacts</h1>

        <section class="mb-20">
            <div class="bg-black bg-opacity-75 p-8 rounded-lg">
                <h2 class="text-3xl font-bold mb-4">Get in Touch</h2>
                <p class="mb-4">If you have any questions, feel free to reach out to us using the contact information below. Our team is here to assist you with any inquiries or concerns you may have.</p>
                
                <div class="mb-8">
                    <h3 class="text-2xl font-bold mb-2">Address</h3>
                    <p>Ukraine, Kyiv</p>
                </div>
                
                <div class="mb-8">
                    <h3 class="text-2xl font-bold mb-2">Phone</h3>
                    <p><a href="tel:+380500650540" class="hover:text-gray-400">+380 (50) 065-05-40</a></p>
                </div>

                <div class="mb-8">
                    <h3 class="text-2xl font-bold mb-2">Email</h3>
                    <p><a href="mailto:information@info.com" class="hover:text-gray-400">information@info.com</a></p>
                </div>
            </div>
        </section>

        <section class="mb-20">
            <div class="bg-black bg-opacity-75 p-8 rounded-lg">
                <h2 class="text-3xl font-bold mb-4">Contact Form</h2>
                <form action="contact_process.php" method="post">
                    <div class="mb-4">
                        <label for="name" class="block text-gray-300 mb-2">Name</label>
                        <input type="text" id="name" name="name" class="w-full p-3 rounded-lg bg-gray-700 border border-gray-600 focus:outline-none focus:border-blue-500" required>
                    </div>
                    <div class="mb-4">
                        <label for="email" class="block text-gray-300 mb-2">Email</label>
                        <input type="email" id="email" name="email" class="w-full p-3 rounded-lg bg-gray-700 border border-gray-600 focus:outline-none focus:border-blue-500" required>
                    </div>
                    <div class="mb-4">
                        <label for="message" class="block text-gray-300 mb-2">Message</label>
                        <textarea id="message" name="message" class="w-full p-3 rounded-lg bg-gray-700 border border-gray-600 focus:outline-none focus:border-blue-500" rows="5" required></textarea>
                    </div>
                    <button type="submit" name="send" class="w-full bg-white text-black py-3 px-4 rounded-lg font-bold">Send Message</button>
                </form>
            </div>
        </section>
    </main>

    <?php include 'php/footer.php'; ?>
</body>
</html>
