<?php
class ProfileController {
    private $userModel;

    public function __construct() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . '/auth/login');
            exit;
        }
        $this->userModel = new User();
    }

    public function index() {
        $user = $this->userModel->getUserById($_SESSION['user_id']);
        require_once 'app/views/profile/index.php';
    }

    public function edit() {
        $user = $this->userModel->getUserById($_SESSION['user_id']);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'id' => $_SESSION['user_id'],
                'full_name' => $_POST['full_name'],
                'email' => $_POST['email'],
                'phone' => $_POST['phone'],
                'address' => $_POST['address']
            ];

            // Handle profile picture upload
            if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === 0) {
                $fileUploader = new FileUploader();
                $uploadResult = $fileUploader->uploadProfilePicture($_FILES['profile_picture']);
                
                if ($uploadResult['success']) {
                    $data['profile_picture'] = $uploadResult['filename'];
                } else {
                    $_SESSION['error'] = $uploadResult['error'];
                    header('Location: ' . BASE_URL . '/profile/edit');
                    exit;
                }
            }

            if ($this->userModel->updateProfile($data)) {
                $_SESSION['success'] = "Cập nhật thông tin thành công!";
                header('Location: ' . BASE_URL . '/profile');
                exit;
            } else {
                $_SESSION['error'] = "Có lỗi xảy ra khi cập nhật thông tin!";
            }
        }

        require_once 'app/views/profile/edit.php';
    }

    public function changePassword() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $currentPassword = $_POST['current_password'];
            $newPassword = $_POST['new_password'];
            $confirmPassword = $_POST['confirm_password'];

            if ($newPassword !== $confirmPassword) {
                $_SESSION['error'] = "Mật khẩu mới không khớp!";
                header('Location: ' . BASE_URL . '/profile/change-password');
                exit;
            }

            if ($this->userModel->changePassword($_SESSION['user_id'], $currentPassword, $newPassword)) {
                $_SESSION['success'] = "Đổi mật khẩu thành công!";
                header('Location: ' . BASE_URL . '/profile');
                exit;
            } else {
                $_SESSION['error'] = "Mật khẩu hiện tại không đúng!";
            }
        }

        require_once 'app/views/profile/change-password.php';
    }
}