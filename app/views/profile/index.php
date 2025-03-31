<?php 
$title = 'Hồ sơ của tôi - BuildCV';
require_once 'app/views/layouts/header.php'; 
?>

<div class="profile-container">
    <div class="container py-5">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3">
                <div class="profile-sidebar">
                    <div class="card">
                        <div class="card-body text-center">
                            <div class="profile-picture-wrapper mb-4">
                                <img src="<?= BASE_URL ?>/uploads/profile_pictures/<?= $user['profile_picture'] ?: 'default.jpg' ?>" 
                                     class="profile-picture" alt="Profile Picture">
                            </div>
                            <h4 class="profile-name"><?= htmlspecialchars($user['full_name']) ?></h4>
                            <p class="profile-email"><?= htmlspecialchars($user['email']) ?></p>
                            <div class="d-grid">
                                <a href="<?= BASE_URL ?>/profile/edit" class="btn btn-primary btn-edit-profile">
                                    <i class="fas fa-edit me-2"></i> Chỉnh sửa thông tin
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="card profile-nav mt-4">
                        <div class="list-group list-group-flush">
                            <a href="<?= BASE_URL ?>/profile" class="list-group-item list-group-item-action active">
                                <i class="fas fa-user me-2"></i> Thông tin cá nhân
                            </a>
                            <a href="<?= BASE_URL ?>/profile/change-password" class="list-group-item list-group-item-action">
                                <i class="fas fa-key me-2"></i> Đổi mật khẩu
                            </a>
                            <a href="<?= BASE_URL ?>/cv" class="list-group-item list-group-item-action">
                                <i class="fas fa-file-alt me-2"></i> CV của tôi
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main content -->
            <div class="col-md-9">
                <div class="profile-content">
                    <?php if (isset($_SESSION['success'])): ?>
                        <div class="alert alert-success alert-dismissible fade show">
                            <?= $_SESSION['success'] ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                        <?php unset($_SESSION['success']); ?>
                    <?php endif; ?>

                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">
                                <i class="fas fa-user-circle me-2"></i>
                                Thông tin cá nhân
                            </h5>
                        </div>
                        <div class="card-body profile-info">
                            <div class="row info-item">
                                <div class="col-md-3">
                                    <i class="fas fa-user me-2"></i>
                                    <span class="text-muted">Họ và tên</span>
                                </div>
                                <div class="col-md-9"><?= htmlspecialchars($user['full_name']) ?></div>
                            </div>
                            <div class="row info-item">
                                <div class="col-md-3">
                                    <i class="fas fa-envelope me-2"></i>
                                    <span class="text-muted">Email</span>
                                </div>
                                <div class="col-md-9"><?= htmlspecialchars($user['email']) ?></div>
                            </div>
                            <div class="row info-item">
                                <div class="col-md-3">
                                    <i class="fas fa-phone me-2"></i>
                                    <span class="text-muted">Số điện thoại</span>
                                </div>
                                <div class="col-md-9"><?= htmlspecialchars($user['phone']) ?></div>
                            </div>
                            <div class="row info-item">
                                <div class="col-md-3">
                                    <i class="fas fa-map-marker-alt me-2"></i>
                                    <span class="text-muted">Địa chỉ</span>
                                </div>
                                <div class="col-md-9"><?= htmlspecialchars($user['address']) ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'app/views/layouts/footer.php'; ?>