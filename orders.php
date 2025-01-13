<?php
require_once 'config/config.php';

// Sipariş oluşturma işlemi
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_order'])) {
    try {
        $stmt = $pdo->prepare("CALL CreateOrder(?, ?, ?, ?)");
        $stmt->execute([
            $_POST['customer_id'],
            $_POST['employee_id'], // Yeni eklenen
            $_POST['product_id'],
            $_POST['quantity']
        ]);
        $message = "Sipariş başarıyla oluşturuldu.";
    } catch (PDOException $e) {
        $error = "Hata: " . $e->getMessage();
    }
}

// Siparişleri listele
$orders = $pdo->query("
    SELECT o.OrderID, 
           c.FirstName as CustomerFirstName, 
           c.LastName as CustomerLastName, 
           e.FirstName as EmployeeFirstName,
           e.LastName as EmployeeLastName,
           o.OrderDate, 
           o.TotalAmount, 
           o.OrderStatus
    FROM Orders o
    JOIN Customers c ON o.CustomerID = c.CustomerID
    JOIN Employees e ON o.EmployeeID = e.EmployeeID
    ORDER BY o.OrderID DESC
")->fetchAll();

// Çalışanları listele (select için)
$employees = $pdo->query("SELECT EmployeeID, FirstName, LastName FROM Employees")->fetchAll();
// Müşterileri listele (select için)
$customers = $pdo->query("SELECT CustomerID, FirstName, LastName FROM Customers")->fetchAll();
// Ürünleri listele (select için)
$products = $pdo->query("SELECT ProductID, ProductName FROM Products")->fetchAll();
?>

<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Siparişler - Dore Ayakkabı</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100">
    <?php include 'includes/header.php'; ?>

    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4">Siparişler</h1>

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

        <!-- Sipariş Oluşturma Formu -->
        <div class="bg-white p-4 rounded shadow mb-4">
            <h2 class="text-xl font-bold mb-4">Yeni Sipariş Oluştur</h2>
            <form method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block mb-2">Müşteri:</label>
                    <select name="customer_id" required class="w-full px-3 py-2 border rounded">
                        <?php foreach ($customers as $customer): ?>
                            <option value="<?php echo $customer['CustomerID']; ?>">
                                <?php echo $customer['FirstName'] . ' ' . $customer['LastName']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block mb-2">Ürün:</label>
                    <select name="product_id" required class="w-full px-3 py-2 border rounded">
                        <?php foreach ($products as $product): ?>
                            <option value="<?php echo $product['ProductID']; ?>">
                                <?php echo $product['ProductName']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block mb-2">Miktar:</label>
                    <input type="number" name="quantity" required min="1" class="w-full px-3 py-2 border rounded">
                </div>
                <div>
                    <label class="block mb-2">Çalışan:</label>
                    <select name="employee_id" required class="w-full px-3 py-2 border rounded">
                        <?php foreach ($employees as $employee): ?>
                            <option value="<?php echo $employee['EmployeeID']; ?>">
                                <?php echo $employee['FirstName'] . ' ' . $employee['LastName']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="md:col-span-2">
                    <button type="submit" name="create_order"
                        class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                        Sipariş Oluştur
                    </button>
                </div>
            </form>
        </div>

        <!-- Sipariş Listesi -->
        <div class="bg-white p-4 rounded shadow">
            <h2 class="text-xl font-bold mb-4">Sipariş Listesi</h2>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="px-4 py-2 text-left">Sipariş ID</th>
                            <th class="px-4 py-2 text-left">Müşteri</th>
                            <th class="px-4 py-2 text-left">Tarih</th>
                            <th class="px-4 py-2 text-left">Toplam Tutar</th>
                            <th class="px-4 py-2 text-left">Durum</th>
                            <th class="px-4 py-2 text-left">Çalışan</th> <!-- Yeni eklenen -->
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orders as $order): ?>
                            <tr class="border-t">
                                <td class="px-4 py-2"><?php echo $order['OrderID']; ?></td>
                                <td class="px-4 py-2"><?php echo $order['CustomerFirstName'] . ' ' . $order['CustomerLastName']; ?>
                                <td class="px-4 py-2"><?php echo $order['OrderDate']; ?></td>
                                <td class="px-4 py-2"><?php echo $order['TotalAmount']; ?> TL</td>
                                <td class="px-4 py-2"><?php echo $order['OrderStatus']; ?></td>
                                <td class="px-4 py-2">
                                    <?php echo $order['EmployeeFirstName'] . ' ' . $order['EmployeeLastName']; ?>
                                </td>
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