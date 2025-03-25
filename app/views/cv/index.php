<?php 
$title = 'CV của tôi - BuildCV';
require_once 'app/views/layouts/header.php'; 
?>

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>CV của tôi</h2>
        <a href="/cv/create" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tạo CV mới
        </a>
    </div>

    <?php if (empty($cvs)): ?>
    <div class="text-center py-5">
        <img src="/assets/images/empty-cv.png" alt="No CVs" class="img-fluid mb-4" style="max-width: 200px;">
        <h3>Bạn chưa có CV nào</h3>
        <p class="text-muted">Hãy bắt đầu tạo CV đầu tiên của bạn!</p>
        <a href="/cv/create" class="btn btn-primary btn-lg">
            <i class="fas fa-plus"></i> Tạo CV ngay
        </a>
    </div>
    <?php else: ?>
    <div class="row g-4">
        <?php foreach ($cvs as $cv): ?>
        <div class="col-md-6 col-lg-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title"><?= htmlspecialchars($cv['title']) ?></h5>
                    <p class="card-text text-muted">
                        <small>
                            <i class="fas fa-palette"></i> <?= htmlspecialchars($cv['template_name']) ?>
                            <br>
                            <i class="fas fa-clock"></i> Cập nhật: <?= date('d/m/Y', strtotime($cv['updated_at'])) ?>
                        </small>
                    </p>
                    <p class="card-text">
                        <?= htmlspecialchars(substr($cv['summary'], 0, 100)) ?>...
                    </p>
                    
                    <div class="cv-status mb-3">
                        <?php if ($cv['is_public']): ?>
                            <span class="badge bg-success">
                                <i class="fas fa-globe"></i> Công khai
                            </span>
                        <?php else: ?>
                            <span class="badge bg-secondary">
                                <i class="fas fa-lock"></i> Riêng tư
                            </span>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-top-0">
                    <div class="btn-group w-100">
                        <a href="/cv/edit/<?= $cv['id'] ?>" class="btn btn-outline-primary">
                            <i class="fas fa-edit"></i> Chỉnh sửa
                        </a>
                        <a href="/cv/view/<?= $cv['id'] ?>" class="btn btn-outline-success">
                            <i class="fas fa-eye"></i> Xem
                        </a>
                        <button type="button" class="btn btn-outline-danger" 
                                onclick="confirmDelete(<?= $cv['id'] ?>)">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>

<!-- Modal Xác nhận xóa -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Xác nhận xóa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Bạn có chắc chắn muốn xóa CV này không? Hành động này không thể hoàn tác.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <form id="deleteForm" action="" method="POST" style="display: inline;">
                    <button type="submit" class="btn btn-danger">Xóa</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function confirmDelete(cvId) {
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    document.getElementById('deleteForm').action = `/cv/delete/${cvId}`;
    modal.show();
}
</script>

<?php require_once 'app/views/layouts/footer.php'; ?>