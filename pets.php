<?php
session_start();
include 'navbar.php';

$conn = new mysqli('127.0.0.1', 'root', '', 'pet_adoption'); // Veritabanı adı 'pet_adoption'

if ($conn->connect_error) {
    die("Bağlantı hatası: " . $conn->connect_error);
}

$type = isset($_GET['type']) ? $_GET['type'] : '';

if ($type) {
    $sql = "SELECT * FROM pets WHERE species = '$type' AND status = 'Aktif'"; // 'species' sütununa göre filtreleme
} else {
    $sql = "SELECT * FROM pets WHERE status = 'Aktif'";
}

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $pets = $result->fetch_all(MYSQLI_ASSOC);
} else {
    $pets = [];
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>İlanlar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&family=Roboto:wght@300;400;700&display=swap" rel="stylesheet"> <!-- Google Fonts -->
    <link href="style.css" rel="stylesheet"> <!-- Harici CSS dosyasını dahil ettik -->

</head>
<body>
<div class="container mt-5">
    <h1 class="text-center mb-5">İlanlar</h1>
    <div class="row g-4">
        <?php foreach ($pets as $pet): ?>
            <div class="col-md-4">
                <div class="card custom-pet-card"> <!-- custom-pet-card sınıfını ekledik -->
                    <div class="custom-image-container">
                        <?php
                        $imageSrc = $pet['image_url'] ? $pet['image_url'] : 'assets/images/default.jpg';
                        ?>
                        <img src="<?= $imageSrc ?>" class="card-img-top" alt="Evcil Hayvan Resmi">
                    </div>
                    <div class="card-body">
                        <h5 class="card-title text-center"><?= $pet['name'] ?></h5>
                        <p class="card-text text-muted"><?= $pet['description'] ?></p>
                        <p class="card-text text-center">
                            <i class="fas fa-birthday-cake"></i> <?= $pet['age'] ?> Yaş
                        </p>
                        <p class="card-text text-center">
                            <?php if ($pet['gender'] == 'Erkek'): ?>
                                <i class="fas fa-mars"></i> Erkek
                            <?php elseif ($pet['gender'] == 'Dişi'): ?>
                                <i class="fas fa-venus"></i> Dişi
                            <?php endif; ?>
                        </p>
                        <p class="card-text text-center text-info">
                            <i class="fas fa-map-marker-alt"></i> <?= $pet['city'] ?>
                        </p>
                        <a href="adopt.php?id=<?= $pet['id'] ?>" class="btn btn-primary w-100">Sahiplendirme Başvurusu</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
