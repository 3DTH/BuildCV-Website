<?php
class Language {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function add($data) {
        $query = "INSERT INTO languages (cv_id, name, proficiency, order_index) 
                  VALUES (:cv_id, :name, :proficiency, :order_index)";
        
        $stmt = $this->db->prepare($query);
        return $stmt->execute($data);
    }
    
    public function getById($id) {
        $query = "SELECT * FROM languages WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getByCvId($cv_id) {
        $query = "SELECT * FROM languages WHERE cv_id = :cv_id ORDER BY order_index ASC";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':cv_id', $cv_id);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function update($id, $data) {
        $query = "UPDATE languages SET 
                  name = :name,
                  proficiency = :proficiency,
                  order_index = :order_index
                  WHERE id = :id AND cv_id = :cv_id";
        
        $stmt = $this->db->prepare($query);
        $data['id'] = $id;
        return $stmt->execute($data);
    }

    public function delete($id, $cv_id) {
        $query = "DELETE FROM languages WHERE id = :id AND cv_id = :cv_id";
        $stmt = $this->db->prepare($query);
        return $stmt->execute(['id' => $id, 'cv_id' => $cv_id]);
    }
}