<?php
session_start();
include 'navbar.php';

if (!isset($_SESSION['user_id'])) {
    $_SESSION['redirect_to'] = $_SERVER['REQUEST_URI'];
    header('Location: login.php?redirect_to=adopted_pets.php'); // Sahiplendirilmiş ilanlara yönlendirme
    exit();
}

$conn = new mysqli('127.0.0.1', 'root', '', 'pet_adoption'); // Veritabanı adı 'pet_adoption'

if ($conn->connect_error) {
    die("Bağlantı hatası: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT * FROM adopted_pets WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();


if ($result->num_rows > 0) {
    $adopted_pets = $result->fetch_all(MYSQLI_ASSOC);
} else {
    $adopted_pets = [];
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Geçmiş İlanlarım</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1 class="text-center">Geçmiş İlanlarım</h1>

    <?php if (empty($adopted_pets)): ?>
        <div class="alert alert-info text-center mt-4" role="alert">
            <strong>Henüz sahiplendirdiğiniz evcil hayvan yok.</strong>
        </div>
    <?php else: ?>
        <div class="row g-4">
            <?php foreach ($adopted_pets as $pet): ?>
                <div class="col-md-4">
                    <div class="card pets-card">
                        <div class="image-container">
                            <?php
                            $imageSrc = $pet['image_url'] ? $pet['image_url'] : 'assets/images/default.jpg';
                            ?>
                            <img src="<?= $imageSrc ?>" class="card-img-top" alt="Evcil Hayvan Resmi">
                        </div>
                        <div class="card-body">
                            <h5 class="card-title"><?= $pet['name'] ?></h5>
                            <p class="card-text text-success"><strong>Sahiplendirildi</strong></p> <!-- Sahiplendirildi yazısı -->
                            <p class="card-text text-muted">
                                <strong>Sahiplendirilme Tarihi: </strong>
                                <?= date("d F Y", strtotime($pet['adopted_at'])) ?>
                            </p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
