<?php
session_start();
include 'db_connection.php';
include 'navbar.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    $sql_check_email = "SELECT COUNT(*) FROM users WHERE email = ?";
    $stmt_check_email = $pdo->prepare($sql_check_email);
    $stmt_check_email->execute([$email]);
    $email_count = $stmt_check_email->fetchColumn();

    if ($email_count > 0) {
        $_SESSION['error_message'] = "Bu e-posta adresi zaten kullanılıyor. Lütfen başka bir e-posta adresi girin.";
    } else {
        if ($password !== $confirm_password) {
            $_SESSION['error_message'] = "Şifreler eşleşmiyor. Lütfen tekrar deneyin.";
        } else {
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

            $sql = "INSERT INTO users (username, email,phone, password) VALUES (?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);

            try {
                $stmt->execute([$username, $email,  $phone,$hashedPassword]);
                $_SESSION['success_message'] = "Kayıt başarıyla yapıldı! Giriş yapabilirsiniz.";

                header("Location: login.php");
                exit();
            } catch (PDOException $e) {
                $_SESSION['error_message'] = "Bir hata oluştu: " . $e->getMessage();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kayıt Ol</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Kayıt Ol</h2>

    <?php if (isset($_SESSION['error_message'])): ?>
        <div class="alert alert-danger">
            <?= $_SESSION['error_message']; ?>
        </div>
        <?php unset($_SESSION['error_message']); ?>
    <?php endif; ?>

    <form action="register.php" method="POST" id="registerForm">
        <div class="mb-3">
            <label for="username" class="form-label">Kullanıcı Adı</label>
            <input type="text" class="form-control" id="username" name="username" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">E-posta</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <div class="mb-3">
            <label for="phone" class="form-label">Telefon Numarası</label>
            <input type="text" class="form-control" id="phone" name="phone" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Şifre</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <div class="mb-3">
            <label for="confirm_password" class="form-label">Şifre (Tekrar)</label>
            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
        </div>
        <button type="submit" class="btn btn-primary">Kayıt Ol</button>
    </form>
    <p>Zaten hesabınız var mı? <a href="login.php">Giriş Yap</a></p>
</div>

<script>
    document.getElementById('registerForm').addEventListener('submit', function (event) {
        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('confirm_password').value;

        if (password !== confirmPassword) {
            event.preventDefault();
            const errorMessage = document.createElement('div');
            errorMessage.className = 'alert alert-danger';
            errorMessage.innerText = 'Şifreler eşleşmiyor!';

            document.querySelector('.container').insertBefore(errorMessage, document.querySelector('form'));

            document.getElementById('password').value = '';
            document.getElementById('confirm_password').value = '';
        }
    });
</script>

</body>
</html>
