<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Dore Ayakkabı</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
</head>

<body>
    <nav class="bg-gray-800 p-4">
        <div class="container mx-auto flex justify-between items-center">
            <a href="index.php" class="text-white text-xl font-bold">Dore Ayakkabı</a>
            <div class="space-x-4">
                <a href="products.php" class="text-white">Ürünler</a>
                <a href="orders.php" class="text-white">Siparişler</a>
                <a href="customers.php" class="text-white">Müşteriler</a>
                <a href="employees.php" class="text-white">Çalışanlar</a>
            </div>
        </div>
    </nav>

    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4">Hoşgeldiniz</h1>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-white p-4 rounded shadow">
                <h2 class="text-xl font-bold mb-2">Ürünler</h2>
                <p>Tüm ürünleri görüntüle ve yönet</p>
                <a href="products.php" class="mt-2 inline-block bg-blue-500 text-white px-4 py-2 rounded">Ürünlere
                    Git</a>
            </div>
            <div class="bg-white p-4 rounded shadow">
                <h2 class="text-xl font-bold mb-2">Siparişler</h2>
                <p>Siparişleri görüntüle ve yönet</p>
                <a href="orders.php" class="mt-2 inline-block bg-blue-500 text-white px-4 py-2 rounded">Siparişlere
                    Git</a>
            </div>
            <div class="bg-white p-4 rounded shadow">
                <h2 class="text-xl font-bold mb-2">Müşteriler</h2>
                <p>Müşteri bilgilerini görüntüle ve yönet</p>
                <a href="customers.php" class="mt-2 inline-block bg-blue-500 text-white px-4 py-2 rounded">Müşterilere
                    Git</a>
            </div>
            <div class="bg-white p-4 rounded shadow">
                <h2 class="text-xl font-bold mb-2">Çalışanlar</h2>
                <p>Çalışan bilgilerini görüntüle ve yönet</p>
                <a href="employees.php" class="mt-2 inline-block bg-blue-500 text-white px-4 py-2 rounded">Çalışanlara
                    Git</a>
            </div>
        </div>
    </div>
</body>

</html>