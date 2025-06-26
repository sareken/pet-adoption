<?php
session_start();
include 'navbar.php';
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Evcil Hayvan Sahiplendirme</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
</head>
<body>
<div class="special"></div>
<div class="container mt-5">
    <h1 class="text-center mb-4">Evcil Hayvan Sahiplendirme</h1>
    <div class="row justify-content-center">

        <div class="col-md-3 pets-card">
            <div class="card text-center">
                <div class="card-body equal-height">
                    <i class="fas fa-dog fa-3x text-primary mb-3"></i>
                    <h5 class="card-title">Köpekler</h5>
                    <p class="card-text">Köpekleri görüntüleyin.</p>
                    <a href="pets.php?type=Köpek" class="btn btn-primary mt-auto">Göz At</a>
                </div>
            </div>
        </div>

        <div class="col-md-3 pets-card">
            <div class="card text-center">
                <div class="card-body equal-height">
                    <i class="fas fa-cat fa-3x text-success mb-3"></i>
                    <h5 class="card-title">Kediler</h5>
                    <p class="card-text">Kedileri görüntüleyin.</p>
                    <a href="pets.php?type=Kedi" class="btn btn-success mt-auto">Göz At</a>
                </div>
            </div>
        </div>

        <div class="col-md-3 pets-card">
            <div class="card text-center">
                <div class="card-body equal-height">
                    <i class="fas fa-paw fa-3x text-warning mb-3"></i>
                    <h5 class="card-title">Diğerleri</h5>
                    <p class="card-text">Diğer evcil hayvanları görüntüleyin.</p>
                    <a href="pets.php?type=Diğerleri" class="btn btn-warning mt-auto">Göz At</a>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
