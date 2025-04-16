<footer class="footer mt-5 py-4 bg-light">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <h5>BuildCV</h5>
                    <p class="text-white">Nền tảng tạo CV chuyên nghiệp hàng đầu Việt Nam</p>
                    <div class="social-links">
                        <a href="#" class="text-white me-2"><i class="fab fa-facebook"></i></a>
                        <a href="#" class="text-white me-2"><i class="fab fa-linkedin"></i></a>
                        <a href="#" class="text-white me-2"><i class="fab fa-twitter"></i></a>
                    </div>
                </div>
                <div class="col-md-2 mb-3">
                    <h6>Liên kết</h6>
                    <ul class="list-unstyled">
                        <li><a href="/about" class="text-white">Giới thiệu</a></li>
                        <li><a href="/contact" class="text-white">Liên hệ</a></li>
                        <li><a href="/pricing" class="text-white">Bảng giá</a></li>
                        <li><a href="/templates" class="text-white">Mẫu CV</a></li>
                    </ul>
                </div>
                <div class="col-md-3 mb-3">
                    <h6>Hỗ trợ</h6>
                    <ul class="list-unstyled">
                        <li><a href="/faq" class="text-white">FAQ</a></li>
                        <li><a href="/privacy" class="text-white">Chính sách bảo mật</a></li>
                        <li><a href="/terms" class="text-white">Điều khoản sử dụng</a></li>
                    </ul>
                </div>
                <div class="col-md-3 mb-3">
                    <h6>Liên hệ</h6>
                    <ul class="list-unstyled text-white">
                        <li><i class="fas fa-envelope me-2"></i> 4nth@buildcv.vn</li>
                        <li><i class="fas fa-phone me-2"></i> (84) 123 456 789</li>
                        <li><i class="fas fa-map-marker-alt me-2"></i> TP Hồ Chí Minh, Việt Nam</li>
                    </ul>
                </div>
            </div>
            <hr>
            <div class="text-center text-white">
                <small>&copy; <?= date('Y') ?> BuildCV. All rights reserved.</small>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Define BASE_URL -->
    <script>
        const BASE_URL = '<?= BASE_URL ?>';
    </script>
    <!-- Custom JS -->
    <script src="<?= BASE_URL ?>/assets/js/main.js"></script>
    <script src="<?= BASE_URL ?>/assets/js/cv-editor.js"></script>
    <script src="<?= BASE_URL ?>/assets/js/templates.js" defer></script>
</body>
</html>