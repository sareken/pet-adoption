<?php
session_start();
include 'db_connection.php';
include 'navbar.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];

        $redirect_url = $_GET['redirect_to'] ?? 'index.php'; // Eğer yoksa anasayfaya git
        header("Location: $redirect_url");
        exit;
    } else {
        $error = "Geçersiz e-posta veya şifre!";
    }
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    echo "Formdan gelen veriler:<br>";
    echo "Kullanıcı adı: " . $_POST['username'] . "<br>";
    echo "Şifre: " . $_POST['password'] . "<br>";
}
?>
