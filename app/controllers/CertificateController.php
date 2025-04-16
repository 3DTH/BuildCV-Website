<?php
class CertificateController extends BaseController {
    private $certificateModel;
    private $cvModel;

    public function __construct() {
        $this->checkAuth();
        $this->certificateModel = new Certificate();
        $this->cvModel = new CV();
    }

    public function add() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->jsonResponse(['error' => 'Phương thức không được phép'], 405);
        }

        $cv_id = $_POST['cv_id'];
        $user_id = $_SESSION['user_id'];
        
        $cv = $this->cvModel->getCVById($cv_id);
        if (!$this->verifyOwnership($cv, $user_id)) {
            return $this->jsonResponse(['error' => 'Không có quyền truy cập'], 403);
        }

        $issue_date = $_POST['issue_date'];
        $expiry_date = $_POST['expiry_date'] ?? null;
        
        // Validate issue date not in future
        if (strtotime($issue_date) > time()) {
            return $this->jsonResponse(['error' => 'Ngày cấp không thể sau ngày hiện tại'], 400);
        }

        // Validate expiry date if provided
        if ($expiry_date && strtotime($expiry_date) < strtotime($issue_date)) {
            return $this->jsonResponse(['error' => 'Ngày hết hạn không thể trước ngày cấp'], 400);
        }

        $data = [
            'cv_id' => $cv_id,
            'name' => $_POST['name'],
            'issuer' => $_POST['issuer'],
            'issue_date' => $issue_date,
            'expiry_date' => $expiry_date,
            'description' => $_POST['description'] ?? '',
            'order_index' => $_POST['order_index'] ?? 0
        ];

        if ($this->certificateModel->add($data)) {
            $certificates = $this->certificateModel->getByCvId($cv_id);
            return $this->jsonResponse(['success' => true, 'data' => $certificates]);
        }

        return $this->jsonResponse(['error' => 'Không thể thêm chứng chỉ'], 500);
    }

    public function get($id) {
        $certificate = $this->certificateModel->getById($id);
        if (!$certificate) {
            return $this->jsonResponse(['error' => 'Không tìm thấy chứng chỉ'], 404);
        }
    
        $cv = $this->cvModel->getCVById($certificate['cv_id']);
        if (!$this->verifyOwnership($cv, $_SESSION['user_id'])) {
            return $this->jsonResponse(['error' => 'Không có quyền truy cập'], 403);
        }
    
        return $this->jsonResponse([
            'success' => true,
            'data' => $certificate
        ]);
    }

    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->jsonResponse(['error' => 'Phương thức không được phép'], 405);
        }
    
        $certificate = $this->certificateModel->getById($id);
        if (!$certificate) {
            return $this->jsonResponse(['error' => 'Không tìm thấy chứng chỉ'], 404);
        }
    
        $cv = $this->cvModel->getCVById($certificate['cv_id']);
        if (!$this->verifyOwnership($cv, $_SESSION['user_id'])) {
            return $this->jsonResponse(['error' => 'Không có quyền truy cập'], 403);
        }
    
        $issue_date = $_POST['issue_date'];
        $expiry_date = $_POST['expiry_date'] ?: null;
        
        // Validate issue date not in future
        if (strtotime($issue_date) > time()) {
            return $this->jsonResponse(['error' => 'Ngày cấp không thể sau ngày hiện tại'], 400);
        }

        // Validate expiry date if provided
        if ($expiry_date && strtotime($expiry_date) < strtotime($issue_date)) {
            return $this->jsonResponse(['error' => 'Ngày hết hạn không thể trước ngày cấp'], 400);
        }

        $data = [
            'cv_id' => $certificate['cv_id'],
            'name' => $_POST['name'],
            'issuer' => $_POST['issuer'],
            'issue_date' => $issue_date,
            'expiry_date' => $expiry_date,
            'description' => $_POST['description'] ?? '',
            'order_index' => $_POST['order_index'] ?? $certificate['order_index']
        ];
    
        if ($this->certificateModel->update($id, $data)) {
            $certificates = $this->certificateModel->getByCvId($certificate['cv_id']);
            return $this->jsonResponse([
                'success' => true,
                'data' => $certificates
            ]);
        }
    
        return $this->jsonResponse(['error' => 'Không thể cập nhật chứng chỉ'], 500);
    }

    public function delete($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->jsonResponse(['error' => 'Phương thức không được phép'], 405);
        }
    
        $certificate = $this->certificateModel->getById($id);
        if (!$certificate) {
            return $this->jsonResponse(['error' => 'Không tìm thấy chứng chỉ'], 404);
        }
    
        $cv = $this->cvModel->getCVById($certificate['cv_id']);
        if (!$this->verifyOwnership($cv, $_SESSION['user_id'])) {
            return $this->jsonResponse(['error' => 'Không có quyền truy cập'], 403);
        }
    
        if ($this->certificateModel->delete($id, $certificate['cv_id'])) {
            return $this->jsonResponse([
                'success' => true,
                'message' => 'Xóa chứng chỉ thành công'
            ]);
        }
    
        return $this->jsonResponse(['error' => 'Không thể xóa chứng chỉ'], 500);
    }
}