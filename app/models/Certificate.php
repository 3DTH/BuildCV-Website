<?php
class Certificate {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function add($data) {
        $query = "INSERT INTO certificates (cv_id, name, issuer, issue_date, expiry_date, description, order_index) 
                  VALUES (:cv_id, :name, :issuer, :issue_date, :expiry_date, :description, :order_index)";
        
        $stmt = $this->db->prepare($query);
        return $stmt->execute($data);
    }

    public function getByCvId($cv_id) {
        $query = "SELECT * FROM certificates WHERE cv_id = :cv_id ORDER BY order_index ASC";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':cv_id', $cv_id);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function update($id, $data) {
        $query = "UPDATE certificates SET 
                  name = :name,
                  issuer = :issuer,
                  issue_date = :issue_date,
                  expiry_date = :expiry_date,
                  description = :description,
                  order_index = :order_index
                  WHERE id = :id AND cv_id = :cv_id";
        
        $stmt = $this->db->prepare($query);
        $data['id'] = $id;
        return $stmt->execute($data);
    }

    public function delete($id, $cv_id) {
        $query = "DELETE FROM certificates WHERE id = :id AND cv_id = :cv_id";
        $stmt = $this->db->prepare($query);
        return $stmt->execute(['id' => $id, 'cv_id' => $cv_id]);
    }
}