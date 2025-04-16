<?php
$title = 'Giới thiệu - BuildCV';
require_once 'app/views/layouts/header.php';
?>

<div class="about-page">
    <div class="about-hero">
        <div class="container">
            <h1>Về BuildCV</h1>
            <p class="lead">Nền tảng tạo CV chuyên nghiệp hàng đầu Việt Nam</p>
        </div>
    </div>

    <div class="about-content">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h2>Sứ mệnh của chúng tôi</h2>
                    <p>BuildCV ra đời với sứ mệnh giúp mọi người tạo ra những CV chuyên nghiệp, ấn tượng một cách dễ dàng và nhanh chóng.</p>
                    
                    <h2>Giá trị cốt lõi</h2>
                    <ul class="core-values">
                        <li><i class="fas fa-check-circle"></i> Chất lượng và chuyên nghiệp</li>
                        <li><i class="fas fa-check-circle"></i> Dễ dàng sử dụng</li>
                        <li><i class="fas fa-check-circle"></i> Đổi mới và sáng tạo</li>
                        <li><i class="fas fa-check-circle"></i> Hỗ trợ khách hàng tận tâm</li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <img src="<?= BASE_URL ?>/assets/images/hero.png" alt="About BuildCV" class="img-fluid rounded">
                </div>
            </div>

        
        </div>
    </div>
</div>

<?php require_once 'app/views/layouts/footer.php'; ?>