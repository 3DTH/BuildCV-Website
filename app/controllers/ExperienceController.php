<?php
class ExperienceController extends BaseController
{
    private $experienceModel;
    private $cvModel;

    public function __construct()
    {
        $this->checkAuth();
        $this->experienceModel = new Experience();
        $this->cvModel = new CV();
    }

    public function get($id)
    {
        $experience = $this->experienceModel->getById($id);
        if (!$experience) {
            return $this->jsonResponse(['error' => 'Không tìm thấy kinh nghiệm làm việc'], 404);
        }

        $cv = $this->cvModel->getCVById($experience['cv_id']);
        if (!$this->verifyOwnership($cv, $_SESSION['user_id'])) {
            return $this->jsonResponse(['error' => 'Không có quyền truy cập'], 403);
        }

        return $this->jsonResponse([
            'success' => true,
            'data' => $experience
        ]);
    }

    public function add()
    {
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
            'company' => $_POST['company'] ?? '',
            'position' => $_POST['position'] ?? '',
            'start_date' => $_POST['start_date'] ?? null,
            'end_date' => empty($_POST['end_date']) ? null : $_POST['end_date'],
            'description' => $_POST['description'] ?? '',
            'is_current' => isset($_POST['is_current']) && $_POST['is_current'] === 'true' ? 1 : 0,
            'order_index' => isset($_POST['order_index']) ? (int)$_POST['order_index'] : 0
        ];

        try {
            if ($this->experienceModel->add($data)) {
                $experiences = $this->experienceModel->getByCvId($cv_id);
                return $this->jsonResponse([
                    'success' => true,
                    'message' => 'Thêm kinh nghiệm làm việc thành công',
                    'data' => $experiences
                ]);
            }
        } catch (Exception $e) {
            error_log($e->getMessage());
        }

        return $this->jsonResponse([
            'error' => 'Không thể thêm kinh nghiệm làm việc'
        ], 500);
    }

    public function update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->jsonResponse(['error' => 'Phương thức không được phép'], 405);
        }

        $experience = $this->experienceModel->getById($id);
        if (!$experience) {
            return $this->jsonResponse(['error' => 'Không tìm thấy kinh nghiệm làm việc'], 404);
        }

        $cv = $this->cvModel->getCVById($experience['cv_id']);
        if (!$this->verifyOwnership($cv, $_SESSION['user_id'])) {
            return $this->jsonResponse(['error' => 'Không có quyền truy cập'], 403);
        }

        $data = [
            'cv_id' => $experience['cv_id'],
            'company' => $_POST['company'],
            'position' => $_POST['position'],
            'start_date' => $_POST['start_date'],
            'end_date' => empty($_POST['end_date']) ? null : $_POST['end_date'],
            'description' => $_POST['description'] ?? '',
            'is_current' => isset($_POST['is_current']) && $_POST['is_current'] === 'true' ? 1 : 0,
            'order_index' => $_POST['order_index'] ?? $experience['order_index']
        ];

        if ($this->experienceModel->update($id, $data)) {
            $experiences = $this->experienceModel->getByCvId($experience['cv_id']);
            return $this->jsonResponse([
                'success' => true,
                'message' => 'Cập nhật kinh nghiệm làm việc thành công',
                'data' => $experiences
            ]);
        }

        return $this->jsonResponse([
            'error' => 'Không thể cập nhật kinh nghiệm làm việc'
        ], 500);
    }

    public function delete($id)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->jsonResponse(['error' => 'Phương thức không được phép'], 405);
        }

        $experience = $this->experienceModel->getById($id);
        if (!$experience) {
            return $this->jsonResponse(['error' => 'Không tìm thấy kinh nghiệm làm việc'], 404);
        }

        $cv = $this->cvModel->getCVById($experience['cv_id']);
        if (!$this->verifyOwnership($cv, $_SESSION['user_id'])) {
            return $this->jsonResponse(['error' => 'Không có quyền truy cập'], 403);
        }

        if ($this->experienceModel->delete($id, $experience['cv_id'])) {
            return $this->jsonResponse([
                'success' => true,
                'message' => 'Xóa kinh nghiệm làm việc thành công'
            ]);
        }

        return $this->jsonResponse([
            'error' => 'Không thể xóa kinh nghiệm làm việc'
        ], 500);
    }
}
