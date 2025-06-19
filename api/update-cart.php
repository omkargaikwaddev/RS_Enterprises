<?php
session_start();
require_once '../config/database.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit();
}

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['cartId']) || !isset($data['quantity'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid input']);
    exit();
}

$cartId = intval($data['cartId']);
$quantity = max(1, intval($data['quantity']));

try {
    // Begin transaction
    $pdo->beginTransaction();

    // Update cart quantity
    $stmt = $pdo->prepare("
        UPDATE cart 
        SET quantity = ? 
        WHERE id = ? AND user_id = ?
    ");
    $stmt->execute([$quantity, $cartId, $_SESSION['user_id']]);

    // Get item subtotal
    $stmt = $pdo->prepare("
        SELECT (c.quantity * p.price) as item_subtotal
        FROM cart c
        JOIN products p ON c.product_id = p.id
        WHERE c.id = ?
    ");
    $stmt->execute([$cartId]);
    $item = $stmt->fetch(PDO::FETCH_ASSOC);

    // Calculate cart totals
    $stmt = $pdo->prepare("
        SELECT 
            SUM(c.quantity * p.price) as subtotal
        FROM cart c
        JOIN products p ON c.product_id = p.id
        WHERE c.user_id = ?
    ");
    $stmt->execute([$_SESSION['user_id']]);
    $totals = $stmt->fetch(PDO::FETCH_ASSOC);

    $subtotal = floatval($totals['subtotal']);
    $gst = $subtotal * 0.05;
    $total = $subtotal + $gst;

    $pdo->commit();

    echo json_encode([
        'success' => true,
        'subtotal' => number_format($item['item_subtotal'], 2),
        'total' => number_format($total, 2),
        'gst' => number_format($gst, 2)
    ]);

} catch (PDOException $e) {
    $pdo->rollBack();
    error_log($e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => 'Database error occurred'
    ]);
}
?> 