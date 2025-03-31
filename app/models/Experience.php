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
        return $stmt->execute($data);
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
                  WHERE id = :id AND cv_id = :cv_id";
        
        $stmt = $this->db->prepare($query);
        $data['id'] = $id;
        return $stmt->execute($data);
    }

    public function delete($id, $cv_id) {
        $query = "DELETE FROM experiences WHERE id = :id AND cv_id = :cv_id";
        $stmt = $this->db->prepare($query);
        return $stmt->execute(['id' => $id, 'cv_id' => $cv_id]);
    }
}