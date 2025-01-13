<?php
require_once 'config/config.php';

// Müşteri ekleme işlemi
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_customer'])) {
    try {
        $stmt = $pdo->prepare("INSERT INTO Customers (FirstName, LastName, Email, Phone, Address) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([
            $_POST['first_name'],
            $_POST['last_name'],
            $_POST['email'],
            $_POST['phone'],
            $_POST['address']
        ]);
        $message = "Müşteri başarıyla eklendi.";
    } catch (PDOException $e) {
        $error = "Hata: " . $e->getMessage();
    }
}

// Müşterileri listele
$customers = $pdo->query("SELECT * FROM Customers ORDER BY CustomerID DESC")->fetchAll();
?>

<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Müşteriler - Dore Ayakkabı</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100">
    <?php include 'includes/header.php'; ?>

    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4">Müşteriler</h1>

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

        <!-- Müşteri Ekleme Formu -->
        <div class="bg-white p-4 rounded shadow mb-4">
            <h2 class="text-xl font-bold mb-4">Yeni Müşteri Ekle</h2>
            <form method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block mb-2">Ad:</label>
                    <input type="text" name="first_name" required class="w-full px-3 py-2 border rounded">
                </div>
                <div>
                    <label class="block mb-2">Soyad:</label>
                    <input type="text" name="last_name" required class="w-full px-3 py-2 border rounded">
                </div>
                <div>
                    <label class="block mb-2">E-posta:</label>
                    <input type="email" name="email" required class="w-full px-3 py-2 border rounded">
                </div>
                <div>
                    <label class="block mb-2">Telefon:</label>
                    <input type="tel" name="phone" required class="w-full px-3 py-2 border rounded">
                </div>
                <div class="md:col-span-2">
                    <label class="block mb-2">Adres:</label>
                    <textarea name="address" required class="w-full px-3 py-2 border rounded"></textarea>
                </div>
                <div class="md:col-span-2">
                    <button type="submit" name="add_customer"
                        class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                        Müşteri Ekle
                    </button>
                </div>
            </form>
        </div>

        <!-- Müşteri Listesi -->
        <div class="bg-white p-4 rounded shadow">
            <h2 class="text-xl font-bold mb-4">Müşteri Listesi</h2>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="px-4 py-2 text-left">ID</th>
                            <th class="px-4 py-2 text-left">Ad</th>
                            <th class="px-4 py-2 text-left">Soyad</th>
                            <th class="px-4 py-2 text-left">E-posta</th>
                            <th class="px-4 py-2 text-left">Telefon</th>
                            <th class="px-4 py-2 text-left">Adres</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($customers as $customer): ?>
                            <tr class="border-t">
                                <td class="px-4 py-2"><?php echo $customer['CustomerID']; ?></td>
                                <td class="px-4 py-2"><?php echo $customer['FirstName']; ?></td>
                                <td class="px-4 py-2"><?php echo $customer['LastName']; ?></td>
                                <td class="px-4 py-2"><?php echo $customer['Email']; ?></td>
                                <td class="px-4 py-2"><?php echo $customer['Phone']; ?></td>
                                <td class="px-4 py-2"><?php echo $customer['Address']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>
</body>

</html>