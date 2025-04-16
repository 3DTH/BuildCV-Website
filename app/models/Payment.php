<?php
class Payment {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function createPayment($data) {
        $query = "INSERT INTO payments (user_id, package_id, amount, payment_id, status, currency) 
                 VALUES (:user_id, :package_id, :amount, :payment_id, :status, :currency)";
        $stmt = $this->db->prepare($query);
        return $stmt->execute($data);
    }

    public function updatePayment($payment_id, $data) {
        $query = "UPDATE payments SET 
                 payer_id = :payer_id,
                 payer_email = :payer_email,
                 status = :status,
                 updated_at = CURRENT_TIMESTAMP
                 WHERE payment_id = :payment_id";
        $stmt = $this->db->prepare($query);
        return $stmt->execute($data);
    }

    public function getPaymentByPaymentId($payment_id) {
        $query = "SELECT * FROM payments WHERE payment_id = :payment_id";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['payment_id' => $payment_id]);
        return $stmt->fetch();
    }
}