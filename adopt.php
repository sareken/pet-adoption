<?php
session_start();
include 'navbar.php';

if (!isset($_SESSION['user_id'])) {
    $_SESSION['redirect_to'] = $_SERVER['REQUEST_URI'];
    header('Location: login.php?redirect_to=add_pet.php'); // İlan Ver sayfasına yönlendirme
    exit();
}

$conn = new mysqli('127.0.0.1', 'root', '', 'pet_adoption'); // Veritabanı adı 'pet_adoption' olarak değiştirilmiştir.

if ($conn->connect_error) {
    die("Bağlantı hatası: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $pet_id = $_POST['pet_id'];
    $user_id = $_SESSION['user_id'];
    $message = $_POST['message'];

    $stmt = $conn->prepare("INSERT INTO adoption_requests (user_id, pet_id, message) VALUES (?, ?, ?)");
    $stmt->bind_param('iis', $user_id, $pet_id, $message);

    if ($stmt->execute()) {
        $success_message = "Başvurunuz başarıyla alındı!";
    } else {
        $error_message = "Hata: " . $stmt->error;
    }
    $stmt->close();
}

if (isset($_GET['id'])) {
    $pet_id = $_GET['id'];
    $sql = "SELECT * FROM pets WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $pet_id);
    $stmt->execute();
    $pet = $stmt->get_result()->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sahiplendirme Başvurusu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
<div class="container mt-5">
    <h1>Sahiplendirme Başvurusu</h1>
    <h2><?php echo htmlspecialchars($pet['name'] ?? '', ENT_QUOTES, 'UTF-8'); ?></h2>

    <?php
    if (isset($success_message)) {
        echo '<div class="alert alert-success mt-3" role="alert">' . $success_message . '</div>';
    } elseif (isset($error_message)) {
        echo '<div class="alert alert-danger mt-3" role="alert">' . $error_message . '</div>';
    }
    ?>

    <form action="adopt.php" method="POST">
        <input type="hidden" name="pet_id" value="<?php echo htmlspecialchars($pet['id']); ?>">
        <div class="mb-3">
            <label for="message" class="form-label">Mesajınız</label>
            <textarea class="form-control" id="message" name="message" rows="4" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Başvuruyu Gönder</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
