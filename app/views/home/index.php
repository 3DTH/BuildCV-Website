<?php 
$title = 'BuildCV - Tạo CV Chuyên Nghiệp';
require_once 'app/views/layouts/header.php'; 
?>

<div class="hero bg-primary text-white py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="display-4 fw-bold">Tạo CV Ấn Tượng</h1>
                <p class="lead">Xây dựng CV chuyên nghiệp trong vài phút với các mẫu được thiết kế bởi chuyên gia</p>
                <a href="<?= BASE_URL ?>/templates" class="btn btn-light btn-lg">Bắt đầu ngay</a>
            </div>
            <div class="col-md-6">
                <img src="<?= BASE_URL ?>/assets/images/hero-image.png" alt="CV Builder" class="img-fluid">
            </div>
        </div>
    </div>
</div>

<div class="features py-5">
    <div class="container">
        <h2 class="text-center mb-5">Tại sao chọn BuildCV?</h2>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center">
                        <i class="fas fa-paint-brush fa-3x text-primary mb-3"></i>
                        <h5 class="card-title">Mẫu CV Đẹp</h5>
                        <p class="card-text">Nhiều mẫu CV được thiết kế chuyên nghiệp, phù hợp với mọi ngành nghề</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center">
                        <i class="fas fa-magic fa-3x text-primary mb-3"></i>
                        <h5 class="card-title">Dễ Sử Dụng</h5>
                        <p class="card-text">Giao diện thân thiện, dễ sử dụng, tạo CV chỉ trong vài phút</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center">
                        <i class="fas fa-download fa-3x text-primary mb-3"></i>
                        <h5 class="card-title">Xuất File Đẹp</h5>
                        <p class="card-text">Xuất CV với chất lượng cao, định dạng PDF chuyên nghiệp</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="templates py-5 bg-light">
    <div class="container">
        <h2 class="text-center mb-4">Mẫu CV Nổi Bật</h2>
        <div class="row g-4">
            <?php foreach ($freeTemplates as $template): ?>
            <div class="col-md-4">
                <div class="card h-100">
                    <img src="<?= BASE_URL ?>/<?= htmlspecialchars($template['thumbnail']) ?>" class="card-img-top" alt="<?= htmlspecialchars($template['name']) ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($template['name']) ?></h5>
                        <p class="card-text"><?= htmlspecialchars($template['description']) ?></p>
                        <a href="<?= BASE_URL ?>/cv/create?template=<?= $template['id'] ?>" class="btn btn-primary">Sử dụng mẫu này</a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <div class="text-center mt-4">
            <a href="<?= BASE_URL ?>/templates" class="btn btn-outline-primary">Xem tất cả mẫu</a>
        </div>
    </div>
</div>

<div class="recent-cvs py-5">
    <div class="container">
        <h2 class="text-center mb-4">CV Mới Nhất</h2>
        <div class="row g-4">
            <?php foreach ($publicCVs as $cv): ?>
            <div class="col-md-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($cv['title']) ?></h5>
                        <p class="card-text"><?= htmlspecialchars($cv['summary']) ?></p>
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">
                                <i class="fas fa-user"></i> <?= htmlspecialchars($cv['full_name']) ?>
                            </small>
                            <a href="<?= BASE_URL ?>/cv/view/<?= $cv['id'] ?>" class="btn btn-sm btn-outline-primary">Xem CV</a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<?php require_once 'app/views/layouts/footer.php'; ?>