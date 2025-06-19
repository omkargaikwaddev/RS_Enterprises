<?php
session_start();
require_once 'config/database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Fetch cart items
$stmt = $pdo->prepare("SELECT p.*, c.quantity, c.id as cart_id 
                       FROM cart c 
                       JOIN products p ON c.product_id = p.id 
                       WHERE c.user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$cartItems = $stmt->fetchAll();

// Calculate subtotal and GST
$subtotal = 0;
foreach($cartItems as $item) {
    $subtotal += $item['price'] * $item['quantity'];
}

// Calculate GST (5%)
$gst = $subtotal * 0.05;
// Calculate final total
$total = $subtotal + $gst;

$pageTitle = "Checkout";
include 'header.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Checkout - Riddhi Siddhi Enterprises</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        .checkout-wrapper {
            background: #f8f9fa;
            min-height: calc(100vh - 100px);
            padding: 40px 20px;
        }

        .checkout-container {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 30px;
        }

        .cart-section {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.05);
        }

        .summary-section {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.05);
            height: fit-content;
            position: sticky;
            top: 20px;
        }

        .cart-item {
            display: grid;
            grid-template-columns: auto 1fr auto;
            gap: 20px;
            padding: 20px;
            border: 1px solid #eee;
            border-radius: 10px;
            margin-bottom: 15px;
            transition: transform 0.2s;
        }

        .cart-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        }

        .cart-item img {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 8px;
        }

        .quantity-controls {
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 15px 0;
        }

        .quantity-controls button {
            width: 30px;
            height: 30px;
            border: none;
            background: #2D336B;
            color: white;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s;
        }

        .quantity-controls button:hover {
            background: #4B0082;
        }

        .quantity-controls input {
            width: 50px;
            text-align: center;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 5px;
        }

        .summary-section h2 {
            color: #2D336B;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #eee;
        }

        .summary-item {
            display: flex;
            justify-content: space-between;
            margin: 15px 0;
            font-size: 1.1em;
        }

        .total {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 2px solid #eee;
            font-weight: bold;
            font-size: 1.2em;
            color: #2D336B;
        }

        .checkout-button {
            width: 100%;
            padding: 15px;
            background: linear-gradient(45deg, #2D336B, #4B0082);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1.1em;
            cursor: pointer;
            transition: transform 0.3s;
            margin-top: 20px;
        }

        .checkout-button:hover {
            transform: translateY(-2px);
        }

        .empty-cart {
            text-align: center;
            padding: 50px 20px;
        }

        .empty-cart h2 {
            color: #2D336B;
            margin-bottom: 15px;
        }

        .continue-shopping {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background: #2D336B;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background 0.3s;
        }

        @media (max-width: 768px) {
            .checkout-container {
                grid-template-columns: 1fr;
            }
            
            .summary-section {
                position: static;
            }
        }

        .delete-btn {
            background: none;
            border: none;
            color: #dc3545;
            font-size: 1.2em;
            cursor: pointer;
            padding: 8px;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .delete-btn:hover {
            background: #ffebee;
            color: #c62828;
            transform: scale(1.1);
        }

        .delete-btn i {
            transition: transform 0.3s ease;
        }

        .delete-btn:hover i {
            transform: rotate(15deg);
        }
    </style>
</head>
<body>
    <div id="update-message" class="update-message"></div>
    <div class="checkout-wrapper">
        <div class="checkout-container">
            <?php if (empty($cartItems)): ?>
                <div class="empty-cart">
                    <h2>Your cart is empty</h2>
                    <p>Looks like you haven't added anything to your cart yet.</p>
                    <a href="shop.php" class="continue-shopping">Continue Shopping</a>
                </div>
            <?php else: ?>
                <div class="cart-section">
                    <h2>Shopping Cart</h2>
                    <?php foreach($cartItems as $item): ?>
                        <div class="cart-item" id="cart-item-<?php echo $item['cart_id']; ?>">
                            <img src="<?php echo htmlspecialchars($item['image_url']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>">
                            <div class="item-details">
                                <h3><?php echo htmlspecialchars($item['name']); ?></h3>
                                <p>Price: ₹<?php echo number_format($item['price'], 2); ?></p>
                                <div class="quantity-controls">
                                    <button onclick="updateQuantity(<?php echo $item['cart_id']; ?>, 'decrease')">-</button>
                                    <input type="number" id="quantity-<?php echo $item['cart_id']; ?>" 
                                           value="<?php echo $item['quantity']; ?>" min="1"
                                           onchange="updateQuantity(<?php echo $item['cart_id']; ?>, 'set', this.value)">
                                    <button onclick="updateQuantity(<?php echo $item['cart_id']; ?>, 'increase')">+</button>
                                </div>
                                <p>Subtotal: ₹<span id="subtotal-<?php echo $item['cart_id']; ?>"><?php echo number_format($item['price'] * $item['quantity'], 2); ?></span></p>
                            </div>
                            <button class="delete-btn" onclick="removeItem(<?php echo $item['cart_id']; ?>)">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <div class="summary-section">
                    <h2>Order Summary</h2>
                    <div class="cart-summary">
                        <div class="summary-item">
                            <span>Subtotal:</span>
                            <span>₹<?php echo number_format($subtotal, 2); ?></span>
                        </div>
                        <div class="summary-item">
                            <span>GST (5%):</span>
                            <span id="gst-amount">₹<?php echo number_format($gst, 2); ?></span>
                        </div>
                        <div class="summary-item total">
                            <span>Total:</span>
                            <span id="cart-total">₹<?php echo number_format($total, 2); ?></span>
                        </div>
                    </div>

                    <button class="checkout-button" onclick="processCheckout()">
                        Proceed to Payment
                    </button>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
        function showMessage(message, isError = false) {
            const messageDiv = document.getElementById('update-message');
            messageDiv.style.backgroundColor = isError ? '#dc3545' : '#28a745';
            messageDiv.textContent = message;
            messageDiv.style.display = 'block';
            setTimeout(() => {
                messageDiv.style.display = 'none';
            }, 3000);
        }

        function updateQuantity(cartId, action, value = null) {
            let quantityInput = document.getElementById(`quantity-${cartId}`);
            let currentQuantity = parseInt(quantityInput.value);
            let newQuantity;

            switch(action) {
                case 'increase':
                    newQuantity = currentQuantity + 1;
                    break;
                case 'decrease':
                    newQuantity = Math.max(1, currentQuantity - 1);
                    break;
                case 'set':
                    newQuantity = Math.max(1, parseInt(value));
                    break;
                default:
                    return;
            }

            fetch('api/update-cart.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    cartId: cartId,
                    quantity: newQuantity
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update quantity input
                    quantityInput.value = newQuantity;
                    
                    // Update item subtotal
                    document.getElementById(`subtotal-${cartId}`).textContent = 
                        '₹' + data.subtotal;
                    
                    // Update cart total
                    document.getElementById('cart-total').textContent = 
                        '₹' + data.total;
                    
                    // Update GST
                    document.getElementById('gst-amount').textContent = 
                        '₹' + data.gst;
                    
                    // Update cart count in header
                    updateCartCount();
                } else {
                    console.error('Update failed:', data.message);
                    alert('Failed to update cart: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error updating cart');
            });
        }

        function removeItem(cartId) {
            if (!confirm('Are you sure you want to remove this item?')) {
                return;
            }

            fetch('api/remove-from-cart.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    cartId: cartId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById(`cart-item-${cartId}`).remove();
                    document.getElementById('cart-total').textContent = data.total;
                    showMessage('Item removed from cart');
                    updateCartCount();
                    
                    // Check if cart is empty
                    if (data.total === "0.00") {
                        location.reload();
                    }
                } else {
                    showMessage('Error removing item', true);
                }
            })
            .catch(error => {
                showMessage('Error removing item', true);
            });
        }

        function processCheckout() {
            window.location.href = 'payment.php';
        }
    </script>
</body>
</html> 