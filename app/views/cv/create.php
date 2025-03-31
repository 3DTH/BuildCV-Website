<?php
$title = 'Tạo CV mới - BuildCV';
require_once 'app/views/layouts/header.php';
?>


<div class="cv-builder-container">
    <div class="container-fluid py-4">
        <div class="row">
            <!-- Template Preview Section -->
            <div class="col-md-4">
                <div class="card shadow mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-file-alt me-2"></i>Mẫu CV đã chọn
                        </h5>
                    </div>
                    <div class="card-body">
                        <?php if (isset($selectedTemplate)): ?>
                            <div class="selected-template">
                                <img src="<?= BASE_URL ?>/assets/images/<?= htmlspecialchars($selectedTemplate['thumbnail']) ?>"
                                    class="img-fluid rounded mb-3" alt="Template Preview">
                                <h5 class="template-name"><?= htmlspecialchars($selectedTemplate['name']) ?></h5>
                                <p class="template-description text-muted">
                                    <?= htmlspecialchars($selectedTemplate['description']) ?>
                                </p>
                            </div>
                        <?php else: ?>
                            <div class="text-center text-muted py-5">
                                <i class="fas fa-file-alt fa-3x mb-3"></i>
                                <p>Vui lòng chọn mẫu CV từ danh sách bên dưới</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Template Selection -->
                <div class="card shadow">
                    <div class="card-header bg-white">
                        <h5 class="card-title mb-0">Chọn mẫu CV</h5>
                    </div>
                    <div class="card-body template-list">
                        <?php foreach ($templates as $template): ?>
                            <div class="template-item <?= $selectedTemplateId == $template['id'] ? 'active' : '' ?>"
                                data-template-id="<?= $template['id'] ?>">
                                <img src="<?= BASE_URL ?>/assets/images/<?= htmlspecialchars($template['thumbnail']) ?>"
                                    class="template-thumb" alt="<?= htmlspecialchars($template['name']) ?>">
                                <div class="template-info">
                                    <h6 class="template-name">
                                        <?= htmlspecialchars($template['name']) ?>
                                        <?php if ($template['is_premium']): ?>
                                            <span class="badge bg-warning">Premium</span>
                                        <?php endif; ?>
                                    </h6>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- CV Form Section -->
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header bg-white">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-edit me-2"></i>Thông tin CV
                        </h5>
                    </div>
                    <div class="card-body">
                        <?php if (isset($_SESSION['error'])): ?>
                            <div class="alert alert-danger alert-dismissible fade show">
                                <?= $_SESSION['error']; ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                            <?php unset($_SESSION['error']); ?>
                        <?php endif; ?>

                        <form action="<?= BASE_URL ?>/cv/create" method="POST" id="cvForm">
                            <input type="hidden" name="template_id" id="template_id"
                                value="<?= $selectedTemplateId ?? '' ?>" required>

                            <div class="mb-4">
                                <label for="title" class="form-label">Tiêu đề CV</label>
                                <input type="text" class="form-control form-control-lg"
                                    id="title" name="title" required
                                    placeholder="VD: CV Chuyên viên IT">
                            </div>

                            <div class="mb-4">
                                <label for="summary" class="form-label">Tóm tắt bản thân</label>
                                <textarea class="form-control" id="summary" name="summary"
                                    rows="4" placeholder="Mô tả ngắn gọn về bản thân và mục tiêu nghề nghiệp của bạn"></textarea>
                            </div>
                            
                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="is_public" name="is_public">
                                <label class="form-check-label" for="is_public">Công khai CV này</label>
                                <div class="form-text">CV công khai có thể được tìm thấy và xem bởi mọi người.</div>
                            </div>


                            <div class="d-flex justify-content-between">
                                <a href="<?= BASE_URL ?>/cv" class="btn btn-light">
                                    <i class="fas fa-arrow-left me-2"></i>Quay lại
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-check me-2"></i>Tạo CV
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'app/views/layouts/footer.php'; ?>