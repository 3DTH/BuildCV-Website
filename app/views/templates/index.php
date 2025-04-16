<?php
$title = 'Mẫu CV - BuildCV';
require_once 'app/views/layouts/header.php';
?>
<!-- Add this after the templates-header section -->
<?php if (isset($_SESSION['user_id'])): ?>
    <?php $activePackage = $this->packageModel->getUserActivePackage($_SESSION['user_id']); ?>
    <?php if ($activePackage): ?>
        <div class="alert alert-info mb-4">
            <h5>Gói Premium của bạn: <?= htmlspecialchars($activePackage['name']) ?></h5>
            <p>Thời hạn sử dụng đến: <?= date('d/m/Y', strtotime($activePackage['end_date'])) ?></p>
        </div>
    <?php endif; ?>
<?php endif; ?>

<div class="templates-container">
    <div class="container py-5">
        <!-- Header Section -->
        <div class="templates-header text-center mb-5">
            <h1 class="display-4 mb-3">Chọn mẫu CV ấn tượng</h1>
            <p class="lead text-muted">Khởi đầu sự nghiệp với những mẫu CV chuyên nghiệp</p>
        </div>

        <!-- Filter Section -->
        <div class="templates-filter mb-4">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="search-box">
                        <input type="text" class="form-control" placeholder="Tìm kiếm mẫu CV...">
                        <i class="fas fa-search"></i>
                    </div>
                </div>
                <div class="col-md-6 text-md-end">
                    <div class="btn-group">
                        <button class="btn btn-filter active" data-filter="all">Tất cả</button>
                        <button class="btn btn-filter" data-filter="free">Miễn phí</button>
                        <button class="btn btn-filter" data-filter="premium">Premium</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Templates Grid -->
        <div class="templates-grid">
            <div class="row g-4">
                <?php foreach ($templates as $template): ?>
                    <div class="col-md-4">
                        <div class="template-card <?= $template['is_premium'] ? 'premium' : 'free' ?>">
                            <div class="template-preview">
                                <img src="<?= BASE_URL ?>/assets/images/<?= htmlspecialchars($template['thumbnail']) ?>"
                                    alt="<?= htmlspecialchars($template['name']) ?>" class="img-fluid">
                                <?php if ($template['is_premium']): ?>
                                    <div class="premium-badge">
                                        <i class="fas fa-crown"></i> Premium
                                    </div>
                                <?php endif; ?>
                                <div class="template-actions">
                                    <?php if ($template['is_premium'] && !$hasPremiumAccess): ?>
                                        <button type="button" class="btn btn-warning btn-upgrade" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#upgradePremiumModal">
                                            <i class="fas fa-crown"></i> Nâng cấp Premium
                                        </button>
                                    <?php else: ?>
                                        <a href="<?= BASE_URL ?>/cv/create?template=<?= $template['id'] ?>"
                                            class="btn btn-primary btn-use">
                                            <i class="fas fa-plus"></i> Sử dụng mẫu này
                                        </a>
                                    <?php endif; ?>
                                    <button type="button" class="btn btn-light btn-preview" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#templatePreviewModal"
                                        data-template-id="<?= $template['id'] ?>">
                                        <i class="fas fa-eye"></i> Xem trước
                                    </button>
                                </div>
                            </div>
                            <div class="template-info">
                                <h5 class="template-name"><?= htmlspecialchars($template['name']) ?></h5>
                                <p class="template-description"><?= htmlspecialchars($template['description']) ?></p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<!-- Template Preview Modal -->
<div class="modal fade" id="templatePreviewModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title">Xem trước mẫu CV</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="template-preview-content"></div>
            </div>
        </div>
    </div>
</div>

<!-- Thêm modal nâng cấp Premium -->
<div class="modal fade" id="upgradePremiumModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nâng cấp tài khoản Premium</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="premium-packages">
                    <div class="package-item">
                        <h4>Gói Premium 3 Ngày</h4>
                        <p class="price">5.000đ</p>
                        <a href="<?= BASE_URL ?>/payment/checkout/1" class="btn btn-primary">Chọn gói</a>
                    </div>
                    <div class="package-item">
                        <h4>Gói Premium 30 Ngày</h4>
                        <p class="price">20.000đ</p>
                        <a href="<?= BASE_URL ?>/payment/checkout/2" class="btn btn-primary">Chọn gói</a>
                    </div>
                    <div class="package-item">
                        <h4>Gói Premium 90 Ngày</h4>
                        <p class="price">50.000đ</p>
                        <a href="<?= BASE_URL ?>/payment/checkout/3" class="btn btn-primary">Chọn gói</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'app/views/layouts/footer.php'; ?>