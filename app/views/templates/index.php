<?php
$title = 'Mẫu CV - BuildCV';
require_once 'app/views/layouts/header.php';
?>

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
                                    <a href="<?= BASE_URL ?>/cv/create?template=<?= $template['id'] ?>"
                                        class="btn btn-primary btn-use">
                                        <i class="fas fa-plus"></i> Sử dụng mẫu này
                                    </a>
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

<?php require_once 'app/views/layouts/footer.php'; ?>