<?php
session_start();
include 'navbar.php';

if (!isset($_SESSION['user_id'])) {
    $_SESSION['redirect_to'] = $_SERVER['REQUEST_URI'];
    header('Location: login.php?redirect_to=add_pet.php');
}

$conn = new mysqli('127.0.0.1', 'root', '', 'pet_adoption'); // Veritabanı adı 'pet_adoption' olarak değiştirilmiştir.

if ($conn->connect_error) {
    die("Bağlantı hatası: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $species = $_POST['species'];
    $age = $_POST['age'];
    $gender = $_POST['gender']; // Yeni alan: Cinsiyet
    $description = $_POST['description'];
    $status = $_POST['status']; // Yeni alan: Durum
    $city = $_POST['city']; // Yeni alan: Şehir
    $image_url = ""; // Varsayılan değer

    if ($_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $image = $_FILES['image'];
        $upload_dir = 'assets/images/';
        $image_name = preg_replace("/[^a-zA-Z0-9\._-]/", "_", basename($image['name'])); // Geçersiz karakterleri değiştirme
        $image_path = $upload_dir . $image_name;

        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true); // Dizini oluştur
        }

        if (move_uploaded_file($image['tmp_name'], $image_path)) {
            $image_url = $image_path;
        } else {
            $image_url = '';
        }
    } else {
        $image_url = $_POST['image_url'];
    }


    $user_id = $_SESSION['user_id'];

    $stmt = $conn->prepare("INSERT INTO pets (name, species, age, gender, description, status, city, created_at, image_url, user_id) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    // 's' => string, 'i' => integer
    $stmt->bind_param('ssisssssss', $name, $species, $age, $gender, $description, $status, $city, $created_at, $image_url, $user_id);

    if ($stmt->execute()) {
        $message = "Evcil hayvan başarıyla eklendi!";
    }
    else {
        $message = "Hata: " . $stmt->error;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
    <title>Evcil Hayvan Ekle</title>
</head>
<body>
<div class="container mt-5">
    <h1 class="text-center">Evcil Hayvan Ekle</h1>

    <?php if (isset($message)): ?>
        <div class="alert alert-info"><?php echo $message; ?></div>
    <?php endif; ?>

    <form method="POST" action="" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="name" class="form-label">Adı:</label>
            <input type="text" id="name" name="name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="species" class="form-label">Tür:</label>
            <select id="species" name="species" class="form-select" required>
                <option value="Kedi">Kedi</option>
                <option value="Köpek">Köpek</option>
                <option value="Diğerleri">Diğerleri</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="age" class="form-label">Yaşı:</label>
            <input type="number" id="age" name="age" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="gender" class="form-label">Cinsiyet:</label>
            <select id="gender" name="gender" class="form-select" required>
                <option value="Erkek">Erkek</option>
                <option value="Dişi">Dişi</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="city" class="form-label">İl:</label>
            <input type="text" id="city" name="city" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Açıklama:</label>
            <textarea id="description" name="description" class="form-control"></textarea>
        </div>
        <div class="mb-3">
            <label for="status" class="form-label">Durum:</label>
            <select id="status" name="status" class="form-select" required>
                <option value="Aktif">Aktif</option>
                <option value="Beklemede">Beklemede</option>
                <option value="Sahiplendirildi">Sahiplendirildi</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="image" class="form-label">Resim Yükle:</label>
            <input type="file" class="form-control" id="image" name="image">
            <p class="text-muted">Veya resim URL'si ekleyebilirsiniz:</p>
            <input type="text" id="image_url" name="image_url" class="form-control" placeholder="Resim URL'si">
        </div>
        <button type="submit" class="btn btn-primary w-100">Ekle</button>
    </form>
</div>
</body>
</html>

