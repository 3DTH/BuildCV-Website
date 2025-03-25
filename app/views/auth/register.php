<?php 
$title = 'Đăng ký - BuildCV';
require_once 'app/views/layouts/header.php'; 
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-body p-5">
                    <h2 class="text-center mb-4">Đăng ký tài khoản</h2>
                    
                    <form action="<?= BASE_URL ?>/auth/register" method="POST" class="needs-validation" novalidate>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="username" class="form-label">Tên đăng nhập *</label>
                                <input type="text" class="form-control" id="username" name="username" required 
                                    value="<?= isset($_SESSION['form_data']['username']) ? htmlspecialchars($_SESSION['form_data']['username']) : '' ?>">
                                <div class="invalid-feedback">
                                    Vui lòng nhập tên đăng nhập
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email *</label>
                                <input type="email" class="form-control" id="email" name="email" required
                                    value="<?= isset($_SESSION['form_data']['email']) ? htmlspecialchars($_SESSION['form_data']['email']) : '' ?>">
                                <div class="invalid-feedback">
                                    Vui lòng nhập email hợp lệ
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">Mật khẩu *</label>
                                <input type="password" class="form-control" id="password" name="password" required
                                    pattern=".{6,}" title="Mật khẩu phải có ít nhất 6 ký tự">
                                <div class="invalid-feedback">
                                    Mật khẩu phải có ít nhất 6 ký tự
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="password_confirm" class="form-label">Xác nhận mật khẩu *</label>
                                <input type="password" class="form-control" id="password_confirm" name="password_confirm" required>
                                <div class="invalid-feedback">
                                    Mật khẩu xác nhận không khớp
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="full_name" class="form-label">Họ và tên</label>
                            <input type="text" class="form-control" id="full_name" name="full_name"
                                value="<?= isset($_SESSION['form_data']['full_name']) ? htmlspecialchars($_SESSION['form_data']['full_name']) : '' ?>">
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">Số điện thoại</label>
                                <input type="tel" class="form-control" id="phone" name="phone"
                                    value="<?= isset($_SESSION['form_data']['phone']) ? htmlspecialchars($_SESSION['form_data']['phone']) : '' ?>">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="address" class="form-label">Địa chỉ</label>
                                <input type="text" class="form-control" id="address" name="address"
                                    value="<?= isset($_SESSION['form_data']['address']) ? htmlspecialchars($_SESSION['form_data']['address']) : '' ?>">
                            </div>
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="terms" name="terms" required>
                            <label class="form-check-label" for="terms">
                                Tôi đồng ý với <a href="<?= BASE_URL ?>/terms" target="_blank">điều khoản sử dụng</a> và 
                                <a href="<?= BASE_URL ?>/privacy" target="_blank">chính sách bảo mật</a>
                            </label>
                            <div class="invalid-feedback">
                                Bạn phải đồng ý với điều khoản sử dụng
                            </div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Đăng ký</button>
                        </div>

                        <div class="text-center mt-3">
                            <p class="mb-0">Đã có tài khoản? <a href="<?= BASE_URL ?>/login" class="text-decoration-none">Đăng nhập</a></p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Form validation
(function () {
    'use strict'
    var forms = document.querySelectorAll('.needs-validation')
    Array.prototype.slice.call(forms).forEach(function (form) {
        form.addEventListener('submit', function (event) {
            if (!form.checkValidity()) {
                event.preventDefault()
                event.stopPropagation()
            }

            // Kiểm tra mật khẩu xác nhận
            var password = document.getElementById('password')
            var password_confirm = document.getElementById('password_confirm')
            if (password.value !== password_confirm.value) {
                password_confirm.setCustomValidity('Mật khẩu xác nhận không khớp')
                event.preventDefault()
                event.stopPropagation()
            } else {
                password_confirm.setCustomValidity('')
            }

            form.classList.add('was-validated')
        }, false)
    })
})()
</script>

<?php 
unset($_SESSION['form_data']);
require_once 'app/views/layouts/footer.php'; 
?>