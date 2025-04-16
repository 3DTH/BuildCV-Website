<?php

class CvController extends BaseController {
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
    private $packageModel;
    

    public function __construct() {
        $this->checkAuth();
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
        $this->packageModel = new Package(); 
    }

    public function index() {
        $user_id = $_SESSION['user_id'];
        $cvs = $this->cvModel->getUserCVs($user_id);
        $activePackage = $this->packageModel->getUserActivePackage($user_id); 
        require_once 'app/views/cv/index.php';
    }

    // Tạo CV mới
    public function create() {
        $templateId = $_GET['template'] ?? null;
        $selectedTemplate = null;
        
        if ($templateId) {
            $selectedTemplate = $this->templateModel->getTemplateById($templateId);
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (empty($_POST['title']) || empty($_POST['template_id'])) {
                return $this->jsonResponse(['error' => 'Vui lòng điền đầy đủ thông tin!'], 400);
            }

            $data = [
                'user_id' => $_SESSION['user_id'],
                'template_id' => $_POST['template_id'],
                'title' => trim($_POST['title']),
                'summary' => trim($_POST['summary'] ?? ''),
                'is_public' => isset($_POST['is_public']) ? 1 : 0
            ];

            $cv_id = $this->cvModel->createCV($data);
            if ($cv_id) {
                $_SESSION['success'] = "CV đã được tạo thành công!";
                header("Location: " . BASE_URL . "/cv/edit/{$cv_id}");
                exit;
            }
            
            return $this->jsonResponse(['error' => 'Có lỗi xảy ra khi tạo CV!'], 500);
        }

        $templates = $this->templateModel->getAllTemplates();
        require_once 'app/views/cv/create.php';
    }

    // Cập nhật thông tin cơ bản
    public function updateBasicInfo($cv_id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/cv/edit/' . $cv_id);
            exit;
        }

        $user_id = $_SESSION['user_id'];
        $cv = $this->cvModel->getCVById($cv_id);

        if (!$cv || $cv['user_id'] != $user_id) {
            $_SESSION['error'] = "Bạn không có quyền chỉnh sửa CV này!";
            header('Location: ' . BASE_URL . '/cv');
            exit;
        }

        $data = [
            'title' => $_POST['title'],
            'summary' => $_POST['summary'] ?? '',
            'is_public' => isset($_POST['is_public']) ? 1 : 0
        ];

        if ($this->cvModel->updateCV($cv_id, $data)) {
            $_SESSION['success'] = "Thông tin CV đã được cập nhật thành công!";
        } else {
            $_SESSION['error'] = "Có lỗi xảy ra khi cập nhật CV!";
        }

        header('Location: ' . BASE_URL . '/cv/edit/' . $cv_id);
        exit;
    }

    // Cập nhật thông tin cá nhân
    public function updatePersonalInfo() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/cv');
            exit;
        }

        $user_id = $_SESSION['user_id'];
        $cv_id = $_POST['cv_id'];

        $userData = [
            'id' => $user_id,
            'full_name' => $_POST['full_name'],
            'email' => $_POST['email'],
            'phone' => $_POST['phone'] ?? null,
            'address' => $_POST['address'] ?? null
        ];

        if ($this->userModel->updateProfile($userData)) {
            $_SESSION['success'] = "Thông tin cá nhân đã được cập nhật thành công!";
        } else {
            $_SESSION['error'] = "Có lỗi xảy ra khi cập nhật thông tin cá nhân!";
        }

        header('Location: ' . BASE_URL . '/cv/edit/' . $cv_id);
        exit;
    }

    public function edit($cv_id) {
        $user_id = $_SESSION['user_id'];
        $cv = $this->cvModel->getCVById($cv_id); // Sửa $id thành $cv_id
        
        if (!$this->verifyOwnership($cv, $user_id)) {
            $_SESSION['error'] = 'Bạn không có quyền truy cập CV này';
            header('Location: ' . BASE_URL . '/cv');
            exit;
        }
    
        $user = $this->userModel->getUserById($user_id);
        
        // Load all CV components
        $education = $this->educationModel->getByCvId($cv_id);
        $experience = $this->experienceModel->getByCvId($cv_id);
        $skills = $this->skillModel->getByCvId($cv_id);
        $languages = $this->languageModel->getByCvId($cv_id);
        $certificates = $this->certificateModel->getByCvId($cv_id);
        $projects = $this->projectModel->getByCvId($cv_id);
        $contacts = $this->contactModel->getByCvId($cv_id);
        
        require_once 'app/views/cv/edit.php';
    }

    public function delete($cv_id) {
        $user_id = $_SESSION['user_id'];
        $cv = $this->cvModel->getCVById($cv_id);

        if (!$this->verifyOwnership($cv, $user_id)) {
            return $this->jsonResponse(['error' => 'Không có quyền xóa CV này!'], 403);
        }

        if ($this->cvModel->deleteCV($cv_id, $user_id)) {
            $_SESSION['success'] = "CV đã được xóa thành công!";
            header('Location: ' . BASE_URL . '/cv');
            exit;
        }

        return $this->jsonResponse(['error' => 'Có lỗi xảy ra khi xóa CV!'], 500);
    }
    
    public function renderPreview($templateId) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->jsonResponse(['error' => 'Method not allowed'], 405);
        }
    
        // Get JSON data from request body
        $data = json_decode(file_get_contents('php://input'), true);
        if (!$data) {
            return $this->jsonResponse(['error' => 'Invalid data format'], 400);
        }
    
        // Get user data
        $user_id = $_SESSION['user_id'];
        $user = $this->userModel->getUserById($user_id);
        
        // Get CV ID from data
        $cv_id = $data['cv_id'] ?? null;
        $cv = $cv_id ? $this->cvModel->getCVById($cv_id) : null;
        
        // Get template mapping
        $template = $this->templateModel->getTemplateById($templateId);
        if (!$template) {
            return $this->jsonResponse(['error' => 'Template not found'], 404);
        }
        
        // Get all CV components from database if CV exists
        $education = $cv_id ? $this->educationModel->getByCvId($cv_id) : [];
        $experience = $cv_id ? $this->experienceModel->getByCvId($cv_id) : [];
        $skills = $cv_id ? $this->skillModel->getByCvId($cv_id) : [];
        $languages = $cv_id ? $this->languageModel->getByCvId($cv_id) : [];
        $certificates = $cv_id ? $this->certificateModel->getByCvId($cv_id) : [];
        $projects = $cv_id ? $this->projectModel->getByCvId($cv_id) : [];
        $contacts = $cv_id ? $this->contactModel->getByCvId($cv_id) : [];

        // Merge all data with priority to form data over database data
        $templateData = [
            'cv' => $cv ?: [
                'title' => $data['title'] ?? '',
                'summary' => $data['summary'] ?? ''
            ],
            'personal' => array_merge([
                'name' => $user['full_name'],
                'email' => $user['email'],
                'phone' => $user['phone'],
                'address' => $user['address']
            ], $data['personal'] ?? []),
            'education' => !empty($data['education']) ? $data['education'] : $education,
            'experience' => !empty($data['experience']) ? $data['experience'] : $experience,
            'skills' => !empty($data['skills']) ? $data['skills'] : $skills,
            'languages' => !empty($data['languages']) ? $data['languages'] : $languages,
            'certificates' => !empty($data['certificates']) ? $data['certificates'] : $certificates,
            'projects' => !empty($data['projects']) ? $data['projects'] : $projects,
            'contacts' => !empty($data['contacts']) ? $data['contacts'] : $contacts
        ];

        // Get preview template file path
        $previewFile = "app/views/cv/previews/{$templateId}.php";
        if (!file_exists($previewFile)) {
            // If preview template doesn't exist, copy from template
            $templateFile = "app/views/templates/previews/{$templateId}.php";
            if (!file_exists($templateFile)) {
                return $this->jsonResponse(['error' => 'Template file not found'], 404);
            }
            
            if (!is_dir('app/views/cv/previews')) {
                mkdir('app/views/cv/previews', 0777, true);
            }
            
            copy($templateFile, $previewFile);
        }
    
        // Start output buffering
        ob_start();
        
        // Make data available to template
        extract($templateData);
        
        // Include preview template
        include $previewFile;
        
        // Get rendered content
        $content = ob_get_clean();
        
        // Return HTML
        header('Content-Type: text/html');
        echo $content;
    }

    public function generatePDF() {
        require_once 'vendor/autoload.php';
        
        $data = json_decode(file_get_contents('php://input'), true);
        
        // Create new mPDF instance without namespace
        $mpdf = new \mPDF('utf-8', 'A4', '', '', 15, 15, 15, 15);
        
        // Add custom CSS and content
        $mpdf->WriteHTML($data['styles'], 1);
        $mpdf->WriteHTML($data['content'], 2);
        
        // Output PDF
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="cv.pdf"');
        $mpdf->Output('cv.pdf', 'D');
    }
}