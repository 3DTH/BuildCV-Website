<?php
class EducationController extends BaseController {
    private $educationModel;
    private $cvModel;

    public function __construct() {
        $this->checkAuth();
        $this->educationModel = new Education();
        $this->cvModel = new CV();
    }
    
    public function get($id) {
        $education = $this->educationModel->getById($id);
        if (!$education) {
            http_response_code(404);
            echo json_encode(['error' => 'Không tìm thấy thông tin học vấn']);
            return;
        }

        $cv = $this->cvModel->getCVById($education['cv_id']);
        if (!$this->verifyOwnership($cv, $_SESSION['user_id'])) {
            http_response_code(403);
            echo json_encode(['error' => 'Không có quyền truy cập']);
            return;
        }

        echo json_encode([
            'success' => true,
            'data' => $education
        ]);
    }

    public function add() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['error' => 'Phương thức không được phép']);
            return;
        }

        $cv_id = $_POST['cv_id'];
        $user_id = $_SESSION['user_id'];
        
        $cv = $this->cvModel->getCVById($cv_id);
        if (!$this->verifyOwnership($cv, $user_id)) {
            http_response_code(403);
            echo json_encode(['error' => 'Không có quyền truy cập']);
            return;
        }

        $data = [
            'cv_id' => $cv_id,
            'institution' => $_POST['institution'],
            'degree' => $_POST['degree'],
            'field_of_study' => $_POST['field_of_study'],
            'start_date' => $_POST['start_date'],
            'end_date' => $_POST['end_date'],
            'description' => $_POST['description'],
            'order_index' => $_POST['order_index'] ?? 0
        ];

        if ($this->educationModel->add($data)) {
            $education = $this->educationModel->getByCvId($cv_id);
            echo json_encode([
                'success' => true,
                'data' => $education
            ]);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Không thể thêm thông tin học vấn']);
        }
    }

    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['error' => 'Phương thức không được phép']);
            return;
        }

        $education = $this->educationModel->getById($id);
        if (!$education) {
            http_response_code(404);
            echo json_encode(['error' => 'Không tìm thấy thông tin học vấn']);
            return;
        }

        $cv = $this->cvModel->getCVById($education['cv_id']);
        if (!$this->verifyOwnership($cv, $_SESSION['user_id'])) {
            http_response_code(403);
            echo json_encode(['error' => 'Không có quyền truy cập']);
            return;
        }

        $data = [
            'institution' => $_POST['institution'],
            'degree' => $_POST['degree'],
            'field_of_study' => $_POST['field_of_study'],
            'start_date' => $_POST['start_date'],
            'end_date' => $_POST['end_date'],
            'description' => $_POST['description'],
            'order_index' => $_POST['order_index'] ?? $education['order_index']
        ];

        if ($this->educationModel->update($id, $data)) {
            $education = $this->educationModel->getByCvId($cv['id']);
            echo json_encode([
                'success' => true,
                'data' => $education
            ]);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Không thể cập nhật thông tin học vấn']);
        }
    }

    public function delete($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['error' => 'Phương thức không được phép']);
            return;
        }

        $education = $this->educationModel->getById($id);
        if (!$education) {
            http_response_code(404);
            echo json_encode(['error' => 'Không tìm thấy thông tin học vấn']);
            return;
        }

        $cv = $this->cvModel->getCVById($education['cv_id']);
        if (!$this->verifyOwnership($cv, $_SESSION['user_id'])) {
            http_response_code(403);
            echo json_encode(['error' => 'Không có quyền truy cập']);
            return;
        }

        if ($this->educationModel->delete($id, $education['cv_id'])) {
            echo json_encode([
                'success' => true
            ]);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Không thể xóa thông tin học vấn']);
        }
    }
}