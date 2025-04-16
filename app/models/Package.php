<?php
class Package
{
    private $db;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    // Lấy tất cả các gói
    public function getAllPackages()
    {
        $query = "SELECT * FROM packages WHERE is_active = TRUE ORDER BY price ASC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Lấy gói theo ID
    public function getPackageById($id)
    {
        $query = "SELECT * FROM packages WHERE id = :id AND is_active = TRUE";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch();
    }

    // Tạo gói mới cho người dùng
    public function createUserPackage($data)
    {
        $query = "INSERT INTO user_packages (user_id, package_id, end_date, remaining_exports, is_active) 
                  VALUES (:user_id, :package_id, :end_date, :remaining_exports, :is_active)";
        $stmt = $this->db->prepare($query);
        return $stmt->execute($data);
    }

    // Lấy gói của người dùng đang sử dụng
    public function getUserActivePackage($user_id)
    {
        $query = "SELECT up.*, p.name, p.description, p.price 
                  FROM user_packages up 
                  JOIN packages p ON p.id = up.package_id 
                  WHERE up.user_id = :user_id 
                  AND up.is_active = TRUE 
                  AND up.end_date > NOW() 
                  ORDER BY up.end_date DESC 
                  LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['user_id' => $user_id]);
        $result = $stmt->fetch();
        return $result ? $result : null;
    }

}
