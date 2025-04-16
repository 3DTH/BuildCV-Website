<?php
class TemplatesController extends BaseController{
    private $templateModel;
    private $userModel;
    private $packageModel;

    public function __construct() {
        $this->templateModel = new Template();
        $this->userModel = new User();
        $this->packageModel = new Package();
    }

    // Hiển thị danh sách các template
    public function index() {
        $templates = $this->templateModel->getAllTemplates();
        $user_id = $_SESSION['user_id'] ?? null;
        $hasPremiumAccess = false;
        
        if ($user_id) {
            // Kiểm tra quyền truy cập premium
            $hasPremiumAccess = $this->templateModel->checkPremiumAccess($user_id, null);
            $activePackage = $this->packageModel->getUserActivePackage($user_id);
            $hasPremiumAccess = !empty($activePackage);
        }
        
        require_once 'app/views/templates/index.php';
    }

    // Xem chi tiết template
    public function preview($id) {
        $template = $this->templateModel->getTemplateById($id);
        
        if (!$template) {
            return $this->jsonResponse(['error' => 'Template không tồn tại'], 404);
        }

        $templateName = strtolower(str_replace(' ', '-', $template['name']));
        $previewFile = "app/views/templates/previews/{$templateName}.php";
        
        if (!file_exists($previewFile)) {
            return $this->jsonResponse(['error' => 'File preview không tồn tại'], 404);
        }

        ob_start();
        require_once $previewFile;
        $previewHtml = ob_get_clean();
        
        return $this->jsonResponse([
            'success' => true,
            'html' => $previewHtml,
            'template' => $template
        ]);
    }

    // Lọc các template theo loại và tìm kiếm
    public function filter() {
        $type = $_GET['type'] ?? 'all';
        $search = $_GET['search'] ?? '';

        $templates = match($type) {
            'free' => $this->templateModel->getFreeTemplates(),
            'premium' => $this->templateModel->getPremiumTemplates(),
            default => $this->templateModel->getAllTemplates()
        };

        if (!empty($search)) {
            $templates = array_filter($templates, function($template) use ($search) {
                return (stripos($template['name'], $search) !== false || 
                        stripos($template['description'], $search) !== false);
            });
        }

        return $this->jsonResponse(['success' => true, 'templates' => $templates]);
    }

    // Kiểm tra quyền truy cập template cho người dùng
    public function checkAccess($templateId) {
        if (!isset($_SESSION['user_id'])) {
            return $this->jsonResponse(['error' => 'Chưa đăng nhập'], 401);
        }
    
        $template = $this->templateModel->getTemplateById($templateId);
        if (!$template['is_premium']) {
            return $this->jsonResponse(['success' => true]);
        }
    
        $activePackage = $this->packageModel->getUserActivePackage($_SESSION['user_id']);
        return $this->jsonResponse([
            'success' => true,
            'hasAccess' => !empty($activePackage)
        ]);
    }
}