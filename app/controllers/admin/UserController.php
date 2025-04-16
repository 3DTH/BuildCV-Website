<?php
class UserController extends BaseController {
    private $userModel;

    public function __construct() {
        parent::__construct();
        $this->checkAdminAuth();
        $this->userModel = new User();
    }

    public function index() {
        $users = $this->userModel->getAllUsers();
        $title = 'Quản lý người dùng - Admin BuildCV';
        require_once 'app/views/admin/users/index.php';
    }

    public function edit($id) {
        $user = $this->userModel->getUserById($id);
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'id' => $id,
                'full_name' => $_POST['full_name'],
                'email' => $_POST['email'],
                'phone' => $_POST['phone'],
                'address' => $_POST['address'],
                'role' => $_POST['role'],
                'status' => $_POST['status']
            ];

            if ($this->userModel->updateUserByAdmin($data)) {
                $_SESSION['success'] = "Cập nhật thông tin người dùng thành công!";
                header('Location: ' . BASE_URL . '/admin/users');
                exit;
            } else {
                $_SESSION['error'] = "Có lỗi xảy ra khi cập nhật thông tin!";
            }
        }

        $title = 'Chỉnh sửa người dùng - Admin BuildCV';
        require_once 'app/views/admin/users/edit.php';
    }

    public function delete($id) {
        if ($this->userModel->deleteUser($id)) {
            $_SESSION['success'] = "Xóa người dùng thành công!";
        } else {
            $_SESSION['error'] = "Có lỗi xảy ra khi xóa người dùng!";
        }
        header('Location: ' . BASE_URL . '/admin/users');
        exit;
    }

    public function toggleStatus($id) {
        if ($this->userModel->toggleUserStatus($id)) {
            $_SESSION['success'] = "Cập nhật trạng thái người dùng thành công!";
        } else {
            $_SESSION['error'] = "Có lỗi xảy ra khi cập nhật trạng thái!";
        }
        header('Location: ' . BASE_URL . '/admin/users');
        exit;
    }
}