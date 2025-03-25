<?php
class HomeController {
    private $userModel;
    private $cvModel;
    private $templateModel;

    public function __construct() {
        $this->userModel = new User();
        $this->cvModel = new CV();
        $this->templateModel = new Template();
    }

    public function index() {
        // Lấy danh sách CV công khai mới nhất
        $publicCVs = $this->cvModel->getPublicCVs(6, 0);
        
        // Lấy danh sách template miễn phí
        $freeTemplates = $this->templateModel->getFreeTemplates();
        
        // Load view với dữ liệu
        require_once 'app/views/home/index.php';
    }

    public function about() {
        require_once 'app/views/home/about.php';
    }

    public function contact() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Xử lý form liên hệ
            $name = $_POST['name'] ?? '';
            $email = $_POST['email'] ?? '';
            $message = $_POST['message'] ?? '';
            
            // Gửi email hoặc lưu vào database
            // TODO: Implement contact form handling
            
            $_SESSION['success'] = "Cảm ơn bạn đã liên hệ với chúng tôi!";
            header('Location: ' . BASE_URL . '/contact');
            exit;
        }
        
        require_once 'app/views/home/contact.php';
    }

}