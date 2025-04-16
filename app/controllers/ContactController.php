<?php
class ContactController extends BaseController {
    private $contactModel;
    private $cvModel;

    public function __construct() {
        $this->checkAuth();
        $this->contactModel = new ContactInfo();
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
            'type' => $_POST['type'],
            'value' => $_POST['value'],
            'is_primary' => isset($_POST['is_primary']) ? 1 : 0,
            'order_index' => $_POST['order_index'] ?? 0
        ];

        if ($this->contactModel->add($data)) {
            if ($data['is_primary']) {
                $this->contactModel->setPrimary($data['id'], $cv_id, $data['type']);
            }
            $contacts = $this->contactModel->getByCvId($cv_id);
            return $this->jsonResponse(['success' => true, 'data' => $contacts]);
        }

        return $this->jsonResponse(['error' => 'Không thể thêm thông tin liên hệ'], 500);
    }

    public function get($id) {
        $contact = $this->contactModel->getById($id);
        if (!$contact) {
            return $this->jsonResponse(['error' => 'Không tìm thấy thông tin liên hệ'], 404);
        }

        $cv = $this->cvModel->getCVById($contact['cv_id']);
        if (!$this->verifyOwnership($cv, $_SESSION['user_id'])) {
            return $this->jsonResponse(['error' => 'Không có quyền truy cập'], 403);
        }

        return $this->jsonResponse([
            'success' => true,
            'data' => $contact
        ]);
    }

    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->jsonResponse(['error' => 'Phương thức không được phép'], 405);
        }

        $contact = $this->contactModel->getById($id);
        if (!$contact) {
            return $this->jsonResponse(['error' => 'Không tìm thấy thông tin liên hệ'], 404);
        }

        $cv = $this->cvModel->getCVById($contact['cv_id']);
        if (!$this->verifyOwnership($cv, $_SESSION['user_id'])) {
            return $this->jsonResponse(['error' => 'Không có quyền truy cập'], 403);
        }

        $data = [
            'cv_id' => $contact['cv_id'],
            'type' => $_POST['type'],
            'value' => $_POST['value'],
            'is_primary' => isset($_POST['is_primary']) ? 1 : 0,
            'order_index' => $_POST['order_index'] ?? $contact['order_index']
        ];

        if ($this->contactModel->update($id, $data)) {
            if ($data['is_primary']) {
                $this->contactModel->setPrimary($id, $contact['cv_id'], $data['type']);
            }
            $contacts = $this->contactModel->getByCvId($contact['cv_id']);
            return $this->jsonResponse([
                'success' => true,
                'data' => $contacts
            ]);
        }

        return $this->jsonResponse(['error' => 'Không thể cập nhật thông tin liên hệ'], 500);
    }

    public function delete($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->jsonResponse(['error' => 'Phương thức không được phép'], 405);
        }

        $contact = $this->contactModel->getById($id);
        if (!$contact) {
            return $this->jsonResponse(['error' => 'Không tìm thấy thông tin liên hệ'], 404);
        }

        $cv = $this->cvModel->getCVById($contact['cv_id']);
        if (!$this->verifyOwnership($cv, $_SESSION['user_id'])) {
            return $this->jsonResponse(['error' => 'Không có quyền truy cập'], 403);
        }

        if ($this->contactModel->delete($id, $contact['cv_id'])) {
            return $this->jsonResponse([
                'success' => true,
                'message' => 'Xóa thông tin liên hệ thành công'
            ]);
        }

        return $this->jsonResponse(['error' => 'Không thể xóa thông tin liên hệ'], 500);
    }
}