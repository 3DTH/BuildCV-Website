
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
    public function updateCV($id, $data) {
        $query = "UPDATE cvs 
                  SET template_id = :template_id, 
                      title = :title, 
                      summary = :summary, 
                      is_public = :is_public 
                  WHERE id = :id AND user_id = :user_id";
        
        $stmt = $this->db->prepare($query);
        
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':user_id', $data['user_id']);
        $stmt->bindParam(':template_id', $data['template_id']);
        $stmt->bindParam(':title', $data['title']);
        $stmt->bindParam(':summary', $data['summary']);
        $stmt->bindParam(':is_public', $data['is_public']);
        
        return $stmt->execute();
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

    // Các phương thức cho sections của CV
    // Thêm giáo dục
    public function addEducation($cv_id, $data) {
        $query = "INSERT INTO education (cv_id, institution, degree, field_of_study, start_date, end_date, description, order_index) 
                  VALUES (:cv_id, :institution, :degree, :field_of_study, :start_date, :end_date, :description, :order_index)";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':cv_id', $cv_id);
        $stmt->bindParam(':institution', $data['institution']);
        $stmt->bindParam(':degree', $data['degree']);
        $stmt->bindParam(':field_of_study', $data['field_of_study']);
        $stmt->bindParam(':start_date', $data['start_date']);
        $stmt->bindParam(':end_date', $data['end_date']);
        $stmt->bindParam(':description', $data['description']);
        $stmt->bindParam(':order_index', $data['order_index']);
        
        return $stmt->execute();
    }

    // Lấy giáo dục
    public function getEducation($cv_id) {
        $query = "SELECT * FROM education WHERE cv_id = :cv_id ORDER BY order_index ASC";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':cv_id', $cv_id);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Thêm kinh nghiệm
    public function addExperience($cv_id, $data) {
        $query = "INSERT INTO experiences (cv_id, company, position, start_date, end_date, is_current, description, order_index) 
                  VALUES (:cv_id, :company, :position, :start_date, :end_date, :is_current, :description, :order_index)";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':cv_id', $cv_id);
        $stmt->bindParam(':company', $data['company']);
        $stmt->bindParam(':position', $data['position']);
        $stmt->bindParam(':start_date', $data['start_date']);
        $stmt->bindParam(':end_date', $data['end_date']);
        $stmt->bindParam(':is_current', $data['is_current']);
        $stmt->bindParam(':description', $data['description']);
        $stmt->bindParam(':order_index', $data['order_index']);
        
        return $stmt->execute();
    }

    // Lấy kinh nghiệm
    public function getExperiences($cv_id) {
        $query = "SELECT * FROM experiences WHERE cv_id = :cv_id ORDER BY order_index ASC";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':cv_id', $cv_id);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Thêm kỹ năng
    public function addSkill($cv_id, $data) {
        $query = "INSERT INTO skills (cv_id, name, level, order_index) 
                  VALUES (:cv_id, :name, :level, :order_index)";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':cv_id', $cv_id);
        $stmt->bindParam(':name', $data['name']);
        $stmt->bindParam(':level', $data['level']);
        $stmt->bindParam(':order_index', $data['order_index']);
        
        return $stmt->execute();
    }

    // Lấy kỹ năng
    public function getSkills($cv_id) {
        $query = "SELECT * FROM skills WHERE cv_id = :cv_id ORDER BY order_index ASC";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':cv_id', $cv_id);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}