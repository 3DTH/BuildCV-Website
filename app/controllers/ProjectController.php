<?php
class ProjectController extends BaseController {
    private $projectModel;
    private $cvModel;

    public function __construct() {
        $this->checkAuth();
        $this->projectModel = new Project();
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
            return $this->jsonResponse(['success' => true, 'data' => $projects]);
        }

        return $this->jsonResponse(['error' => 'Không thể thêm dự án'], 500);
    }

    public function get($id) {
        $project = $this->projectModel->getById($id);
        if (!$project) {
            return $this->jsonResponse(['error' => 'Không tìm thấy dự án'], 404);
        }
    
        $cv = $this->cvModel->getCVById($project['cv_id']);
        if (!$this->verifyOwnership($cv, $_SESSION['user_id'])) {
            return $this->jsonResponse(['error' => 'Không có quyền truy cập'], 403);
        }
    
        return $this->jsonResponse([
            'success' => true,
            'data' => $project
        ]);
    }

    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->jsonResponse(['error' => 'Phương thức không được phép'], 405);
        }
    
        $project = $this->projectModel->getById($id);
        if (!$project) {
            return $this->jsonResponse(['error' => 'Không tìm thấy dự án'], 404);
        }
    
        $cv = $this->cvModel->getCVById($project['cv_id']);
        if (!$this->verifyOwnership($cv, $_SESSION['user_id'])) {
            return $this->jsonResponse(['error' => 'Không có quyền truy cập'], 403);
        }
    
        // Validate dates
        $start_date = $_POST['start_date'];
        $end_date = $_POST['end_date'] ?: null;
    
        if (strtotime($start_date) > time()) {
            return $this->jsonResponse(['error' => 'Ngày bắt đầu không thể sau ngày hiện tại'], 400);
        }
    
        if ($end_date && strtotime($end_date) < strtotime($start_date)) {
            return $this->jsonResponse(['error' => 'Ngày kết thúc không thể trước ngày bắt đầu'], 400);
        }
    
        $data = [
            'cv_id' => $project['cv_id'],
            'title' => $_POST['title'],
            'role' => $_POST['role'],
            'start_date' => $start_date,
            'end_date' => $end_date,
            'description' => $_POST['description'],
            'url' => $_POST['url'] ?: null,
            'order_index' => $_POST['order_index'] ?? $project['order_index']
        ];
    
        if ($this->projectModel->update($id, $data)) {
            $projects = $this->projectModel->getByCvId($project['cv_id']);
            return $this->jsonResponse([
                'success' => true,
                'data' => $projects
            ]);
        }
    
        return $this->jsonResponse(['error' => 'Không thể cập nhật dự án'], 500);
    }

    public function delete($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->jsonResponse(['error' => 'Phương thức không được phép'], 405);
        }
    
        $project = $this->projectModel->getById($id);
        if (!$project) {
            return $this->jsonResponse(['error' => 'Không tìm thấy dự án'], 404);
        }
    
        $cv = $this->cvModel->getCVById($project['cv_id']);
        if (!$this->verifyOwnership($cv, $_SESSION['user_id'])) {
            return $this->jsonResponse(['error' => 'Không có quyền truy cập'], 403);
        }
    
        if ($this->projectModel->delete($id, $project['cv_id'])) {
            return $this->jsonResponse([
                'success' => true,
                'message' => 'Xóa dự án thành công'
            ]);
        }
    
        return $this->jsonResponse(['error' => 'Không thể xóa dự án'], 500);
    }
}