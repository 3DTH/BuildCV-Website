<?php
class TemplatesController {
    private $templateModel;
    private $userModel;

    public function __construct() {
        $this->templateModel = new Template();
        $this->userModel = new User();
    }

    public function index() {
        // Get all templates
        $templates = $this->templateModel->getAllTemplates();
        
        // Load the templates view
        require_once 'app/views/templates/index.php';
    }

    public function preview($id) {
        header('Content-Type: application/json');
        
        $template = $this->templateModel->getTemplateById($id);
        
        if (!$template) {
            echo json_encode([
                'success' => false,
                'message' => 'Template not found'
            ]);
            return;
        }

        // Load preview content
        ob_start();
        // Convert template name to lowercase for file naming consistency
        $templateName = strtolower(str_replace(' ', '-', $template['name']));
        $previewFile = "app/views/templates/previews/{$templateName}.php";
        
        if (file_exists($previewFile)) {
            require_once $previewFile;
            $previewHtml = ob_get_clean();
            
            echo json_encode([
                'success' => true,
                'html' => $previewHtml,
                'template' => $template
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => "Preview template not found: {$previewFile}"
            ]);
        }
    }

    public function filter() {
        $type = $_GET['type'] ?? 'all';
        $search = $_GET['search'] ?? '';

        switch ($type) {
            case 'free':
                $templates = $this->templateModel->getFreeTemplates();
                break;
            case 'premium':
                $templates = $this->templateModel->getPremiumTemplates();
                break;
            default:
                $templates = $this->templateModel->getAllTemplates();
        }

        // Filter by search term if provided
        if (!empty($search)) {
            $templates = array_filter($templates, function($template) use ($search) {
                return (stripos($template['name'], $search) !== false || 
                        stripos($template['description'], $search) !== false);
            });
        }

        echo json_encode([
            'success' => true,
            'templates' => $templates
        ]);
    }

    // Kiểm tra quyền truy cập template cho người dùng
    public function checkAccess($templateId) {
        if (!isset($_SESSION['user_id'])) {
            return false;
        }

        $template = $this->templateModel->getTemplateById($templateId);
        $user = $this->userModel->getUserById($_SESSION['user_id']);

        // Free templates are accessible to all
        if (!$template['is_premium']) {
            return true;
        }

        // Check if user has premium access
        return $user['has_premium_access'];
    }

    // public function create() {
    //     // Check if user is admin
    //     if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    //         header('Location: ' . BASE_URL . '/templates');
    //         exit;
    //     }

    //     if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //         $data = [
    //             'name' => $_POST['name'],
    //             'description' => $_POST['description'],
    //             'is_premium' => isset($_POST['is_premium']),
    //             'thumbnail' => '',
    //             'css_file' => ''
    //         ];

    //         // Handle thumbnail upload
    //         if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] === 0) {
    //             $fileUploader = new FileUploader();
    //             $uploadResult = $fileUploader->uploadTemplateImage($_FILES['thumbnail']);
    //             if ($uploadResult['success']) {
    //                 $data['thumbnail'] = $uploadResult['filename'];
    //             } else {
    //                 $_SESSION['error'] = $uploadResult['error'];
    //                 require_once 'app/views/templates/create.php';
    //                 return;
    //             }
    //         }

    //         // Handle CSS file upload
    //         if (isset($_FILES['css_file']) && $_FILES['css_file']['error'] === 0) {
    //             $fileUploader = new FileUploader();
    //             $uploadResult = $fileUploader->uploadCSSFile($_FILES['css_file']);
    //             if ($uploadResult['success']) {
    //                 $data['css_file'] = $uploadResult['filename'];
    //             } else {
    //                 $_SESSION['error'] = $uploadResult['error'];
    //                 require_once 'app/views/templates/create.php';
    //                 return;
    //             }
    //         }

    //         if ($this->templateModel->createTemplate($data)) {
    //             $_SESSION['success'] = 'Template created successfully';
    //             header('Location: ' . BASE_URL . '/templates');
    //             exit;
    //         } else {
    //             $_SESSION['error'] = 'Failed to create template';
    //         }
    //     }

    //     require_once 'app/views/templates/create.php';
    // }

    // public function edit($id) {
    //     // Check if user is admin
    //     if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    //         header('Location: ' . BASE_URL . '/templates');
    //         exit;
    //     }

    //     $template = $this->templateModel->getTemplateById($id);

    //     if (!$template) {
    //         header('Location: ' . BASE_URL . '/templates');
    //         exit;
    //     }

    //     if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //         $data = [
    //             'name' => $_POST['name'],
    //             'description' => $_POST['description'],
    //             'is_premium' => isset($_POST['is_premium']),
    //             'thumbnail' => $template['thumbnail'],
    //             'css_file' => $template['css_file']
    //         ];

    //         // Handle new thumbnail upload
    //         if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] === 0) {
    //             $fileUploader = new FileUploader();
    //             $uploadResult = $fileUploader->uploadTemplateImage($_FILES['thumbnail']);
    //             if ($uploadResult['success']) {
    //                 $data['thumbnail'] = $uploadResult['filename'];
    //             } else {
    //                 $_SESSION['error'] = $uploadResult['error'];
    //                 require_once 'app/views/templates/edit.php';
    //                 return;
    //             }
    //         }

    //         // Handle new CSS file upload
    //         if (isset($_FILES['css_file']) && $_FILES['css_file']['error'] === 0) {
    //             $fileUploader = new FileUploader();
    //             $uploadResult = $fileUploader->uploadCSSFile($_FILES['css_file']);
    //             if ($uploadResult['success']) {
    //                 $data['css_file'] = $uploadResult['filename'];
    //             } else {
    //                 $_SESSION['error'] = $uploadResult['error'];
    //                 require_once 'app/views/templates/edit.php';
    //                 return;
    //             }
    //         }

    //         if ($this->templateModel->updateTemplate($id, $data)) {
    //             $_SESSION['success'] = 'Template updated successfully';
    //             header('Location: ' . BASE_URL . '/templates');
    //             exit;
    //         } else {
    //             $_SESSION['error'] = 'Failed to update template';
    //         }
    //     }

    //     require_once 'app/views/templates/edit.php';
    // }

    // public function delete($id) {
    //     // Check if user is admin
    //     if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    //         header('Location: ' . BASE_URL . '/templates');
    //         exit;
    //     }

    //     if ($this->templateModel->deleteTemplate($id)) {
    //         $_SESSION['success'] = 'Template deleted successfully';
    //     } else {
    //         $_SESSION['error'] = 'Failed to delete template';
    //     }

    //     header('Location: ' . BASE_URL . '/templates');
    //     exit;
    // }
}