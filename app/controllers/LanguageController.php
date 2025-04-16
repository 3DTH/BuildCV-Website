<?php
class LanguageController extends BaseController {
    private $languageModel;
    private $cvModel;

    public function __construct() {
        $this->checkAuth();
        $this->languageModel = new Language();
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
            'name' => $_POST['name'],
            'proficiency' => $_POST['proficiency'],
            'order_index' => $_POST['order_index'] ?? 0
        ];

        if ($this->languageModel->add($data)) {
            $languages = $this->languageModel->getByCvId($cv_id);
            return $this->jsonResponse(['success' => true, 'data' => $languages]);
        }

        return $this->jsonResponse(['error' => 'Không thể thêm ngôn ngữ'], 500);
    }

    public function get($id) {
        $language = $this->languageModel->getById($id);
        if (!$language) {
            return $this->jsonResponse(['error' => 'Không tìm thấy ngoại ngữ'], 404);
        }
    
        $cv = $this->cvModel->getCVById($language['cv_id']);
        if (!$this->verifyOwnership($cv, $_SESSION['user_id'])) {
            return $this->jsonResponse(['error' => 'Không có quyền truy cập'], 403);
        }
    
        return $this->jsonResponse([
            'success' => true,
            'data' => $language
        ]);
    }

    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->jsonResponse(['error' => 'Phương thức không được phép'], 405);
        }
    
        $language = $this->languageModel->getById($id);
        if (!$language) {
            return $this->jsonResponse(['error' => 'Không tìm thấy ngoại ngữ'], 404);
        }
    
        $cv = $this->cvModel->getCVById($language['cv_id']);
        if (!$this->verifyOwnership($cv, $_SESSION['user_id'])) {
            return $this->jsonResponse(['error' => 'Không có quyền truy cập'], 403);
        }
    
        $data = [
            'cv_id' => $language['cv_id'],
            'name' => $_POST['name'],
            'proficiency' => $_POST['proficiency'],
            'order_index' => $_POST['order_index'] ?? $language['order_index']
        ];
    
        if ($this->languageModel->update($id, $data)) {
            $languages = $this->languageModel->getByCvId($language['cv_id']);
            return $this->jsonResponse([
                'success' => true,
                'data' => $languages
            ]);
        }
    
        return $this->jsonResponse(['error' => 'Không thể cập nhật ngoại ngữ'], 500);
    }

    public function delete($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->jsonResponse(['error' => 'Phương thức không được phép'], 405);
        }
    
        $language = $this->languageModel->getById($id);
        if (!$language) {
            return $this->jsonResponse(['error' => 'Không tìm thấy ngoại ngữ'], 404);
        }
    
        $cv = $this->cvModel->getCVById($language['cv_id']);
        if (!$this->verifyOwnership($cv, $_SESSION['user_id'])) {
            return $this->jsonResponse(['error' => 'Không có quyền truy cập'], 403);
        }
    
        if ($this->languageModel->delete($id, $language['cv_id'])) {
            return $this->jsonResponse([
                'success' => true,
                'message' => 'Xóa ngoại ngữ thành công'
            ]);
        }
    
        return $this->jsonResponse(['error' => 'Không thể xóa ngoại ngữ'], 500);
    }
}