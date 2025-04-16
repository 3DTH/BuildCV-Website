<?php
class PackageController extends BaseController {
    private $packageModel;
    private $userModel;

    public function __construct() {
        $this->checkAuth();
        $this->packageModel = new Package();
        $this->userModel = new User();
    }

    public function index() {
        $packages = $this->packageModel->getAllPackages();
        $user_id = $_SESSION['user_id'];
        $activePackage = $this->packageModel->getUserActivePackage($user_id);
        
        require_once 'app/views/packages/index.php';
    }

    public function purchase($package_id) {
        $package = $this->packageModel->getPackageById($package_id);
        if (!$package) {
            $_SESSION['error'] = "Gói dịch vụ không tồn tại!";
            header('Location: ' . BASE_URL . '/packages');
            exit;
        }

        header('Location: ' . BASE_URL . '/payment/checkout/' . $package_id);
        exit;
    }
}