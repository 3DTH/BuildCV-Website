<?php
$title = 'Liên hệ - BuildCV';
require_once 'app/views/layouts/header.php';
?>

<div class="contact-page">
    <div class="contact-hero">
        <div class="container">
            <h1>Liên hệ với chúng tôi</h1>
            <p class="lead">Hãy để lại thông tin, chúng tôi sẽ phản hồi sớm nhất có thể</p>
        </div>
    </div>

    <div class="contact-content">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <div class="contact-info">
                        <h3>Thông tin liên hệ</h3>
                        <ul>
                            <li><i class="fas fa-map-marker-alt"></i> Thu Duc, HCM CITY</li>
                            <li><i class="fas fa-phone"></i> (84) 123-456-789</li>
                            <li><i class="fas fa-envelope"></i> 4NTH@gmail.com</li>
                        </ul>

                        <div class="social-links">
                            <a href="#"><i class="fab fa-facebook"></i></a>
                            <a href="#"><i class="fab fa-twitter"></i></a>
                            <a href="#"><i class="fab fa-linkedin"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <form class="contact-form" method="POST" action="<?= BASE_URL ?>/contact">
                        <?php if (isset($_SESSION['success'])): ?>
                            <div class="alert alert-success">
                                <?= $_SESSION['success'] ?>
                                <?php unset($_SESSION['success']); ?>
                            </div>
                        <?php endif; ?>

                        <div class="mb-3">
                            <label for="name" class="form-label">Họ và tên</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="message" class="form-label">Nội dung</label>
                            <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Gửi tin nhắn</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'app/views/layouts/footer.php'; ?>