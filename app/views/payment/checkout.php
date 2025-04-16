<?php
$title = 'Thanh toán - BuildCV';
require_once 'app/views/layouts/header.php';
?>

<div class="payment-container">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title text-center mb-4">Thanh toán gói <?= htmlspecialchars($package['name']) ?></h3>
                        
                        <div class="payment-details mb-4">
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Gói dịch vụ:</strong> <?= htmlspecialchars($package['name']) ?></p>
                                    <p><strong>Thời hạn:</strong> <?= $package['duration_days'] ?> ngày</p>
                                </div>
                                <div class="col-md-6 text-md-end">
                                    <p><strong>Số tiền:</strong> <?= number_format($package['price'], 0, ',', '.') ?> VND</p>
                                </div>
                            </div>
                        </div>

                        <div class="payment-timer text-center mb-4">
                            <p>Thời gian còn lại để thanh toán: <span id="countdown">15:00</span></p>
                        </div>

                        <div id="paypal-button-container" class="mt-4"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://www.paypal.com/sdk/js?client-id=<?= $paypalClientId ?>&currency=USD"></script>
<script src="<?= BASE_URL ?>/assets/js/payment.js"></script>
<script>
paypal.Buttons({
    createOrder: function(data, actions) {
        return fetch('<?= BASE_URL ?>/payment/create-order/<?= $package['id'] ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => data.id);
    },
    onApprove: function(data, actions) {
        return fetch('<?= BASE_URL ?>/payment/capture-order/' + data.orderID, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            }
        })
        .then(res => res.json())
        .then(function(data) {
            if (data.status === 'success') {
                window.location.href = '<?= BASE_URL ?>/payment/success';
            }
        });
    },
    onError: function(err) {
        window.location.href = '<?= BASE_URL ?>/payment/error';
    }
}).render('#paypal-button-container');
</script>

<?php require_once 'app/views/layouts/footer.php'; ?>