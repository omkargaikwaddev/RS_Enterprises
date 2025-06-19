<?php
session_start();
require_once 'config/database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Fetch cart total and user details
$stmt = $pdo->prepare("
    SELECT SUM(c.quantity * p.price) as subtotal 
    FROM cart c 
    JOIN products p ON c.product_id = p.id 
    WHERE c.user_id = ?
");
$stmt->execute([$_SESSION['user_id']]);
$result = $stmt->fetch();
$subtotal = $result['subtotal'] ?? 0;

// Calculate GST and total
$gst = $subtotal * 0.05;
$total = $subtotal + $gst;

// Fetch user details
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

$pageTitle = "Payment";
include 'header.php';
?>

<style>
    .payment-container {
        max-width: 800px;
        margin: 50px auto;
        padding: 30px;
        background: white;
        border-radius: 10px;
        box-shadow: 0 0 20px rgba(0,0,0,0.1);
    }

    .payment-header {
        text-align: center;
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 2px solid #eee;
    }

    .payment-methods {
        display: grid;
        gap: 20px;
        margin: 30px 0;
    }

    .payment-method {
        padding: 20px;
        border: 2px solid #eee;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .payment-method:hover {
        border-color: #2D336B;
    }

    .payment-method.selected {
        border-color: #2D336B;
        background: #f8f9fa;
    }

    .order-summary {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 30px;
    }

    .amount-breakdown {
        margin-top: 15px;
    }
    
    .amount-breakdown p {
        display: flex;
        justify-content: space-between;
        margin: 10px 0;
        color: #666;
    }
    
    .total-amount {
        display: flex;
        justify-content: space-between;
        font-size: 24px;
        color: #2D336B;
        font-weight: bold;
        margin-top: 15px;
        padding-top: 15px;
        border-top: 2px solid #eee;
    }

    .payment-button {
        background: linear-gradient(45deg, #2D336B, #4B0082);
        color: white;
        padding: 15px 30px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 18px;
        width: 100%;
        margin-top: 20px;
        transition: transform 0.3s ease;
    }

    .payment-button:hover {
        transform: translateY(-2px);
    }

    .shipping-address {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 20px;
    }

    .upi-details {
        display: none;
        padding: 20px;
        background: #fff;
        border-radius: 8px;
        margin-top: 20px;
    }

    .upi-details.active {
        display: block;
    }

    .upi-qr {
        text-align: center;
        margin: 20px 0;
    }

    .upi-qr img {
        max-width: 200px;
        margin: 0 auto;
    }

    .upi-id {
        text-align: center;
        background: #f8f9fa;
        padding: 15px;
        border-radius: 5px;
        margin: 15px 0;
        font-family: monospace;
        font-size: 18px;
    }

    .copy-button {
        background: #2D336B;
        color: white;
        border: none;
        padding: 8px 15px;
        border-radius: 5px;
        cursor: pointer;
        margin-left: 10px;
    }

    .payment-status {
        text-align: center;
        margin: 20px 0;
        padding: 15px;
        border-radius: 5px;
        display: none;
    }

    .payment-status.pending {
        background: #fff3cd;
        color: #856404;
        display: block;
    }

    .payment-status.success {
        background: #d4edda;
        color: #155724;
        display: block;
    }

    .timer {
        text-align: center;
        font-size: 20px;
        margin: 15px 0;
        color: #2D336B;
    }
</style>

<div class="payment-container">
    <div class="payment-header">
        <h1>Payment Details</h1>
        <p>Complete your purchase securely</p>
    </div>

    <div class="order-summary">
        <h2>Order Summary</h2>
        <div class="amount-breakdown">
            <p>Subtotal: ₹<?php echo number_format($subtotal, 2); ?></p>
            <p>GST (5%): ₹<?php echo number_format($gst, 2); ?></p>
            <div class="total-amount">
                Total Amount: ₹<?php echo number_format($total, 2); ?>
            </div>
        </div>
    </div>

    <div class="shipping-address">
        <h2>Shipping Address</h2>
        <?php if ($user['address']): ?>
            <p><?php echo htmlspecialchars($user['name']); ?></p>
            <p><?php echo htmlspecialchars($user['address']); ?></p>
            <p>Phone: <?php echo htmlspecialchars($user['phone']); ?></p>
        <?php else: ?>
            <p>Please <a href="profile.php" style="color: #2D336B;">update your profile</a> with shipping details first.</p>
        <?php endif; ?>
    </div>

    <form id="payment-form" method="POST" action="process-payment.php">
        <div class="payment-methods">
            <div class="payment-method" onclick="selectPayment('cod')">
                <input type="radio" name="payment_method" value="cod" id="cod">
                <label for="cod">Cash on Delivery (COD)</label>
            </div>
            <div class="payment-method" onclick="selectPayment('upi')">
                <input type="radio" name="payment_method" value="upi" id="upi">
                <label for="upi">UPI Payment</label>
            </div>
        </div>

        <div class="upi-details" id="upiDetails">
            <h3>Pay using UPI</h3>
            <div class="upi-qr">
                <img src="generate-qr.php?amount=<?php echo $total; ?>" alt="UPI QR Code">
            </div>
            <div class="upi-id">
                yourbusiness@upi
                <button class="copy-button" onclick="copyUpiId()">
                    <i class="fas fa-copy"></i>
                </button>
            </div>
            <div class="timer" id="paymentTimer">
                Time remaining: <span>10:00</span>
            </div>
            <div class="payment-status" id="paymentStatus">
                Waiting for payment...
            </div>
        </div>

        <button type="submit" class="payment-button" id="pay-button" disabled>
            Complete Order
        </button>
    </form>
</div>

<script>
    function selectPayment(method) {
        document.querySelectorAll('.payment-method').forEach(el => {
            el.classList.remove('selected');
        });
        document.querySelector(`#${method}`).closest('.payment-method').classList.add('selected');
        document.querySelector(`#${method}`).checked = true;
        
        const upiDetails = document.getElementById('upiDetails');
        const payButton = document.querySelector('#pay-button');
        
        if (method === 'upi') {
            upiDetails.classList.add('active');
            payButton.textContent = 'Verify Payment';
            startPaymentTimer();
        } else {
            upiDetails.classList.remove('active');
            payButton.textContent = 'Complete Order';
            stopPaymentTimer();
        }
        payButton.disabled = false;
    }

    function copyUpiId() {
        const upiId = 'yourbusiness@upi';
        navigator.clipboard.writeText(upiId).then(() => {
            alert('UPI ID copied to clipboard!');
        });
    }

    let timerInterval;
    function startPaymentTimer() {
        let timeLeft = 600; // 10 minutes in seconds
        const timerDisplay = document.querySelector('#paymentTimer span');
        
        timerInterval = setInterval(() => {
            timeLeft--;
            const minutes = Math.floor(timeLeft / 60);
            const seconds = timeLeft % 60;
            timerDisplay.textContent = `${minutes}:${seconds.toString().padStart(2, '0')}`;
            
            if (timeLeft <= 0) {
                clearInterval(timerInterval);
                document.getElementById('paymentStatus').textContent = 'Payment session expired. Please try again.';
            }
        }, 1000);
    }

    function stopPaymentTimer() {
        clearInterval(timerInterval);
    }

    function checkPaymentStatus() {
        // Simulate payment status check
        let checkCount = 0;
        const statusCheck = setInterval(() => {
            checkCount++;
            fetch('check-payment-status.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    order_id: '<?php echo uniqid(); ?>'
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    clearInterval(statusCheck);
                    document.getElementById('paymentStatus').className = 'payment-status success';
                    document.getElementById('paymentStatus').textContent = 'Payment successful! Redirecting...';
                    setTimeout(() => {
                        window.location.href = 'order-confirmation.php';
                    }, 2000);
                }
            });

            if (checkCount >= 60) { // Stop checking after 5 minutes
                clearInterval(statusCheck);
            }
        }, 5000); // Check every 5 seconds
    }
</script> 