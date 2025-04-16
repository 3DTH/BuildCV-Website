<?php
class ContactInfo {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function add($data) {
        $query = "INSERT INTO contact_info (cv_id, type, value, is_primary, order_index) 
                  VALUES (:cv_id, :type, :value, :is_primary, :order_index)";
        
        $stmt = $this->db->prepare($query);
        return $stmt->execute($data);
    }

    public function getById($id) {
        $query = "SELECT * FROM contact_info WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getByCvId($cv_id) {
        $query = "SELECT * FROM contact_info WHERE cv_id = :cv_id ORDER BY order_index ASC";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':cv_id', $cv_id);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function update($id, $data) {
        $query = "UPDATE contact_info SET 
                  type = :type,
                  value = :value,
                  is_primary = :is_primary,
                  order_index = :order_index
                  WHERE id = :id AND cv_id = :cv_id";
        
        $stmt = $this->db->prepare($query);
        $data['id'] = $id;
        return $stmt->execute($data);
    }

    public function delete($id, $cv_id) {
        $query = "DELETE FROM contact_info WHERE id = :id AND cv_id = :cv_id";
        $stmt = $this->db->prepare($query);
        return $stmt->execute(['id' => $id, 'cv_id' => $cv_id]);
    }

    public function setPrimary($id, $cv_id, $type) {
        // First, remove primary status from all contacts of the same type
        $query1 = "UPDATE contact_info SET is_primary = 0 
                   WHERE cv_id = :cv_id AND type = :type";
        $stmt1 = $this->db->prepare($query1);
        $stmt1->execute(['cv_id' => $cv_id, 'type' => $type]);

        // Then set the selected contact as primary
        $query2 = "UPDATE contact_info SET is_primary = 1 
                   WHERE id = :id AND cv_id = :cv_id";
        $stmt2 = $this->db->prepare($query2);
        return $stmt2->execute(['id' => $id, 'cv_id' => $cv_id]);
    }
}