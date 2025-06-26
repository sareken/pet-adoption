<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid ">
        <a class="navbar-brand" href="index.php">Evcil Hayvan Sahiplendirme</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="pets.php">İlanlar</a>
                </li>

                <?php if (isset($_SESSION['user_id'])): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="user_dashboard.php">İlanlarım</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="adopted_pets.php">Geçmiş İlanlarım</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="add_pet.php">İlan Ver</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="edit_profile.php">Hesabım</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="applications.php">Başvurular</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="comment.php">Yorum Ekle</a>
                    </li>
                    <li class="nav-item">
                        <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#logoutModal">Çıkış Yap</button>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="login.php">Giriş Yap</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="register.php">Kayıt Ol</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="login.php?redirect_to=add_pet.php">İlan Ver</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="login.php?redirect_to=comment.php">Yorum Ekle</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<!-- Modal -->
<div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body text-center" style="padding-top: 40px;">
                <p>Çıkış yapmak istediğinizden emin misiniz?</p>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hayır</button>
                <a href="logout.php" class="btn btn-warning">Evet</a>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>