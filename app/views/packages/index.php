<?php
$title = 'Gói Dịch Vụ Premium - BuildCV';
require_once 'app/views/layouts/header.php';
?>

<div class="packages-container">
    <div class="container py-5">
        <div class="text-center mb-5">
            <h1 class="display-4">Gói Dịch Vụ Premium</h1>
            <p class="lead text-muted">Nâng cấp tài khoản để sử dụng các mẫu CV premium</p>
        </div>

        <?php if (isset($activePackage) && is_array($activePackage) && !empty($activePackage)): ?>
            <div class="alert alert-info mb-4">
                <h5>Gói dịch vụ hiện tại của bạn: <?= htmlspecialchars($activePackage['name']) ?></h5>
                <p>Thời hạn: <?= date('d/m/Y', strtotime($activePackage['end_date'])) ?></p>
            </div>
        <?php endif; ?>

        <div class="row g-4 mb-5">
            <?php foreach ($packages as $package): ?>
            <div class="col-md-4">
                <div class="card h-100 package-card">
                    <div class="card-body text-center">
                        <h3 class="card-title"><?= htmlspecialchars($package['name']) ?></h3>
                        <div class="price-tag my-4">
                            <span class="currency">VND</span>
                            <span class="amount"><?= number_format($package['price'], 0, ',', '.') ?></span>
                        </div>
                        <p class="card-text"><?= htmlspecialchars($package['description']) ?></p>
                        <ul class="list-unstyled mt-3 mb-4">
                            <li><i class="fas fa-check text-success me-2"></i>Sử dụng mẫu CV premium</li>
                            <li><i class="fas fa-check text-success me-2"></i><?= $package['duration_days'] ?> ngày sử dụng</li>
                            <?php if ($package['export_limit'] === null): ?>
                            <li><i class="fas fa-check text-success me-2"></i>Không giới hạn lượt xuất PDF</li>
                            <?php else: ?>
                            <li><i class="fas fa-check text-success me-2"></i><?= $package['export_limit'] ?> lượt xuất PDF</li>
                            <?php endif; ?>
                        </ul>
                        <a href="<?= BASE_URL ?>/package/purchase/<?= $package['id'] ?>" 
                           class="btn btn-primary btn-lg w-100">
                            Mua Ngay
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<?php require_once 'app/views/layouts/footer.php'; ?>