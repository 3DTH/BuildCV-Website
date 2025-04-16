<?php
class Education {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function add($data) {
        $query = "INSERT INTO education (cv_id, institution, degree, field_of_study, start_date, end_date, description, order_index) 
                  VALUES (:cv_id, :institution, :degree, :field_of_study, :start_date, :end_date, :description, :order_index)";
        
        $stmt = $this->db->prepare($query);
        return $stmt->execute($data);
    }

    public function getById($id) {
        $query = "SELECT * FROM education WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }


    public function getByCvId($cv_id) {
        $query = "SELECT * FROM education WHERE cv_id = :cv_id ORDER BY order_index ASC";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':cv_id', $cv_id);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function update($id, $data) {
        $query = "UPDATE education SET 
                  institution = :institution,
                  degree = :degree,
                  field_of_study = :field_of_study,
                  start_date = :start_date,
                  end_date = :end_date,
                  description = :description,
                  order_index = :order_index
                  WHERE id = :id";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':institution', $data['institution']);
        $stmt->bindParam(':degree', $data['degree']);
        $stmt->bindParam(':field_of_study', $data['field_of_study']);
        $stmt->bindParam(':start_date', $data['start_date']);
        $stmt->bindParam(':end_date', $data['end_date']);
        $stmt->bindParam(':description', $data['description']);
        $stmt->bindParam(':order_index', $data['order_index']);
        $stmt->bindParam(':id', $id);
        
        return $stmt->execute();
    }

    public function delete($id, $cv_id) {
        $query = "DELETE FROM education WHERE id = :id AND cv_id = :cv_id";
        $stmt = $this->db->prepare($query);
        return $stmt->execute(['id' => $id, 'cv_id' => $cv_id]);
    }
}