<?php
class Project {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function add($data) {
        $query = "INSERT INTO projects (cv_id, title, role, start_date, end_date, description, url, order_index) 
                  VALUES (:cv_id, :title, :role, :start_date, :end_date, :description, :url, :order_index)";
        
        $stmt = $this->db->prepare($query);
        return $stmt->execute($data);
    }

    public function getByCvId($cv_id) {
        $query = "SELECT * FROM projects WHERE cv_id = :cv_id ORDER BY order_index ASC";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':cv_id', $cv_id);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function update($id, $data) {
        $query = "UPDATE projects SET 
                  title = :title,
                  role = :role,
                  start_date = :start_date,
                  end_date = :end_date,
                  description = :description,
                  url = :url,
                  order_index = :order_index
                  WHERE id = :id AND cv_id = :cv_id";
        
        $stmt = $this->db->prepare($query);
        $data['id'] = $id;
        return $stmt->execute($data);
    }

    public function delete($id, $cv_id) {
        $query = "DELETE FROM projects WHERE id = :id AND cv_id = :cv_id";
        $stmt = $this->db->prepare($query);
        return $stmt->execute(['id' => $id, 'cv_id' => $cv_id]);
    }
}