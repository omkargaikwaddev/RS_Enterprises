<?php
session_start();
$pageTitle = "Our Products";
require_once 'config/database.php';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    // Fetch products from database
    $stmt = $pdo->prepare("SELECT * FROM products");
    $stmt->execute();
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Log the error and show a user-friendly message
    error_log($e->getMessage());
    $error_message = "Unable to load products. Please try again later.";
}

// Include the header
include 'header.php';
?>

<div class="container">
    <h1 style="font-family: 'Montserrat', sans-serif; background: linear-gradient(45deg, #2D336B, #4B0082); color: white; padding: 10px 20px; border-radius: 8px; margin-bottom: 30px;">Our Products</h1>
    
    <?php if (isset($error_message)): ?>
        <div class="error-message" style="color: red; text-align: center; margin: 20px 0;">
            <?php echo htmlspecialchars($error_message); ?>
        </div>
    <?php else: ?>
    <div class="products-grid">
        <!-- Product Card 1 -->
        <div class="product-card">
            <img src="image/blueHand.png" alt="Safety Gloves" class="product-image">
            <h3 class="product-title">Safety Gloves</h3>
            <p class="product-description">Heavy-duty safety gloves with reinforced palm grip. Ideal for industrial and construction work.</p>
            <div class="button-container">
                <button onclick="addToCart(1)" class="btn">Add to Cart</button>
                <button onclick="buyNow(1)" class="btn btn-cart">Buy Now</button>
            </div>
            <div class="stock-status">
                <?php if ($product['stock'] > 0): ?>
                    <span class="in-stock">In Stock (<?php echo $product['stock']; ?>)</span>
                <?php endif; ?>
            </div>
        </div>

        <!-- Product Card 2 -->
        <div class="product-card">
            <img src="image/blue Hand Gloves.png" alt="Blue Threaded Hand Gloves" class="product-image">
            <h3 class="product-title">Blue Threaded Hand Gloves</h3>
            <p class="product-description">Premium quality blue threaded hand gloves perfect for industrial use. Provides excellent grip and protection.</p>
            <div class="button-container">
                <button onclick="addToCart(2)" class="btn">Add to Cart</button>
                <button onclick="buyNow(2)" class="btn btn-cart">Buy Now</button>
            </div>
            <div class="stock-status">
                <?php if ($product['stock'] > 0): ?>
                    <span class="in-stock">In Stock (<?php echo $product['stock']; ?>)</span>
                <?php endif; ?>
            </div>
        </div>

        <!-- Product Card 3 -->
        <div class="product-card">
            <img src="image/nylon.png" alt="Nylon Safety Hand Gloves" class="product-image">
            <h3 class="product-title">Nylon Safety Hand Gloves</h3>
            <p class="product-description">Premium nylon safety gloves with PU coating for enhanced grip. Breathable, lightweight and ideal for precision work.</p>
            <div class="button-container">
                <button onclick="addToCart(3)" class="btn">Add to Cart</button>
                <button onclick="buyNow(3)" class="btn btn-cart">Buy Now</button>
            </div>
            <div class="stock-status">
                <?php if ($product['stock'] > 0): ?>
                    <span class="in-stock">In Stock (<?php echo $product['stock']; ?>)</span>
                <?php endif; ?>
            </div>
        </div>

        <!-- Product Card 4 -->
        <div class="product-card">
            <img src="image/grey_enhanced.png" alt="Grey Threaded Hand Gloves" class="product-image">
            <h3 class="product-title">Grey Threaded Hand Gloves</h3>
            <p class="product-description">Premium quality grey threaded hand gloves perfect for industrial use. Provides excellent grip and protection.</p>
            <div class="button-container">
                <button onclick="addToCart(4)" class="btn">Add to Cart</button>
                <button onclick="buyNow(4)" class="btn btn-cart">Buy Now</button>
            </div>
            <div class="stock-status">
                <?php if ($product['stock'] > 0): ?>
                    <span class="in-stock">In Stock (<?php echo $product['stock']; ?>)</span>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<style>
    /* Container Styles */
    .container {
        max-width: 1400px;
        margin: 50px auto;
        padding: 0 20px;
    }

    /* Products Grid Layout */
    .products-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 30px;
        padding: 20px;
        background-color: rgba(248, 249, 250, 0.9);
        border-radius: 10px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        backdrop-filter: blur(5px);
    }

    /* Product Card Styles */
    .product-card {
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        padding: 20px;
        border-radius: 10px;
        text-align: center;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
    }

    /* Product Image Styles */
    .product-image {
        max-width: 200px;
        height: auto;
        border-radius: 8px;
        margin: 0 auto;
        transition: all 0.5s ease;
    }

    .product-image:hover {
        transform: translateY(-10px) scale(1.1);
        filter: drop-shadow(0 10px 15px rgba(45, 51, 107, 0.3));
    }

    /* Product Text Styles */
    .product-title {
        font-family: 'Montserrat', sans-serif;
        color: #2D336B;
        margin: 15px 0;
        font-size: 1.5em;
        font-weight: 600;
    }

    .product-description {
        font-family: 'Montserrat', sans-serif;
        margin-bottom: 20px;
        color: #666;
        line-height: 1.6;
        font-size: 1rem;
    }

    /* Button Styles */
    .button-container {
        display: flex;
        gap: 10px;
        justify-content: center;
        margin-top: auto;
    }

    .btn {
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        font-family: 'Montserrat', sans-serif;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        background: linear-gradient(45deg, #2D336B, #4B0082);
        color: white;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .btn:hover {
        transform: scale(1.05) rotate(2deg);
        box-shadow: 0 6px 12px rgba(45, 51, 107, 0.3);
    }

    .btn-cart {
        background: white;
        color: #2D336B;
        border: 2px solid #2D336B;
    }

    .btn-cart:hover {
        background: rgba(255, 255, 255, 0.9);
        color: #2D336B;
        border-width: 3px;
        border-style: dashed;
        animation: borderDance 1.5s linear infinite;
    }

    /* Animation Keyframes */
    @keyframes borderDance {
        0% { 
            border-style: dashed; 
            transform: rotate(0deg); 
        }
        25% { 
            border-style: dotted; 
            transform: rotate(1deg); 
        }
        50% { 
            border-style: double; 
            transform: rotate(0deg); 
        }
        75% { 
            border-style: dotted; 
            transform: rotate(-1deg); 
        }
        100% { 
            border-style: dashed; 
            transform: rotate(0deg); 
        }
    }

    /* Error Message Styles */
    .error-message {
        background-color: #fff3f3;
        color: #dc3545;
        padding: 15px;
        border-radius: 5px;
        margin: 20px 0;
        text-align: center;
        border: 1px solid #dc3545;
    }

    /* Responsive Design */
    @media screen and (max-width: 768px) {
        .products-grid {
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            padding: 15px;
        }

        .product-title {
            font-size: 1.2em;
        }

        .product-description {
            font-size: 0.9rem;
        }

        .btn {
            padding: 8px 16px;
            font-size: 0.9rem;
        }
    }

    @media screen and (max-width: 480px) {
        .container {
            margin: 30px auto;
            padding: 0 15px;
        }

        .product-image {
            max-width: 150px;
        }

        .button-container {
            flex-direction: column;
        }

        .btn {
            width: 100%;
        }
    }
</style>

<script>
    function addToCart(productId) {
        // Check if user is logged in first
        fetch('api/check-login.php')
        .then(response => response.json())
        .then(data => {
            if (!data.logged_in) {
                window.location.href = 'login.php';
                return;
            }
            
            fetch('api/add-to-cart.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    productId: productId,
                    quantity: 1
                })
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    alert('Product added to cart!');
                    updateCartCount();
                } else {
                    if(data.message === 'User not logged in') {
                        window.location.href = 'login.php';
                    } else {
                        alert('Error adding product to cart: ' + data.message);
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error adding product to cart');
            });
        });
    }

    function buyNow(productId) {
        // Check if user is logged in first
        fetch('api/check-login.php')
        .then(response => response.json())
        .then(data => {
            if (!data.logged_in) {
                window.location.href = 'login.php';
                return;
            }
            
            addToCart(productId);
            setTimeout(() => {
                window.location.href = 'checkout.php';
            }, 1000);
        });
    }

    function updateCartCount() {
        fetch('api/get-cart-count.php')
        .then(response => response.json())
        .then(data => {
            document.getElementById('cart-count').textContent = data.count;
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }

    // Update cart count when page loads
    document.addEventListener('DOMContentLoaded', function() {
        updateCartCount();
    });
</script>

<!-- Footer Section -->
<footer style="background: linear-gradient(45deg, #3D4380, #6B2099); color: white; padding: 15px 0; margin-top: 30px; font-family: 'Montserrat', sans-serif; text-align: center;">
    <!-- Your existing footer content remains the same -->
</footer>

<div class="payment-methods">
    <div class="payment-method">
        <input type="radio" name="payment_method" value="cod">
        <label>Cash on Delivery</label>
    </div>
    <div class="payment-method">
        <input type="radio" name="payment_method" value="upi">
        <label>UPI Payment</label>
    </div>
    <div class="payment-method">
        <input type="radio" name="payment_method" value="card">
        <label>Credit/Debit Card</label>
    </div>
    <div class="payment-method">
        <input type="radio" name="payment_method" value="netbanking">
        <label>Net Banking</label>
    </div>
</div> 