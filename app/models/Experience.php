<?php
class Experience {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function add($data) {
        $query = "INSERT INTO experiences (cv_id, company, position, start_date, end_date, is_current, description, order_index) 
                  VALUES (:cv_id, :company, :position, :start_date, :end_date, :is_current, :description, :order_index)";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':cv_id', $data['cv_id']);
        $stmt->bindParam(':company', $data['company']);
        $stmt->bindParam(':position', $data['position']);
        $stmt->bindParam(':start_date', $data['start_date']);
        $stmt->bindParam(':end_date', $data['end_date']);
        $stmt->bindParam(':is_current', $data['is_current']);
        $stmt->bindParam(':description', $data['description']);
        $stmt->bindParam(':order_index', $data['order_index']);
        
        return $stmt->execute();
    }

    public function getById($id) {
        $sql = "SELECT * FROM experiences WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getByCvId($cv_id) {
        $query = "SELECT * FROM experiences WHERE cv_id = :cv_id ORDER BY order_index ASC";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':cv_id', $cv_id);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function update($id, $data) {
        $query = "UPDATE experiences SET 
                  company = :company,
                  position = :position,
                  start_date = :start_date,
                  end_date = :end_date,
                  is_current = :is_current,
                  description = :description,
                  order_index = :order_index
                  WHERE id = :id";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':company', $data['company']);
        $stmt->bindParam(':position', $data['position']);
        $stmt->bindParam(':start_date', $data['start_date']);
        $stmt->bindParam(':end_date', $data['end_date']);
        $stmt->bindParam(':is_current', $data['is_current']);
        $stmt->bindParam(':description', $data['description']);
        $stmt->bindParam(':order_index', $data['order_index']);
        $stmt->bindParam(':id', $id);
        
        return $stmt->execute();
    }

    public function delete($id, $cv_id) {
        $query = "DELETE FROM experiences WHERE id = :id AND cv_id = :cv_id";
        $stmt = $this->db->prepare($query);
        return $stmt->execute(['id' => $id, 'cv_id' => $cv_id]);
    }
}