<?php
require 'vendor/autoload.php';
use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;
use PayPalCheckoutSdk\Orders\OrdersCaptureRequest;

class PaymentController extends BaseController {
    private $paymentModel;
    private $packageModel;
    private $client;
    private $config;

    public function __construct() {
        $this->checkAuth();
        $this->paymentModel = new Payment();
        $this->packageModel = new Package();
        $this->config = require 'app/config/paypal.php';
        
        $environment = new SandboxEnvironment($this->config['client_id'], $this->config['client_secret']);
        $this->client = new PayPalHttpClient($environment);
    }

    public function createOrder($package_id) {
        $package = $this->packageModel->getPackageById($package_id);
        if (!$package) {
            return $this->jsonResponse(['error' => 'Gói không tồn tại'], 404);
        }

        $request = new OrdersCreateRequest();
        $request->prefer('return=representation');
        $request->body = [
            'intent' => 'CAPTURE',
            'purchase_units' => [[
                'amount' => [
                    'currency_code' => $this->config['currency'],
                    'value' => $package['price']
                ]
            ]]
        ];

        try {
            $response = $this->client->execute($request);
            
            // Lưu thông tin thanh toán
            $this->paymentModel->createPayment([
                'user_id' => $_SESSION['user_id'],
                'package_id' => $package_id,
                'amount' => $package['price'],
                'payment_id' => $response->result->id,
                'status' => 'pending',
                'currency' => $this->config['currency']
            ]);

            return $this->jsonResponse([
                'id' => $response->result->id
            ]);
        } catch (Exception $e) {
            return $this->jsonResponse(['error' => $e->getMessage()], 500);
        }
    }

    public function captureOrder($orderId) {
        $request = new OrdersCaptureRequest($orderId);

        try {
            $response = $this->client->execute($request);
            
            // Cập nhật trạng thái thanh toán
            $this->paymentModel->updatePayment($orderId, [
                'payment_id' => $orderId,
                'payer_id' => $response->result->payer->payer_id,
                'payer_email' => $response->result->payer->email_address,
                'status' => 'completed'
            ]);

            // Kích hoạt gói premium cho user
            $payment = $this->paymentModel->getPaymentByPaymentId($orderId);
            $this->activateUserPackage($payment);

            return $this->jsonResponse(['status' => 'success']);
        } catch (Exception $e) {
            return $this->jsonResponse(['error' => $e->getMessage()], 500);
        }
    }

    private function activateUserPackage($payment) {
        $package = $this->packageModel->getPackageById($payment['package_id']);
        $end_date = date('Y-m-d H:i:s', strtotime('+' . $package['duration_days'] . ' days'));
        
        $data = [
            'user_id' => $payment['user_id'],
            'package_id' => $payment['package_id'],
            'end_date' => $end_date,
            'remaining_exports' => $package['export_limit'],
            'is_active' => true
        ];
        
        $this->packageModel->createUserPackage($data);
    }

    public function checkout($package_id) {
        $package = $this->packageModel->getPackageById($package_id);
        if (!$package) {
            $_SESSION['error'] = "Gói dịch vụ không tồn tại!";
            header('Location: ' . BASE_URL . '/packages');
            exit;
        }

        $paypalClientId = $this->config['client_id'];
        require_once 'app/views/payment/checkout.php';
    }

    public function success() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . '/auth/login');
            exit;
        }
        require_once 'app/views/payment/success.php';
    }

    public function error() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . '/auth/login');
            exit;
        }
        require_once 'app/views/payment/error.php';
    }
}