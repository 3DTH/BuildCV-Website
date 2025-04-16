<?php
$title = 'Thanh toán thất bại - BuildCV';
require_once 'app/views/layouts/header.php';
?>

<div class="container py-5">
    <div class="text-center">
        <i class="fas fa-times-circle text-danger" style="font-size: 64px;"></i>
        <h2 class="mt-4">Thanh toán thất bại!</h2>
        <p class="lead">Đã có lỗi xảy ra trong quá trình thanh toán. Vui lòng thử lại sau.</p>
        <a href="<?= BASE_URL ?>/package" class="btn btn-primary mt-3">Quay lại trang gói dịch vụ</a>
    </div>
</div>

<?php require_once 'app/views/layouts/footer.php'; ?>