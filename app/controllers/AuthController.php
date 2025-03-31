<?php
class AuthController {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'username' => $_POST['username'],
                'email' => $_POST['email'],
                'password' => $_POST['password'],
                'full_name' => $_POST['full_name'] ?? '',
                'phone' => $_POST['phone'] ?? '',
                'address' => $_POST['address'] ?? ''
            ];

            // Validate password confirmation
            if ($_POST['password'] !== $_POST['password_confirm']) {
                $_SESSION['error'] = "Mật khẩu xác nhận không khớp!";
                $_SESSION['form_data'] = $data;
                header('Location: ' . BASE_URL . '/auth/register');
                exit;
            }
            
            $user_id = $this->userModel->register($data);
            if ($user_id) {
                $_SESSION['success'] = "Đăng ký thành công! Vui lòng đăng nhập.";
                header('Location: ' . BASE_URL . '/auth/login');
                exit;
            } else {
                $_SESSION['error'] = "Tên đăng nhập hoặc email đã tồn tại!";
                $_SESSION['form_data'] = $data;
                header('Location: ' . BASE_URL . '/auth/register');
                exit;
            }
        }

        require_once 'app/views/auth/register.php';
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username']); // Thêm trim() để loại bỏ khoảng trắng
            $password = $_POST['password'];
            
            if (empty($username) || empty($password)) {
                $_SESSION['error'] = "Vui lòng nhập đầy đủ thông tin!";
                header('Location: ' . BASE_URL . '/auth/login');
                exit;
            }
            
            $user = $this->userModel->login($username, $password);
            if ($user) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];
                
                if (isset($_POST['remember'])) {
                    // Xử lý remember me nếu được chọn
                    setcookie('remember_token', $user['id'], time() + (86400 * 30), '/'); // 30 ngày
                }
                
                // Chuyển hướng đến trang dashboard hoặc trang trước đó
                $redirect = $_SESSION['redirect_url'] ?? ($user['role'] === 'admin' ? '/dashboard' : '/');
                unset($_SESSION['redirect_url']);
                header("Location: " . BASE_URL . $redirect);
                exit;
            } else {
                $_SESSION['error'] = "Tên đăng nhập hoặc mật khẩu không đúng!";
                $_SESSION['form_data']['username'] = $username; // Giữ lại username
                header('Location: ' . BASE_URL . '/auth/login');
                exit;
            }
        }

        require_once 'app/views/auth/login.php';
    }

    public function logout() {
        // Xóa cookie nếu có
        if (isset($_COOKIE['remember_token'])) {
            setcookie('remember_token', '', time() - 3600, '/');
        }

        // Xóa toàn bộ session
        session_destroy();
        
        // Chuyển hướng về trang chủ với BASE_URL
        header('Location: ' . BASE_URL . '/');
        exit;
    }

    public function forgotPassword() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];
            
            // TODO: Implement password reset functionality
            // 1. Generate reset token
            // 2. Save token to database with expiration
            // 3. Send reset email
            
            $_SESSION['success'] = "Nếu email tồn tại trong hệ thống, bạn sẽ nhận được hướng dẫn đặt lại mật khẩu.";
            header('Location: ' . BASE_URL . '/auth/login');
            exit;
        }

        require_once 'app/views/auth/forgot-password.php';
    }

    public function resetPassword($token) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $password = $_POST['password'];
            $password_confirm = $_POST['password_confirm'];
            
            if ($password !== $password_confirm) {
                $_SESSION['error'] = "Mật khẩu xác nhận không khớp!";
                header('Location: ' . BASE_URL . '/auth/reset-password/' . $token);
                exit;
            }
            
            // TODO: Implement password reset
            // 1. Verify token is valid and not expired
            // 2. Update user password
            // 3. Delete token
            
            $_SESSION['success'] = "Mật khẩu đã được đặt lại thành công! Vui lòng đăng nhập.";
            header('Location: ' . BASE_URL . '/auth/login');
            exit;
        }

        // TODO: Verify token is valid before showing form
        require_once 'app/views/auth/reset-password.php';
    }
}