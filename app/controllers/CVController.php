<?php
class CvController {
    private $cvModel;
    private $userModel;
    private $templateModel;

    public function __construct() {
        // Kiểm tra đăng nhập cho tất cả các action trừ viewPublic
        if (!isset($_SESSION['user_id']) && $_GET['action'] !== 'viewPublic') {
            header('Location: ' . BASE_URL . '/auth/login');
            exit;
        }

        $this->cvModel = new CV();
        $this->userModel = new User();
        $this->templateModel = new Template();
    }

    public function index() {
        $user_id = $_SESSION['user_id'];
        $cvs = $this->cvModel->getUserCVs($user_id);
        require_once 'app/views/cv/index.php';
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user_id = $_SESSION['user_id'];
            $data = [
                'user_id' => $user_id,
                'template_id' => $_POST['template_id'],
                'title' => $_POST['title'],
                'summary' => $_POST['summary'] ?? '',
                'is_public' => isset($_POST['is_public']) ? 1 : 0
            ];

            $cv_id = $this->cvModel->createCV($data);
            if ($cv_id) {
                $_SESSION['success'] = "CV đã được tạo thành công!";
                header("Location: " . BASE_URL . "/cv/edit/{$cv_id}");
                exit;
            } else {
                $_SESSION['error'] = "Có lỗi xảy ra khi tạo CV!";
            }
        }

        $templates = $this->templateModel->getAllTemplates();
        require_once 'app/views/cv/create.php';
    }

    public function edit($cv_id) {
        $user_id = $_SESSION['user_id'];
        $cv = $this->cvModel->getCVById($cv_id);

        // Kiểm tra quyền sở hữu CV
        if (!$cv || $cv['user_id'] != $user_id) {
            $_SESSION['error'] = "Bạn không có quyền chỉnh sửa CV này!";
            header('Location: ' . BASE_URL . '/cv');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Cập nhật thông tin cơ bản của CV
            $data = [
                'user_id' => $user_id,
                'template_id' => $_POST['template_id'],
                'title' => $_POST['title'],
                'summary' => $_POST['summary'] ?? '',
                'is_public' => isset($_POST['is_public']) ? 1 : 0
            ];

            if ($this->cvModel->updateCV($cv_id, $data)) {
                $_SESSION['success'] = "CV đã được cập nhật thành công!";
            } else {
                $_SESSION['error'] = "Có lỗi xảy ra khi cập nhật CV!";
            }
        }

        // Lấy dữ liệu cho form edit
        $templates = $this->templateModel->getAllTemplates();
        $education = $this->cvModel->getEducation($cv_id);
        $experiences = $this->cvModel->getExperiences($cv_id);
        $skills = $this->cvModel->getSkills($cv_id);

        require_once 'app/views/cv/edit.php';
    }

    public function delete($cv_id) {
        $user_id = $_SESSION['user_id'];
        
        if ($this->cvModel->deleteCV($cv_id, $user_id)) {
            $_SESSION['success'] = "CV đã được xóa thành công!";
        } else {
            $_SESSION['error'] = "Có lỗi xảy ra khi xóa CV!";
        }
        
        header('Location: ' . BASE_URL . '/cv');
        exit;
    }

    public function viewPublic($cv_id) {
        $cv = $this->cvModel->getCVById($cv_id);
        
        if (!$cv || !$cv['is_public']) {
            $this->error404();
            return;
        }

        $education = $this->cvModel->getEducation($cv_id);
        $experiences = $this->cvModel->getExperiences($cv_id);
        $skills = $this->cvModel->getSkills($cv_id);

        require_once 'app/views/cv/view.php';
    }

    // API endpoints for AJAX requests
    public function addEducation() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
            return;
        }

        $cv_id = $_POST['cv_id'];
        $user_id = $_SESSION['user_id'];
        
        // Verify ownership
        $cv = $this->cvModel->getCVById($cv_id);
        if (!$cv || $cv['user_id'] != $user_id) {
            http_response_code(403);
            echo json_encode(['error' => 'Unauthorized']);
            return;
        }

        $data = [
            'institution' => $_POST['institution'],
            'degree' => $_POST['degree'],
            'field_of_study' => $_POST['field_of_study'],
            'start_date' => $_POST['start_date'],
            'end_date' => $_POST['end_date'],
            'description' => $_POST['description'],
            'order_index' => $_POST['order_index'] ?? 0
        ];

        if ($this->cvModel->addEducation($cv_id, $data)) {
            echo json_encode(['success' => true]);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to add education']);
        }
    }

    public function addExperience() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
            return;
        }

        $cv_id = $_POST['cv_id'];
        $user_id = $_SESSION['user_id'];
        
        // Verify ownership
        $cv = $this->cvModel->getCVById($cv_id);
        if (!$cv || $cv['user_id'] != $user_id) {
            http_response_code(403);
            echo json_encode(['error' => 'Unauthorized']);
            return;
        }

        $data = [
            'company' => $_POST['company'],
            'position' => $_POST['position'],
            'start_date' => $_POST['start_date'],
            'end_date' => $_POST['end_date'],
            'is_current' => isset($_POST['is_current']) ? 1 : 0,
            'description' => $_POST['description'],
            'order_index' => $_POST['order_index'] ?? 0
        ];

        if ($this->cvModel->addExperience($cv_id, $data)) {
            echo json_encode(['success' => true]);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to add experience']);
        }
    }

    public function addSkill() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
            return;
        }

        $cv_id = $_POST['cv_id'];
        $user_id = $_SESSION['user_id'];
        
        // Verify ownership
        $cv = $this->cvModel->getCVById($cv_id);
        if (!$cv || $cv['user_id'] != $user_id) {
            http_response_code(403);
            echo json_encode(['error' => 'Unauthorized']);
            return;
        }

        $data = [
            'name' => $_POST['name'],
            'level' => $_POST['level'],
            'order_index' => $_POST['order_index'] ?? 0
        ];

        if ($this->cvModel->addSkill($cv_id, $data)) {
            echo json_encode(['success' => true]);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to add skill']);
        }
    }

    private function error404() {
        http_response_code(404);
        require_once 'app/views/error/404.php';
    }
}