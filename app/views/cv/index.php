<?php 
$title = 'Quản lý CV - BuildCV';
require_once 'app/views/layouts/header.php'; 
?>

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
                                    <a href="<?= BASE_URL ?>/cv/view/<?= $cv['id'] ?>" class="btn btn-sm btn-outline-info">
                                        <i class="fas fa-eye"></i> Xem
                                    </a>
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

<script>
function deleteCV(cvId) {
    if (confirm('Bạn có chắc chắn muốn xóa CV này không?')) {
        window.location.href = '<?= BASE_URL ?>/cv/delete/' + cvId;
    }
}
</script>

<?php require_once 'app/views/layouts/footer.php'; ?>