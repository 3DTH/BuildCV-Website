<?php
class Skill {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function add($data) {
        $query = "INSERT INTO skills (cv_id, name, level, order_index) 
                  VALUES (:cv_id, :name, :level, :order_index)";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':cv_id', $data['cv_id']);
        $stmt->bindParam(':name', $data['name']);
        $stmt->bindParam(':level', $data['level']);
        $stmt->bindParam(':order_index', $data['order_index']);
        
        return $stmt->execute();
    }

    public function getById($id) {
        $query = "SELECT * FROM skills WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getByCvId($cv_id) {
        $query = "SELECT * FROM skills WHERE cv_id = :cv_id ORDER BY order_index ASC";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':cv_id', $cv_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function update($id, $data) {
        $query = "UPDATE skills SET 
                  name = :name,
                  level = :level,
                  order_index = :order_index
                  WHERE id = :id AND cv_id = :cv_id";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':name', $data['name']);
        $stmt->bindParam(':level', $data['level']);
        $stmt->bindParam(':order_index', $data['order_index']);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':cv_id', $data['cv_id']);
        
        return $stmt->execute();
    }

    public function delete($id, $cv_id) {
        $query = "DELETE FROM skills WHERE id = :id AND cv_id = :cv_id";
        $stmt = $this->db->prepare($query);
        return $stmt->execute(['id' => $id, 'cv_id' => $cv_id]);
    }
}