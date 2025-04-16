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


    public function getTotalActiveSubscriptions()
    {
        $query = "SELECT COUNT(*) as total FROM user_packages 
              WHERE end_date > NOW()";  // Chỉ kiểm tra end_date
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }

    public function getRecentSubscriptions($limit = 5)
    {
        $query = "SELECT up.*, u.full_name as user_name, p.name as package_name 
              FROM user_packages up 
              JOIN users u ON up.user_id = u.id 
              JOIN packages p ON up.package_id = p.id 
              ORDER BY up.created_at DESC LIMIT ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$limit]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getMonthlyRevenue() {
        $query = "SELECT SUM(p.price) as total 
                  FROM user_packages up 
                  JOIN packages p ON up.package_id = p.id 
                  WHERE MONTH(up.created_at) = MONTH(CURRENT_DATE()) 
                  AND YEAR(up.created_at) = YEAR(CURRENT_DATE())";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'] ?? 0;
    }
}
