<?php
class CvController {
    private $cvModel;
    private $userModel;
    private $templateModel;
    private $educationModel;
    private $experienceModel;
    private $skillModel;
    private $languageModel;
    private $certificateModel;
    private $projectModel;
    private $contactModel;
    

    public function __construct() {
        // Kiểm tra đăng nhập cho tất cả các action trừ viewPublic
        if (!isset($_SESSION['user_id']) && $_GET['action'] !== 'viewPublic') {
            header('Location: ' . BASE_URL . '/auth/login');
            exit;
        }

        $this->cvModel = new CV();
        $this->userModel = new User();
        $this->templateModel = new Template();
        $this->educationModel = new Education();
        $this->experienceModel = new Experience();
        $this->skillModel = new Skill();
        $this->languageModel = new Language();
        $this->certificateModel = new Certificate();
        $this->projectModel = new Project();
        $this->contactModel = new ContactInfo();
    }

    public function index() {
        $user_id = $_SESSION['user_id'];
        $cvs = $this->cvModel->getUserCVs($user_id);
        require_once 'app/views/cv/index.php';
    }

    public function create($templateId = null) {
        // Kiểm tra template ID từ URL parameter hoặc query string
        if (!$templateId && isset($_GET['template'])) {
            $templateId = $_GET['template'];
        }
        
        // Nếu có template ID, lấy thông tin template đã chọn
        $selectedTemplate = null;
        $selectedTemplateId = null;
        if ($templateId) {
            $selectedTemplate = $this->templateModel->getTemplateById($templateId);
            $selectedTemplateId = $templateId;
        }

        // Xử lý khi form được submit
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

        // Lấy danh sách template để hiển thị
        $templates = $this->templateModel->getAllTemplates();
        require_once 'app/views/cv/create.php';
    }

    public function edit($cv_id) {
        $user_id = $_SESSION['user_id'];
        $cv = $this->cvModel->getCVById($cv_id);
        $user = $this->userModel->getUserById($user_id);

        // Kiểm tra quyền sở hữu CV
        if (!$cv || $cv['user_id'] != $user_id) {
            $_SESSION['error'] = "Bạn không có quyền chỉnh sửa CV này!";
            header('Location: ' . BASE_URL . '/cv');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Cập nhật thông tin cơ bản của CV
            $cvData = [
                'title' => $_POST['title'],
                'summary' => $_POST['summary'] ?? '',
                'is_public' => isset($_POST['is_public']) ? 1 : 0
            ];

            // Cập nhật thông tin user
            $userData = [
                'id' => $user_id,
                'full_name' => $_POST['full_name'],
                'email' => $_POST['email'],
                'phone' => $_POST['phone'],
                'address' => $_POST['address']
            ];

            $success = true;
            if (!$this->cvModel->updateCV($cv_id, $cvData)) {
                $success = false;
            }
            if (!$this->userModel->updateProfile($userData)) {
                $success = false;
            }

            if ($success) {
                $_SESSION['success'] = "CV đã được cập nhật thành công!";
            } else {
                $_SESSION['error'] = "Có lỗi xảy ra khi cập nhật CV!";
            }
        }

        // Lấy dữ liệu cho form edit
        $education = $this->educationModel->getByCvId($cv_id);
        $experiences = $this->experienceModel->getByCvId($cv_id);
        $skills = $this->skillModel->getByCvId($cv_id);
        $languages = $this->languageModel->getByCvId($cv_id);
        $certificates = $this->certificateModel->getByCvId($cv_id);
        $projects = $this->projectModel->getByCvId($cv_id);
        $contacts = $this->contactModel->getByCvId($cv_id);

        // $education = $this->cvModel->getEducation($cv_id);
        // $experiences = $this->cvModel->getExperiences($cv_id);
        // $skills = $this->cvModel->getSkills($cv_id);
        // $languages = $this->cvModel->getLanguages($cv_id);
        // $certificates = $this->cvModel->getCertificates($cv_id);
        // $projects = $this->cvModel->getProjects($cv_id);
        // $contacts = $this->cvModel->getContactInfo($cv_id);
        

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

        $education = $this->educationModel->getByCvId($cv_id);
        $experiences = $this->experienceModel->getByCvId($cv_id);
        $skills = $this->skillModel->getByCvId($cv_id);

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
            'cv_id' => $cv_id,
            'institution' => $_POST['institution'],
            'degree' => $_POST['degree'],
            'field_of_study' => $_POST['field_of_study'],
            'start_date' => $_POST['start_date'],
            'end_date' => $_POST['end_date'],
            'description' => $_POST['description'],
            'order_index' => $_POST['order_index'] ?? 0
        ];

        if ($this->educationModel->add($data)) {
            $education = $this->educationModel->getByCvId($cv_id);
            echo json_encode([
                'success' => true,
                'data' => $education
            ]);
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
            'cv_id' => $cv_id,
            'company' => $_POST['company'],
            'position' => $_POST['position'],
            'start_date' => $_POST['start_date'],
            'end_date' => $_POST['end_date'],
            'is_current' => isset($_POST['is_current']) ? 1 : 0,
            'description' => $_POST['description'],
            'order_index' => $_POST['order_index'] ?? 0
        ];

        if ($this->experienceModel->add($data)) {
            $experiences = $this->experienceModel->getByCvId($cv_id);
            echo json_encode([
                'success' => true,
                'data' => $experiences
            ]);
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
            'cv_id' => $cv_id,
            'name' => $_POST['name'],
            'level' => $_POST['level'],
            'order_index' => $_POST['order_index'] ?? 0
        ];

        if ($this->skillModel->add($data)) {
            $skills = $this->skillModel->getByCvId($cv_id);
            echo json_encode([
                'success' => true,
                'data' => $skills
            ]);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to add skill']);
        }
    }

    public function addLanguage() {
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
            'cv_id' => $cv_id,
            'name' => $_POST['name'],
            'proficiency' => $_POST['proficiency'],
            'order_index' => $_POST['order_index'] ?? 0
        ];

        if ($this->languageModel->add($data)) {
            $languages = $this->languageModel->getByCvId($cv_id);
            echo json_encode([
                'success' => true,
                'data' => $languages
            ]);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to add language']);
        }
    }

    public function addCertificate() {
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
            'cv_id' => $cv_id,
            'name' => $_POST['name'],
            'issuer' => $_POST['issuer'],
            'issue_date' => $_POST['issue_date'],
            'expiry_date' => $_POST['expiry_date'] ?? null,
            'description' => $_POST['description'] ?? '',
            'order_index' => $_POST['order_index'] ?? 0
        ];

        if ($this->certificateModel->add($data)) {
            $certificates = $this->certificateModel->getByCvId($cv_id);
            echo json_encode([
                'success' => true,
                'data' => $certificates
            ]);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to add certificate']);
        }
    }

    public function addProject() {
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
            'cv_id' => $cv_id,
            'title' => $_POST['title'],
            'role' => $_POST['role'],
            'start_date' => $_POST['start_date'],
            'end_date' => $_POST['end_date'] ?? null,
            'description' => $_POST['description'],
            'url' => $_POST['url'] ?? null,
            'order_index' => $_POST['order_index'] ?? 0
        ];

        if ($this->projectModel->add($data)) {
            $projects = $this->projectModel->getByCvId($cv_id);
            echo json_encode([
                'success' => true,
                'data' => $projects
            ]);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to add project']);
        }
    }

    public function addContact() {
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
            'cv_id' => $cv_id,
            'type' => $_POST['type'],
            'value' => $_POST['value'],
            'is_primary' => isset($_POST['is_primary']) ? 1 : 0,
            'order_index' => $_POST['order_index'] ?? 0
        ];

        if ($this->contactModel->add($data)) {
            if ($data['is_primary']) {
                $this->contactModel->setPrimary($data['id'], $cv_id, $data['type']);
            }
            $contacts = $this->contactModel->getByCvId($cv_id);
            echo json_encode([
                'success' => true,
                'data' => $contacts
            ]);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to add contact']);
        }
    }

    private function error404() {
        http_response_code(404);
        require_once 'app/views/error/404.php';
    }
}