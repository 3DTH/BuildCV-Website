<?php
class Template {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    // Lấy tất cả các template
    public function getAllTemplates() {
        $query = "SELECT * FROM templates ORDER BY created_at DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Lấy template theo ID
    public function getTemplateById($id) {
        $query = "SELECT * FROM templates WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch();
    }

    // Lấy các template miễn phí
    public function getFreeTemplates() {
        $query = "SELECT * FROM templates WHERE is_premium = FALSE ORDER BY created_at DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Lấy các template premium
    public function getPremiumTemplates() {
        $query = "SELECT * FROM templates WHERE is_premium = TRUE ORDER BY created_at DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Tạo template mới
    public function createTemplate($data) {
        $query = "INSERT INTO templates (name, description, thumbnail, css_file, is_premium) 
                  VALUES (:name, :description, :thumbnail, :css_file, :is_premium)";
        
        $stmt = $this->db->prepare($query);
        
        $stmt->bindParam(':name', $data['name']);
        $stmt->bindParam(':description', $data['description']);
        $stmt->bindParam(':thumbnail', $data['thumbnail']);
        $stmt->bindParam(':css_file', $data['css_file']);
        $stmt->bindParam(':is_premium', $data['is_premium']);
        
        return $stmt->execute();
    }

    // Cập nhật template
    public function updateTemplate($id, $data) {
        $query = "UPDATE templates 
                  SET name = :name, 
                      description = :description, 
                      thumbnail = :thumbnail, 
                      css_file = :css_file, 
                      is_premium = :is_premium 
                  WHERE id = :id";
        
        $stmt = $this->db->prepare($query);
        
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':name', $data['name']);
        $stmt->bindParam(':description', $data['description']);
        $stmt->bindParam(':thumbnail', $data['thumbnail']);
        $stmt->bindParam(':css_file', $data['css_file']);
        $stmt->bindParam(':is_premium', $data['is_premium']);
        
        return $stmt->execute();
    }

    // Xóa template
    public function deleteTemplate($id) {
        $query = "DELETE FROM templates WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}