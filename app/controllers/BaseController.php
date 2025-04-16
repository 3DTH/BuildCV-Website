<?php
class BaseController
{

    protected $db;

    public function __construct()
    {
        // Khởi tạo database connection nếu cần
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    protected function checkAuth()
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . '/auth/login');
            exit;
        }
    }

    protected function verifyOwnership($cv, $user_id)
    {
        if (!$cv || $cv['user_id'] != $user_id) {
            return false;
        }
        return true;
    }

    protected function jsonResponse($data, $status = 200)
    {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    protected function checkAdminAuth()
    {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            $_SESSION['error'] = 'Bạn không có quyền truy cập trang này!';
            header('Location: ' . BASE_URL . '/auth/login');
            exit;
        }
    }

    protected function error404()
    {
        http_response_code(404);
        require_once 'app/views/error/404.php';
    }

    protected function view($view, $data = [])
    {
        extract($data);
        $viewPath = 'app/views/' . $view . '.php';

        if (file_exists($viewPath)) {
            require_once $viewPath;
        } else {
            die('View not found: ' . $viewPath);
        }
    }
}
