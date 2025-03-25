<?php
class User {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    // Đăng ký
    public function register($data) {
        // Kiểm tra email đã tồn tại
        if ($this->emailExists($data['email'])) {
            return false;
        }

        // Kiểm tra username đã tồn tại
        if ($this->usernameExists($data['username'])) {
            return false;
        }

        $query = "INSERT INTO users (username, email, password, full_name, phone, address) 
                  VALUES (:username, :email, :password, :full_name, :phone, :address)";
        
        $stmt = $this->db->prepare($query);
        
        // Hash mật khẩu
        $hashed_password = password_hash($data['password'], PASSWORD_DEFAULT);
        
        $stmt->bindParam(':username', $data['username']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':password', $hashed_password);
        $stmt->bindParam(':full_name', $data['full_name']);
        $stmt->bindParam(':phone', $data['phone']);
        $stmt->bindParam(':address', $data['address']);
        
        if ($stmt->execute()) {
            return $this->db->lastInsertId();
        }
        return false;
    }

    // Đăng nhập
    public function login($username, $password) {
        $query = "SELECT * FROM users WHERE username = :username OR email = :email";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $username);
        $stmt->execute();
        
        $user = $stmt->fetch();
        
        if ($user && password_verify($password, $user['password'])) {
            unset($user['password']); // Không trả về mật khẩu
            return $user;
        }
        return false;
    }

    // Lấy user theo ID
    public function getUserById($id) {
        $query = "SELECT id, username, email, role, full_name, phone, address, profile_picture, created_at 
                  FROM users WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch();
    }

    // Cập nhật profile
    public function updateProfile($id, $data) {
        $query = "UPDATE users 
                  SET full_name = :full_name, 
                      phone = :phone, 
                      address = :address";
        
        $params = [
            ':id' => $id,
            ':full_name' => $data['full_name'],
            ':phone' => $data['phone'],
            ':address' => $data['address']
        ];

        // Nếu có cập nhật ảnh đại diện
        if (isset($data['profile_picture'])) {
            $query .= ", profile_picture = :profile_picture";
            $params[':profile_picture'] = $data['profile_picture'];
        }

        $query .= " WHERE id = :id";
        
        $stmt = $this->db->prepare($query);
        return $stmt->execute($params);
    }

    // Thay đổi mật khẩu
    public function changePassword($id, $current_password, $new_password) {
        // Kiểm tra mật khẩu hiện tại
        $query = "SELECT password FROM users WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        $user = $stmt->fetch();
        
        if (!$user || !password_verify($current_password, $user['password'])) {
            return false;
        }

        // Cập nhật mật khẩu mới
        $query = "UPDATE users SET password = :password WHERE id = :id";
        $stmt = $this->db->prepare($query);
        
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $stmt->bindParam(':password', $hashed_password);
        $stmt->bindParam(':id', $id);
        
        return $stmt->execute();
    }

    // Kiểm tra email tồn tại
    private function emailExists($email) {
        $query = "SELECT id FROM users WHERE email = :email";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch() !== false;
    }

    // Kiểm tra username tồn tại
    private function usernameExists($username) {
        $query = "SELECT id FROM users WHERE username = :username";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        return $stmt->fetch() !== false;
    }

    // Phương thức cho gói dịch vụ
    // Lấy gói dịch vụ của user
    public function getUserPackages($user_id) {
        $query = "SELECT up.*, p.name as package_name, p.description, p.price 
                  FROM user_packages up 
                  JOIN packages p ON up.package_id = p.id 
                  WHERE up.user_id = :user_id AND up.is_active = TRUE 
                  AND (up.end_date IS NULL OR up.end_date > NOW())";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Kiểm tra user có gói dịch vụ active
    public function hasActivePackage($user_id) {
        $query = "SELECT COUNT(*) FROM user_packages 
                  WHERE user_id = :user_id 
                  AND is_active = TRUE 
                  AND (end_date IS NULL OR end_date > NOW())";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }

    // Lấy số lượng export còn lại
    public function getRemainingExports($user_id) {
        $query = "SELECT remaining_exports FROM user_packages 
                  WHERE user_id = :user_id 
                  AND is_active = TRUE 
                  AND (end_date IS NULL OR end_date > NOW()) 
                  ORDER BY end_date DESC 
                  LIMIT 1";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        return $stmt->fetchColumn();
    }
}