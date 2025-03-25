<?php
class Database {
    private $host = 'localhost';
    private $dbname = 'webbuildcv';
    private $username = 'root';
    private $password = '030303zxzx';
    private $conn;
    
    public function getConnection() {
        if (!$this->conn) {
            try {
                $this->conn = new PDO(
                    "mysql:host={$this->host};dbname={$this->dbname};charset=utf8mb4",
                    $this->username,
                    $this->password,
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::ATTR_EMULATE_PREPARES => false
                    ]
                );
            } catch (PDOException $e) {
                die("Kết nối database thất bại: " . $e->getMessage());
            }
        }
        
        return $this->conn;
    }
}