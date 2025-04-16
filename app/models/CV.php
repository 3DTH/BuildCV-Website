
<?php
class CV {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    // Tạo CV mới
    public function createCV($data) {
        $query = "INSERT INTO cvs (user_id, template_id, title, summary, is_public) 
                  VALUES (:user_id, :template_id, :title, :summary, :is_public)";
        
        $stmt = $this->db->prepare($query);
        
        $stmt->bindParam(':user_id', $data['user_id']);
        $stmt->bindParam(':template_id', $data['template_id']);
        $stmt->bindParam(':title', $data['title']);
        $stmt->bindParam(':summary', $data['summary']);
        $stmt->bindParam(':is_public', $data['is_public']);
        
        if ($stmt->execute()) {
            return $this->db->lastInsertId();
        }
        return false;
    }

    // Lấy CV theo ID
    public function getCVById($id) {
        $query = "SELECT cv.*, u.username, u.full_name, t.name as template_name 
                  FROM cvs cv 
                  JOIN users u ON cv.user_id = u.id 
                  JOIN templates t ON cv.template_id = t.id 
                  WHERE cv.id = :id";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch();
    }

    // Lấy tất cả các CV của user
    public function getUserCVs($user_id) {
        $query = "SELECT cv.*, t.name as template_name 
                  FROM cvs cv 
                  JOIN templates t ON cv.template_id = t.id 
                  WHERE cv.user_id = :user_id 
                  ORDER BY cv.updated_at DESC";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Cập nhật CV
    public function updateCV($cv_id, $data) {
        try {
            $sql = "UPDATE cvs SET 
                    title = :title,
                    summary = :summary,
                    is_public = :is_public,
                    updated_at = NOW()
                    WHERE id = :id";
                    
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                ':title' => $data['title'],
                ':summary' => $data['summary'],
                ':is_public' => $data['is_public'],
                ':id' => $cv_id
            ]);
        } catch (PDOException $e) {
            error_log('CV Update Error: ' . $e->getMessage());
            return false;
        }
    }

    // Xóa CV
    public function deleteCV($id, $user_id) {
        $query = "DELETE FROM cvs WHERE id = :id AND user_id = :user_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':user_id', $user_id);
        return $stmt->execute();
    }

    // Lấy các CV public
    public function getPublicCVs($limit = 10, $offset = 0) {
        $query = "SELECT cv.*, u.username, u.full_name, t.name as template_name 
                  FROM cvs cv 
                  JOIN users u ON cv.user_id = u.id 
                  JOIN templates t ON cv.template_id = t.id 
                  WHERE cv.is_public = TRUE 
                  ORDER BY cv.updated_at DESC 
                  LIMIT :limit OFFSET :offset";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getTotalCVs() {
        $query = "SELECT COUNT(*) as total FROM cvs";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }
}