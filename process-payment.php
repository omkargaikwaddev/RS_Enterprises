<?php
session_start();
require_once 'config/database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$payment_method = $_POST['payment_method'] ?? '';
$order_id = uniqid('ORD');

try {
    $pdo->beginTransaction();
    
    // Get cart total
    $stmt = $pdo->prepare("
        SELECT SUM(c.quantity * p.price) as subtotal 
        FROM cart c 
        JOIN products p ON c.product_id = p.id 
        WHERE c.user_id = ?
    ");
    $stmt->execute([$_SESSION['user_id']]);
    $result = $stmt->fetch();
    $subtotal = $result['subtotal'] ?? 0;
    $gst = $subtotal * 0.05;
    $total = $subtotal + $gst;

    // Create order
    $stmt = $pdo->prepare("
        INSERT INTO orders (id, user_id, total_amount, status, payment_method) 
        VALUES (?, ?, ?, ?, ?)
    ");
    $status = ($payment_method === 'cod') ? 'pending' : 'processing';
    $stmt->execute([$order_id, $_SESSION['user_id'], $total, $status, $payment_method]);

    // Move cart items to order items
    $stmt = $pdo->prepare("
        INSERT INTO order_items (order_id, product_id, quantity, price)
        SELECT ?, product_id, quantity, 
        (SELECT price FROM products WHERE id = cart.product_id)
        FROM cart WHERE user_id = ?
    ");
    $stmt->execute([$order_id, $_SESSION['user_id']]);

    // Clear cart
    $stmt = $pdo->prepare("DELETE FROM cart WHERE user_id = ?");
    $stmt->execute([$_SESSION['user_id']]);

    $pdo->commit();
    
    if ($payment_method === 'cod') {
        header('Location: order-success.php?order_id=' . $order_id);
    } else {
        echo json_encode(['success' => true, 'order_id' => $order_id]);
    }
} catch (Exception $e) {
    $pdo->rollBack();
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
} 