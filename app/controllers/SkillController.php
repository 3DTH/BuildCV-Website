<?php
class SkillController extends BaseController {
    private $skillModel;
    private $cvModel;

    public function __construct() {
        $this->checkAuth();
        $this->skillModel = new Skill();
        $this->cvModel = new CV();
    }

    public function get($id) {
        $skill = $this->skillModel->getById($id);
        if (!$skill) {
            return $this->jsonResponse(['error' => 'Không tìm thấy kỹ năng'], 404);
        }

        $cv = $this->cvModel->getCVById($skill['cv_id']);
        if (!$this->verifyOwnership($cv, $_SESSION['user_id'])) {
            return $this->jsonResponse(['error' => 'Không có quyền truy cập'], 403);
        }

        return $this->jsonResponse([
            'success' => true,
            'data' => $skill
        ]);
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
            'name' => $_POST['name'] ?? '',
            'level' => $_POST['level'] ?? 0,
            'order_index' => isset($_POST['order_index']) ? (int)$_POST['order_index'] : 0
        ];

        try {
            if ($this->skillModel->add($data)) {
                $skills = $this->skillModel->getByCvId($cv_id);
                return $this->jsonResponse([
                    'success' => true,
                    'message' => 'Thêm kỹ năng thành công',
                    'data' => $skills
                ]);
            }
        } catch (Exception $e) {
            error_log($e->getMessage());
        }

        return $this->jsonResponse([
            'error' => 'Không thể thêm kỹ năng'
        ], 500);
    }

    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->jsonResponse(['error' => 'Phương thức không được phép'], 405);
        }

        $skill = $this->skillModel->getById($id);
        if (!$skill) {
            return $this->jsonResponse(['error' => 'Không tìm thấy kỹ năng'], 404);
        }

        $cv = $this->cvModel->getCVById($skill['cv_id']);
        if (!$this->verifyOwnership($cv, $_SESSION['user_id'])) {
            return $this->jsonResponse(['error' => 'Không có quyền truy cập'], 403);
        }

        $data = [
            'cv_id' => $skill['cv_id'],
            'name' => $_POST['name'],
            'level' => $_POST['level'],
            'order_index' => $_POST['order_index'] ?? $skill['order_index']
        ];

        if ($this->skillModel->update($id, $data)) {
            $skills = $this->skillModel->getByCvId($skill['cv_id']);
            return $this->jsonResponse([
                'success' => true,
                'message' => 'Cập nhật kỹ năng thành công',
                'data' => $skills
            ]);
        }

        return $this->jsonResponse([
            'error' => 'Không thể cập nhật kỹ năng'
        ], 500);
    }

    public function delete($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->jsonResponse(['error' => 'Phương thức không được phép'], 405);
        }

        $skill = $this->skillModel->getById($id);
        if (!$skill) {
            return $this->jsonResponse(['error' => 'Không tìm thấy kỹ năng'], 404);
        }

        $cv = $this->cvModel->getCVById($skill['cv_id']);
        if (!$this->verifyOwnership($cv, $_SESSION['user_id'])) {
            return $this->jsonResponse(['error' => 'Không có quyền truy cập'], 403);
        }

        if ($this->skillModel->delete($id, $skill['cv_id'])) {
            return $this->jsonResponse([
                'success' => true,
                'message' => 'Xóa kỹ năng thành công'
            ]);
        }

        return $this->jsonResponse([
            'error' => 'Không thể xóa kỹ năng'
        ], 500);
    }
}