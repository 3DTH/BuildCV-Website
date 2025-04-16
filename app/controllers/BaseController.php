<?php
class BaseController {
    protected function checkAuth() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . '/auth/login');
            exit;
        }
    }

    protected function verifyOwnership($cv, $user_id) {
        if (!$cv || $cv['user_id'] != $user_id) {
            return false;
        }
        return true;
    }

    protected function jsonResponse($data, $status = 200) {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    protected function error404() {
        http_response_code(404);
        require_once 'app/views/error/404.php';
    }
}