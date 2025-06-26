<?php
session_start();
include 'navbar.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

try {
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=pet_adoption', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Veritabanı bağlantı hatası: ' . $e->getMessage();
    exit();
}

$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM pets WHERE user_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id]);
$pets = $stmt->fetchAll();

if (isset($_GET['delete'])) {
    $pet_id = $_GET['delete'];

    $sql = "SELECT * FROM pets WHERE id = ? AND user_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$pet_id, $user_id]);
    $pet = $stmt->fetch();

    if ($pet) {
        $delete_sql = "DELETE FROM pets WHERE id = ?";
        $delete_stmt = $pdo->prepare($delete_sql);
        $delete_stmt->execute([$pet_id]);

        $_SESSION['success_message'] = "İlan başarıyla silindi!";
        header("Location: user_dashboard.php");
        exit();
    }
}

$success_message = '';

if (isset($_GET['edit'])) {
    $pet_id = $_GET['edit'];

    $sql = "SELECT * FROM pets WHERE id = ? AND user_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$pet_id, $user_id]);
    $pet = $stmt->fetch();

    if (!$pet) {
        echo "Geçersiz ilan ID'si veya bu ilan size ait değil!";
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = $_POST['name'];
        $species = $_POST['species'];
        $age = $_POST['age'];
        $gender = $_POST['gender'];
        $status = $_POST['status'];
        $description = $_POST['description'];
        $city = $_POST['city'];
        $image_url = $_POST['image_url'];

        if ($status == 'Sahiplendirildi') {
            $insert_sql = "INSERT INTO adopted_pets (name, species, age, gender, status, description, user_id, city, image_url) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $insert_stmt = $pdo->prepare($insert_sql);
            $insert_stmt->execute([$name, $species, $age, $gender, $status, $description, $user_id, $city, $image_url]);

            $delete_sql = "DELETE FROM pets WHERE id = ?";
            $delete_stmt = $pdo->prepare($delete_sql);
            $delete_stmt->execute([$pet_id]);

            $_SESSION['success_message'] = "İlan sahiplendirildi ve listeden kaldırıldı!";
            header("Location: user_dashboard.php");
            exit();
        } else {
            $update_sql = "UPDATE pets SET name = ?, species = ?, age = ?, gender = ?, status = ?, description = ?, city = ?, image_url = ? WHERE id = ?";
            $update_stmt = $pdo->prepare($update_sql);
            $update_stmt->execute([$name, $species, $age, $gender, $status, $description, $city, $image_url, $pet_id]);

            $_SESSION['success_message'] = "İlan başarıyla güncellendi!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
    <title>İlanlarım</title>
</head>
<body>
<div class="container mt-5">
    <h1 class="text-center">İlanlarım</h1>
    <?php if (isset($_SESSION['success_message'])): ?>
        <div class="alert alert-success">
            <?php
            echo $_SESSION['success_message'];
            unset($_SESSION['success_message']);
            ?>
        </div>
    <?php endif; ?>

    <?php if (count($pets) > 0): ?>
        <table class="table table-striped">
            <thead>
            <tr>
                <th>Adı</th>
                <th>Tür</th>
                <th>Yaş</th>
                <th>Cinsiyet</th>
                <th>Durum</th>
                <th>İşlem</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($pets as $pet_row): ?>
                <tr>
                    <td><?php echo htmlspecialchars($pet_row['name']); ?></td>
                    <td><?php echo htmlspecialchars($pet_row['species']); ?></td>
                    <td><?php echo htmlspecialchars($pet_row['age']); ?></td>
                    <td><?php echo htmlspecialchars($pet_row['gender']); ?></td>
                    <td><?php echo htmlspecialchars($pet_row['status']); ?></td>
                    <td>
                        <a href="?edit=<?php echo $pet_row['id']; ?>" class="btn btn-warning">Düzenle</a>
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal<?php echo $pet_row['id']; ?>">
                            Sil
                        </button>
                        <div class="modal fade" id="deleteModal<?php echo $pet_row['id']; ?>" tabindex="-1" aria-labelledby="deleteModalLabel<?php echo $pet_row['id']; ?>" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-body text-center" style="padding-top: 40px;">
                                        <p>
                                            "<?php echo htmlspecialchars($pet_row['name']); ?>" adlı ilanı silmek istediğinizden emin misiniz?
                                        </p>
                                    </div>
                                    <div class="modal-footer justify-content-center">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hayır</button>
                                        <a href="delete_pet.php?id=<?php echo $pet_row['id']; ?>" class="btn btn-danger">Evet</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="alert alert-info text-center mt-4" role="alert">
            <strong>Henüz Güncel Bir İlanınız Yok</strong>
        </div>
    <?php endif; ?>

    <?php if (isset($pet)): ?>
        <h2>İlanı Düzenle</h2>
        <form method="POST">
            <div class="mb-3">
                <label for="name" class="form-label">Adı:</label>
                <input type="text" id="name" name="name" class="form-control" value="<?php echo htmlspecialchars($pet['name']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="species" class="form-label">Tür:</label>
                <input type="text" id="species" name="species" class="form-control" value="<?php echo htmlspecialchars($pet['species']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="age" class="form-label">Yaş:</label>
                <input type="number" id="age" name="age" class="form-control" value="<?php echo htmlspecialchars($pet['age']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="gender" class="form-label">Cinsiyet:</label>
                <select id="gender" name="gender" class="form-select" required>
                    <option value="Erkek" <?php echo $pet['gender'] == 'Erkek' ? 'selected' : ''; ?>>Erkek</option>
                    <option value="Dişi" <?php echo $pet['gender'] == 'Dişi' ? 'selected' : ''; ?>>Dişi</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="status" class="form-label">Durum:</label>
                <select id="status" name="status" class="form-select" required>
                    <option value="Aktif" <?php echo $pet['status'] == 'Aktif' ? 'selected' : ''; ?>>Aktif</option>
                    <option value="Sahiplendirildi" <?php echo $pet['status'] == 'Sahiplendirildi' ? 'selected' : ''; ?>>Sahiplendirildi</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Açıklama:</label>
                <textarea id="description" name="description" class="form-control" rows="3"><?php echo htmlspecialchars($pet['description']); ?></textarea>
            </div>


            <div class="mb-3">
                <label for="city" class="form-label">Şehir:</label>
                <input type="text" id="city" name="city" class="form-control" value="<?php echo htmlspecialchars($pet['city']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="image_url" class="form-label">Resim URL:</label>
                <input type="text" id="image_url" name="image_url" class="form-control" value="<?php echo htmlspecialchars($pet['image_url']); ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Güncelle</button>
        </form>
    <?php endif; ?>
</div>
</body>
</html>
