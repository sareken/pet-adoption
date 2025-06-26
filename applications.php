<?php
session_start();
include 'navbar.php';
if (!isset($_SESSION['user_id'])) {
    $_SESSION['redirect_to'] = $_SERVER['REQUEST_URI'];
    header('Location: login.php');
    exit();
}

$conn = new mysqli('127.0.0.1', 'root', '', 'pet_adoption');

if ($conn->connect_error) {
    die("Bağlantı hatası: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id'];
$sql = "

   SELECT a.id AS application_id, a.request_date, a.status, a.message,
           p.name AS pet_name, p.species AS pet_species, p.image_url AS pet_image, u.username AS applicant_username
    FROM adoption_requests a
    JOIN pets p ON a.pet_id = p.id
    JOIN users u ON a.user_id = u.id
    WHERE p.user_id = ?
    ORDER BY a.request_date DESC
";

$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$applications_result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
    <title>Başvurular</title>
    <style>
        .application-card {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
            background-color: #f9f9f9;
        }

        .application-card h5 {
            margin-top: 0;
            font-size: 1.2rem;
            font-weight: bold;
        }

        .application-card p {
            margin: 10px 0;
            font-size: 1rem;
            line-height: 1.5;
        }

        .application-card small {
            color: #888;
        }

        .status {
            font-weight: bold;
        }

        .status.pending {
            color: #16bd5e;
        }

        .status.approved {
            color: green;
        }

        .status.rejected {
            color: red;
        }
    </style>
</head>

<body>
<div class="container mt-5">
    <h1 class="text-center">Başvurular</h1>

    <?php if ($applications_result->num_rows > 0): ?>
        <?php while ($row = $applications_result->fetch_assoc()): ?>
            <div class="application-card mb-4">
                <h5 class="application-header"><?php echo htmlspecialchars($row['pet_name']); ?> - <?php echo htmlspecialchars($row['pet_species']); ?></h5>
                <?php if (!empty($row['pet_image'])): ?>
                    <img src="<?php echo htmlspecialchars($row['pet_image']); ?>" alt="Pet Image" class="img-fluid mb-3" style="max-width: 200px;">
                <?php endif; ?>
                <p><strong>Başvuru Yapan Kullanıcı:</strong> <?php echo htmlspecialchars($row['applicant_username']); ?></p>
                <p><strong>Başvuru Tarihi:</strong> <?php echo $row['request_date']; ?></p>
                <p><strong>Durum:</strong> <span class="status <?php echo $row['status']; ?>"><?php echo ucfirst($row['status']); ?></span></p>
                <p><strong>Mesaj:</strong> <?php echo htmlspecialchars($row['message']); ?></p>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>Henüz başvuru yapılmamış.</p>
    <?php endif; ?>
</div>

</body>

</html>

<?php
$conn->close();
?>
