<?php
$title = 'Thanh toán thành công - BuildCV';
require_once 'app/views/layouts/header.php';
?>

<div class="container py-5">
    <div class="text-center">
        <i class="fas fa-check-circle text-success" style="font-size: 64px;"></i>
        <h2 class="mt-4">Thanh toán thành công!</h2>
        <p class="lead">Cảm ơn bạn đã nâng cấp tài khoản. Bây giờ bạn có thể sử dụng các tính năng premium.</p>
        <a href="<?= BASE_URL ?>/templates" class="btn btn-primary mt-3">Xem các mẫu CV Premium</a>
    </div>
</div>

<?php require_once 'app/views/layouts/footer.php'; ?>