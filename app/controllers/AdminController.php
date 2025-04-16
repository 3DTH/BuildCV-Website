<?php
require_once 'app/controllers/BaseController.php';

class AdminController extends BaseController {
    private $userModel;
    private $cvModel;
    private $packageModel;

    public function __construct() {
        parent::__construct();
        $this->checkAdminAuth();
        
        require_once 'app/models/User.php';
        require_once 'app/models/CV.php';
        require_once 'app/models/Package.php';
        
        $this->userModel = new User();
        $this->cvModel = new CV();
        $this->packageModel = new Package();
    }

    public function index() {
        $title = 'Dashboard - Admin BuildCV';
        $data = [
            'title' => $title,
            'totalUsers' => $this->userModel->getTotalUsers(),
            'totalCVs' => $this->cvModel->getTotalCVs(),
            'activeSubscriptions' => $this->packageModel->getTotalActiveSubscriptions(),
            'monthlyRevenue' => $this->packageModel->getMonthlyRevenue(), 
            'recentUsers' => $this->userModel->getRecentUsers(5),
            'recentSubscriptions' => $this->packageModel->getRecentSubscriptions(5)
        ];

        $viewPath = 'app/views/admin/dashboard.php';
        extract($data);
        require_once 'app/views/layouts/admin.php';
    }
}