<?php
require_once 'config/config.php';

// Ürün ekleme işlemi
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_product'])) {
    try {
        $stmt = $pdo->prepare("CALL AddProduct(?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $_POST['product_name'],
            $_POST['brand'],
            $_POST['category'],
            $_POST['price'],
            $_POST['stock_quantity'],
            $_POST['color'],
            $_POST['size']
        ]);
        $message = "Ürün başarıyla eklendi.";
    } catch (PDOException $e) {
        $error = "Hata: " . $e->getMessage();
    }
}

// Stok güncelleme işlemi
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_stock'])) {
    try {
        $stmt = $pdo->prepare("CALL UpdateProductStock(?, ?)");
        $stmt->execute([
            $_POST['product_id'],
            $_POST['stock_quantity']
        ]);
        $message = "Stok başarıyla güncellendi.";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'Stok miktarı negatif olamaz') !== false) {
            $error = "Hata: Stok miktarı negatif olamaz!";
        } else {
            $error = "Hata: " . $e->getMessage();
        }
    }
}

// Ürünleri listele
$products = $pdo->query("SELECT * FROM Products ORDER BY ProductID DESC")->fetchAll();
?>

<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ürünler - Dore Ayakkabı</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100">
    <?php include 'includes/header.php'; ?>

    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4">Ürünler</h1>

        <?php if (isset($message)): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <?php if (isset($error)): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <!-- Ürün Ekleme Formu -->
        <div class="bg-white p-4 rounded shadow mb-4">
            <h2 class="text-xl font-bold mb-4">Yeni Ürün Ekle</h2>
            <form method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block mb-2">Ürün Adı:</label>
                    <input type="text" name="product_name" required class="w-full px-3 py-2 border rounded">
                </div>
                <div>
                    <label class="block mb-2">Marka:</label>
                    <input type="text" name="brand" required class="w-full px-3 py-2 border rounded">
                </div>
                <div>
                    <label class="block mb-2">Kategori:</label>
                    <select name="category" required class="w-full px-3 py-2 border rounded">
                        <option value="Erkek">Erkek</option>
                        <option value="Kadın">Kadın</option>
                        <option value="Çocuk">Çocuk</option>
                    </select>
                </div>
                <div>
                    <label class="block mb-2">Fiyat:</label>
                    <input type="number" step="0.01" name="price" required class="w-full px-3 py-2 border rounded">
                </div>
                <div>
                    <label class="block mb-2">Stok Miktarı:</label>
                    <input type="number" name="stock_quantity" required class="w-full px-3 py-2 border rounded">
                </div>
                <div>
                    <label class="block mb-2">Renk:</label>
                    <input type="text" name="color" required class="w-full px-3 py-2 border rounded">
                </div>
                <div>
                    <label class="block mb-2">Numara:</label>
                    <input type="number" name="size" required class="w-full px-3 py-2 border rounded">
                </div>
                <div class="md:col-span-2">
                    <button type="submit" name="add_product"
                        class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                        Ürün Ekle
                    </button>
                </div>
            </form>
        </div>

        <!-- Ürün Listesi -->
        <div class="bg-white p-4 rounded shadow">
            <h2 class="text-xl font-bold mb-4">Ürün Listesi</h2>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="px-4 py-2 text-left">ID</th>
                            <th class="px-4 py-2 text-left">Ürün Adı</th>
                            <th class="px-4 py-2 text-left">Marka</th>
                            <th class="px-4 py-2 text-left">Kategori</th>
                            <th class="px-4 py-2 text-left">Fiyat</th>
                            <th class="px-4 py-2 text-left">Stok</th>
                            <th class="px-4 py-2 text-left">Renk</th>
                            <th class="px-4 py-2 text-left">Numara</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($products as $product): ?>
                            <tr class="border-t">
                                <td class="px-4 py-2"><?php echo $product['ProductID']; ?></td>
                                <td class="px-4 py-2"><?php echo $product['ProductName']; ?></td>
                                <td class="px-4 py-2"><?php echo $product['Brand']; ?></td>
                                <td class="px-4 py-2"><?php echo $product['Category']; ?></td>
                                <td class="px-4 py-2"><?php echo $product['Price']; ?> TL</td>
                                <td class="px-4 py-2"><?php echo $product['StockQuantity']; ?></td>
                                <td class="px-4 py-2"><?php echo $product['Color']; ?></td>
                                <td class="px-4 py-2"><?php echo $product['Size']; ?></td>
                                <td class="px-4 py-2">
                                    <button onclick="openStockModal(<?php echo htmlspecialchars(json_encode([
                                        'id' => $product['ProductID'],
                                        'name' => $product['ProductName'],
                                        'stock' => $product['StockQuantity']
                                    ])); ?>)"
                                        class="bg-green-500 text-white px-2 py-1 rounded text-sm hover:bg-green-600 mr-2">
                                        Stok Güncelle
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div id="stockModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Stok Güncelle</h3>
                <p id="productInfo" class="text-sm text-gray-500 mt-2"></p>
                <form id="stockForm" method="POST" class="mt-4">
                    <input type="hidden" name="update_stock" value="1">
                    <input type="hidden" name="product_id" id="stock_product_id">

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Yeni Stok Miktarı:</label>
                        <input type="number" name="stock_quantity" id="stock_quantity" required min="0"
                            class="w-full px-3 py-2 border rounded">
                    </div>

                    <div class="flex justify-between">
                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                            Güncelle
                        </button>
                        <button type="button" onclick="closeStockModal()"
                            class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                            İptal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>
    <script>
        function openStockModal(product) {
            document.getElementById('stockModal').classList.remove('hidden');
            document.getElementById('stock_product_id').value = product.id;
            document.getElementById('stock_quantity').value = product.stock;
            document.getElementById('productInfo').textContent =
                `Ürün: ${product.name} - Mevcut Stok: ${product.stock}`;
        }

        function closeStockModal() {
            document.getElementById('stockModal').classList.add('hidden');
        }

        // Modal dışına tıklandığında kapatma
        window.onclick = function (event) {
            const modal = document.getElementById('stockModal');
            if (event.target == modal) {
                closeStockModal();
            }
        }
    </script>
</body>

</html>