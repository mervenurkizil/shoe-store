<?php
require_once 'config/config.php';

// Çalışan ekleme işlemi
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_employee'])) {
    try {
        $stmt = $pdo->prepare("CALL AddEmployee(?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $_POST['first_name'],
            $_POST['last_name'],
            $_POST['email'],
            $_POST['phone'],
            $_POST['hire_date'],
            $_POST['salary'],
            $_POST['position']
        ]);
        $message = "Çalışan başarıyla eklendi.";
    } catch (PDOException $e) {
        $error = "Hata: " . $e->getMessage();
    }
}

// Çalışan silme işlemi
if (isset($_POST['delete_employee'])) {
    try {
        $stmt = $pdo->prepare("CALL DeleteEmployee(?)");
        $stmt->execute([$_POST['employee_id']]);
        $message = "Çalışan başarıyla silindi.";
    } catch (PDOException $e) {
        $error = "Hata: " . $e->getMessage();
    }
}

// Çalışan güncelleme işlemi
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_employee'])) {
    try {
        $stmt = $pdo->prepare("CALL UpdateEmployee(?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $_POST['employee_id'],
            $_POST['first_name'],
            $_POST['last_name'],
            $_POST['email'],
            $_POST['phone'],
            $_POST['salary'],
            $_POST['position']
        ]);
        $message = "Çalışan başarıyla güncellendi.";
    } catch (PDOException $e) {
        // Maaş düşürme hatası kontrolü
        if (strpos($e->getMessage(), 'Maaş düşürülemez') !== false) {
            $error = "Hata: Çalışan maaşı mevcut maaşından düşük olamaz!";
        } else {
            $error = "Hata: " . $e->getMessage();
        }
    }
}

// Çalışanları listele
$employees = $pdo->query("SELECT * FROM Employees ORDER BY EmployeeID DESC")->fetchAll();
?>

<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Çalışanlar - Dore Ayakkabı</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100">
    <?php include 'includes/header.php'; ?>

    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4">Çalışanlar</h1>

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

        <!-- Çalışan Ekleme Formu -->
        <div class="bg-white p-4 rounded shadow mb-4">
            <h2 class="text-xl font-bold mb-4">Yeni Çalışan Ekle</h2>
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
                <div>
                    <label class="block mb-2">İşe Başlama Tarihi:</label>
                    <input type="date" name="hire_date" required class="w-full px-3 py-2 border rounded">
                </div>
                <div>
                    <label class="block mb-2">Pozisyon:</label>
                    <select name="position" required class="w-full px-3 py-2 border rounded">
                        <option value="Satış Danışmanı">Satış Danışmanı</option>
                        <option value="Kasiyer">Kasiyer</option>
                        <option value="Depo Görevlisi">Depo Görevlisi</option>
                        <option value="Mağaza Müdürü">Mağaza Müdürü</option>
                    </select>
                </div>
                <div>
                    <label class="block mb-2">Maaş:</label>
                    <input type="number" name="salary" required step="0.01" class="w-full px-3 py-2 border rounded">
                </div>
                <div class="md:col-span-2">
                    <button type="submit" name="add_employee"
                        class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                        Çalışan Ekle
                    </button>
                </div>
            </form>
        </div>

        <!-- Çalışan Listesi -->
        <div class="bg-white p-4 rounded shadow">
            <h2 class="text-xl font-bold mb-4">Çalışan Listesi</h2>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="px-4 py-2 text-left">ID</th>
                            <th class="px-4 py-2 text-left">Ad</th>
                            <th class="px-4 py-2 text-left">Soyad</th>
                            <th class="px-4 py-2 text-left">E-posta</th>
                            <th class="px-4 py-2 text-left">Telefon</th>
                            <th class="px-4 py-2 text-left">Pozisyon</th>
                            <th class="px-4 py-2 text-left">İşe Başlama</th>
                            <th class="px-4 py-2 text-left">Maaş</th>
                            <th class="px-4 py-2 text-left">İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($employees as $employee): ?>
                            <tr class="border-t">
                                <td class="px-4 py-2"><?php echo $employee['EmployeeID']; ?></td>
                                <td class="px-4 py-2"><?php echo $employee['FirstName']; ?></td>
                                <td class="px-4 py-2"><?php echo $employee['LastName']; ?></td>
                                <td class="px-4 py-2"><?php echo $employee['Email']; ?></td>
                                <td class="px-4 py-2"><?php echo $employee['Phone']; ?></td>
                                <td class="px-4 py-2"><?php echo $employee['Position']; ?></td>
                                <td class="px-4 py-2"><?php echo date('d.m.Y', strtotime($employee['HireDate'])); ?></td>
                                <td class="px-4 py-2"><?php echo number_format($employee['Salary'], 2, ',', '.'); ?> ₺</td>
                                <td class="px-4 py-2">
                                    <button onclick="openEditModal(<?php echo htmlspecialchars(json_encode($employee)); ?>)"
                                        class="bg-yellow-500 text-white px-2 py-1 rounded text-sm hover:bg-yellow-600 mr-2">
                                        Düzenle
                                    </button>
                                    <form method="POST" class="inline"
                                        onsubmit="return confirm('Bu çalışanı silmek istediğinizden emin misiniz?');">
                                        <input type="hidden" name="employee_id"
                                            value="<?php echo $employee['EmployeeID']; ?>">
                                        <button type="submit" name="delete_employee"
                                            class="bg-red-500 text-white px-2 py-1 rounded text-sm hover:bg-red-600">
                                            Sil
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Çalışan Düzenleme Modal -->
    <div id="editModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Çalışan Güncelle</h3>
                <form id="editForm" method="POST" class="mt-4">
                    <input type="hidden" name="update_employee" value="1">
                    <input type="hidden" name="employee_id" id="edit_employee_id">

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Ad:</label>
                        <input type="text" name="first_name" id="edit_first_name" required
                            class="w-full px-3 py-2 border rounded">
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Soyad:</label>
                        <input type="text" name="last_name" id="edit_last_name" required
                            class="w-full px-3 py-2 border rounded">
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">E-posta:</label>
                        <input type="email" name="email" id="edit_email" required
                            class="w-full px-3 py-2 border rounded">
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Telefon:</label>
                        <input type="tel" name="phone" id="edit_phone" required class="w-full px-3 py-2 border rounded">
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Pozisyon:</label>
                        <select name="position" id="edit_position" required class="w-full px-3 py-2 border rounded">
                            <option value="Satış Danışmanı">Satış Danışmanı</option>
                            <option value="Kasiyer">Kasiyer</option>
                            <option value="Depo Görevlisi">Depo Görevlisi</option>
                            <option value="Mağaza Müdürü">Mağaza Müdürü</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Maaş:</label>
                        <input type="number" name="salary" id="edit_salary" required step="0.01"
                            class="w-full px-3 py-2 border rounded">
                    </div>

                    <div class="flex justify-between">
                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                            Güncelle
                        </button>
                        <button type="button" onclick="closeEditModal()"
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
        function openEditModal(employee) {
            document.getElementById('editModal').classList.remove('hidden');
            document.getElementById('edit_employee_id').value = employee.EmployeeID;
            document.getElementById('edit_first_name').value = employee.FirstName;
            document.getElementById('edit_last_name').value = employee.LastName;
            document.getElementById('edit_email').value = employee.Email;
            document.getElementById('edit_phone').value = employee.Phone;
            document.getElementById('edit_position').value = employee.Position;
            document.getElementById('edit_salary').value = employee.Salary;
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
        }

        // Modal dışına tıklandığında kapatma
        window.onclick = function (event) {
            const modal = document.getElementById('editModal');
            if (event.target == modal) {
                closeEditModal();
            }
        }
    </script>
</body>

</html>