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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $comment = $_POST['comment'];
    $rating = $_POST['rating'];

    $stmt = $conn->prepare("INSERT INTO comments (user_id, comment, rating, created_at) VALUES (?, ?, ?, NOW())");
    $stmt->bind_param('iss', $user_id, $comment, $rating);

    if ($stmt->execute()) {
        $message = "Yorum başarıyla eklendi!";
    } else {
        $message = "Hata: " . $stmt->error;
    }
    $stmt->close();
}

$sql = "SELECT c.comment, c.rating, c.created_at, u.username FROM comments c JOIN users u ON c.user_id = u.id ORDER BY c.created_at DESC";
$comments_result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
    <title>Yorum Yap</title>
    <style>
        .star-rating {
            display: inline-block;
            cursor: pointer;
        }

        .star {
            font-size: 2rem;
            color: #ccc;
        }

        .star.selected,
        .star:hover {
            color: gold;
        }

        .star-rating span {
            transition: color 0.3s;
        }

        .comment-card {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
            background-color: #f9f9f9;
        }

        .comment-card h5 {
            margin-top: 0;
            font-size: 1.2rem;
            font-weight: bold;
        }

        .comment-card p {
            margin: 10px 0;
            font-size: 1rem;
            line-height: 1.5;
        }

        .comment-card small {
            color: #888;
        }
    </style>
</head>

<body>
<div class="container mt-5">
    <h1 class="text-center">Yorum Yap</h1>

    <?php if (isset($message)): ?>
        <div class="alert alert-info"><?php echo $message; ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="mb-3">
            <label for="rating" class="form-label">Yıldız Değerlendirme:</label>
            <div class="star-rating" id="rating">
                <span class="star" data-value="1">&#9733;</span>
                <span class="star" data-value="2">&#9733;</span>
                <span class="star" data-value="3">&#9733;</span>
                <span class="star" data-value="4">&#9733;</span>
                <span class="star" data-value="5">&#9733;</span>
            </div>
            <input type="hidden" name="rating" id="selected-rating" value="">
        </div>
        <div class="mb-3">
            <label for="comment" class="form-label">Yorum:</label>
            <textarea id="comment" name="comment" class="form-control" rows="5" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary w-100">Gönder</button>
    </form>

    <hr>

    <h3 class="text-center">Kullanıcı Yorumları</h3>

    <?php if ($comments_result->num_rows > 0): ?>
        <?php while ($row = $comments_result->fetch_assoc()): ?>
            <div class="comment-card mb-4">
                <h5 class="comment-header"><?php echo htmlspecialchars($row['username']); ?></h5>
                <p>Değerlendirme: <?php echo str_repeat('⭐', $row['rating']); ?></p>
                <p><?php echo htmlspecialchars($row['comment']); ?></p>
                <p><small>Tarih: <?php echo $row['created_at']; ?></small></p>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>Henüz yorum yapılmamış.</p>
    <?php endif; ?>
</div>

<script>
    document.querySelectorAll('.star').forEach(star => {
        star.addEventListener('click', function () {
            const rating = this.getAttribute('data-value');
            document.getElementById('selected-rating').value = rating;
            updateStarColors(rating);
        });

        star.addEventListener('mouseover', function () {
            const rating = this.getAttribute('data-value');
            updateStarColors(rating);
        });

        star.addEventListener('mouseout', function () {
            const currentRating = document.getElementById('selected-rating').value;
            updateStarColors(currentRating);
        });
    });

    function updateStarColors(rating) {
        document.querySelectorAll('.star').forEach(star => {
            if (star.getAttribute('data-value') <= rating) {
                star.classList.add('selected');
            } else {
                star.classList.remove('selected');
            }
        });
    }
</script>

</body>

</html>
