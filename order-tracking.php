<?php
session_start();
require_once 'config/database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$order_id = $_GET['order_id'] ?? '';

$stmt = $pdo->prepare("
    SELECT o.*, 
           oi.quantity,
           p.name as product_name,
           p.image_url
    FROM orders o
    JOIN order_items oi ON o.id = oi.order_id
    JOIN products p ON oi.product_id = p.id
    WHERE o.id = ? AND o.user_id = ?
");
$stmt->execute([$order_id, $_SESSION['user_id']]);
$orderDetails = $stmt->fetchAll();

$pageTitle = "Track Order";
include 'header.php';
?>

<div class="tracking-container">
    <h1>Track Order #<?php echo htmlspecialchars($order_id); ?></h1>
    
    <div class="tracking-status">
        <?php
        $status = $orderDetails[0]['shipping_status'] ?? 'processing';
        $statusSteps = [
            'processing' => 1,
            'shipped' => 2,
            'out_for_delivery' => 3,
            'delivered' => 4
        ];
        $currentStep = $statusSteps[$status] ?? 1;
        ?>
        
        <div class="tracking-timeline">
            <div class="timeline-step <?php echo $currentStep >= 1 ? 'active' : ''; ?>">
                <i class="fas fa-box"></i>
                <span>Order Processing</span>
            </div>
            <div class="timeline-step <?php echo $currentStep >= 2 ? 'active' : ''; ?>">
                <i class="fas fa-shipping-fast"></i>
                <span>Shipped</span>
            </div>
            <div class="timeline-step <?php echo $currentStep >= 3 ? 'active' : ''; ?>">
                <i class="fas fa-truck"></i>
                <span>Out for Delivery</span>
            </div>
            <div class="timeline-step <?php echo $currentStep >= 4 ? 'active' : ''; ?>">
                <i class="fas fa-home"></i>
                <span>Delivered</span>
            </div>
        </div>
    </div>

    <div class="order-details">
        <h2>Order Details</h2>
        <?php foreach ($orderDetails as $item): ?>
            <div class="order-item">
                <img src="<?php echo htmlspecialchars($item['image_url']); ?>" alt="<?php echo htmlspecialchars($item['product_name']); ?>">
                <div class="item-details">
                    <h3><?php echo htmlspecialchars($item['product_name']); ?></h3>
                    <p>Quantity: <?php echo $item['quantity']; ?></p>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<style>
.tracking-container {
    max-width: 800px;
    margin: 30px auto;
    padding: 20px;
}

.tracking-timeline {
    display: flex;
    justify-content: space-between;
    margin: 40px 0;
    position: relative;
}

.timeline-step {
    text-align: center;
    position: relative;
    flex: 1;
}

.timeline-step i {
    font-size: 24px;
    color: #ccc;
    margin-bottom: 10px;
}

.timeline-step.active i {
    color: #2D336B;
}

.order-item {
    display: flex;
    margin: 20px 0;
    padding: 15px;
    border: 1px solid #eee;
    border-radius: 8px;
}

.order-item img {
    width: 100px;
    height: 100px;
    object-fit: cover;
    margin-right: 20px;
}
</style> 