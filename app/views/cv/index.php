<?php
$title = 'Quản lý CV - BuildCV';
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

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Quản lý CV của bạn</h2>
        <a href="<?= BASE_URL ?>/cv/create" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tạo CV mới
        </a>
    </div>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success">
            <?= $_SESSION['success']; ?>
            <?php unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger">
            <?= $_SESSION['error']; ?>
            <?php unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <?php if (empty($cvs)): ?>
        <div class="text-center py-5">
            <h4>Bạn chưa có CV nào</h4>
            <p>Hãy bắt đầu tạo CV đầu tiên của bạn!</p>
            <a href="<?= BASE_URL ?>/cv/create" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tạo CV ngay
            </a>
        </div>
    <?php else: ?>
        <div class="row">
            <?php foreach ($cvs as $cv): ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($cv['title']) ?></h5>
                            <p class="card-text text-muted">
                                Cập nhật: <?= date('d/m/Y', strtotime($cv['updated_at'])) ?>
                            </p>
                            <p class="card-text">
                                <?= htmlspecialchars(substr($cv['summary'], 0, 100)) ?>...
                            </p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="badge <?= $cv['is_public'] ? 'bg-success' : 'bg-secondary' ?>">
                                    <?= $cv['is_public'] ? 'Công khai' : 'Riêng tư' ?>
                                </span>
                                <div class="btn-group">
                                    <a href="<?= BASE_URL ?>/cv/edit/<?= $cv['id'] ?>" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-edit"></i> Sửa
                                    </a>
                                    <button type="button" class="btn btn-sm btn-outline-info" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#previewModal" 
                                            onclick="loadPreview(<?= $cv['id'] ?>, <?= $cv['template_id'] ?>)">
                                        <i class="fas fa-eye"></i> Xem
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-danger"
                                            onclick="deleteCV(<?= $cv['id'] ?>)">
                                        <i class="fas fa-trash"></i> Xóa
                                    </button>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<!-- Preview Modal -->
<div class="modal fade" id="previewModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Xem CV</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0">
                <div class="cv-preview-wrapper">
                    <div id="cvPreview" class="cv-preview-container">
                        <!-- CV content will be loaded here -->
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                <button type="button" class="btn btn-primary" onclick="printCV()">
                    <i class="fas fa-print me-2"></i>In CV
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    function deleteCV(cvId) {
        if (confirm('Bạn có chắc chắn muốn xóa CV này không?')) {
            window.location.href = '<?= BASE_URL ?>/cv/delete/' + cvId;
        }
    }

    function loadPreview(cvId, templateId) {
        const previewContainer = document.getElementById('cvPreview');
        if (!previewContainer) return;

        // Show loading state
        previewContainer.innerHTML = '<div class="text-center p-4"><div class="spinner-border text-primary"></div></div>';

        // Load preview content
        fetch(`${BASE_URL}/cv/renderPreview/${templateId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ cv_id: cvId })
        })
        .then(response => response.text())
        .then(html => {
            previewContainer.innerHTML = html;
        });
    }

    function printCV() {
        const printWindow = window.open('', '_blank');
        const content = document.getElementById('cvPreview').innerHTML;
        const styles = Array.from(document.styleSheets)
            .map(sheet => {
                try {
                    return Array.from(sheet.cssRules)
                        .map(rule => rule.cssText)
                        .join('\n');
                } catch (e) {
                    return '';
                }
            })
            .join('\n');

        printWindow.document.write(`
            <!DOCTYPE html>
            <html>
            <head>
                <title>Print CV</title>
                <style>${styles}</style>
            </head>
            <body>
                <div class="cv-preview-container">${content}</div>
            </body>
            </html>
        `);
        printWindow.document.close();
        printWindow.print();
    }
</script>

<?php require_once 'app/views/layouts/footer.php'; ?>