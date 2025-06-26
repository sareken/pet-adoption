<?php
session_start();
try {
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=pet_adoption', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Veritabanı bağlantı hatası: ' . $e->getMessage();
    exit();
}

if (isset($_GET['id']) && isset($_SESSION['user_id'])) {
    $pet_id = $_GET['id'];
    $user_id = $_SESSION['user_id'];

    $sql = "DELETE FROM pets WHERE id = ? AND user_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$pet_id, $user_id]);

    $_SESSION['success_message'] = "İlan başarıyla silindi.";
}

header('Location: user_dashboard.php');
exit();
?>
