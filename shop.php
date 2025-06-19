<?php 
session_start();
$pageTitle = "Our Products";
require_once 'config/database.php';

try {
    $stmt = $pdo->prepare("SELECT * FROM products");
    $stmt->execute();
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error_message = "Unable to load products. Please try again later.";
}

include 'header.php';
?>

<style>
    .container {
        max-width: 1200px;
        margin: 50px auto;
        padding: 0 20px;
    }

    .products-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 30px;
        padding: 20px;
    }

    .product-card {
        background: white;
        border-radius: 10px;
        padding: 15px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        display: flex;
        flex-direction: column;
        max-width: 300px;
        margin: 0 auto;
    }

    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
    }

    .product-image {
        width: 100%;
        height: 180px;
        object-fit: contain;
        border-radius: 8px;
        margin-bottom: 15px;
    }

    .product-title {
        font-size: 1.4em;
        color: #2D336B;
        margin: 10px 0;
        font-family: 'Montserrat', sans-serif;
    }

    .product-description {
        color: #666;
        margin: 10px 0;
        flex-grow: 1;
        line-height: 1.5;
    }

    .product-price {
        font-size: 1.2em;
        color: #2D336B;
        font-weight: bold;
        margin: 15px 0;
    }

    .button-container {
        display: flex;
        gap: 10px;
        margin-top: auto;
    }

    .btn {
        flex: 1;
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
        transform: scale(1.05);
        box-shadow: 0 6px 12px rgba(45, 51, 107, 0.3);
    }

    .btn-cart {
        background: white;
        color: #2D336B;
        border: 2px solid #2D336B;
    }

    .btn-cart:hover {
        background: rgba(255, 255, 255, 0.9);
    }

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
        .product-image {
            height: 150px;
        }
    }
</style>

<div class="container">
    <h1 style="font-family: 'Montserrat', sans-serif; background: linear-gradient(45deg, #2D336B, #4B0082); color: white; padding: 10px 20px; border-radius: 8px; margin-bottom: 30px;">Our Products</h1>
    
    <?php if (isset($error_message)): ?>
        <div class="error-message">
            <?php echo htmlspecialchars($error_message); ?>
        </div>
    <?php else: ?>
    <div class="products-grid">
        <?php foreach ($products as $product): ?>
        <div class="product-card">
            <img src="<?php echo htmlspecialchars($product['image_url']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="product-image">
            <h3 class="product-title"><?php echo htmlspecialchars($product['name']); ?></h3>
            <p class="product-description"><?php echo htmlspecialchars($product['description']); ?></p>
            <p class="product-price">₹<?php echo number_format($product['price'], 2); ?></p>
            <div class="button-container">
                <button onclick="checkAuthAndProceed('addToCart', <?php echo $product['id']; ?>)" class="btn">Add to Cart</button>
                <button onclick="checkAuthAndProceed('buyNow', <?php echo $product['id']; ?>)" class="btn btn-cart">Buy Now</button>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>

<script>
    function checkAuthAndProceed(action, productId) {
        fetch('api/check-login.php')
        .then(response => response.json())
        .then(data => {
            if (!data.logged_in) {
                // Store the current URL and action in session storage
                sessionStorage.setItem('lastAction', JSON.stringify({
                    action: action,
                    productId: productId,
                    returnUrl: window.location.href
                }));
                window.location.href = 'login.php';
                return;
            }
            
            if (action === 'addToCart') {
                addToCart(productId);
            } else if (action === 'buyNow') {
                buyNow(productId);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error checking login status');
        });
    }

    function addToCart(productId) {
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
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error adding product to cart');
        });
    }

    function buyNow(productId) {
        addToCart(productId);
        setTimeout(() => {
            window.location.href = 'checkout.php';
        }, 1000);
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

    // Check if there was a previous action after login
    document.addEventListener('DOMContentLoaded', function() {
        const lastAction = sessionStorage.getItem('lastAction');
        if (lastAction) {
            const action = JSON.parse(lastAction);
            sessionStorage.removeItem('lastAction');
            checkAuthAndProceed(action.action, action.productId);
        }
        updateCartCount();
    });
</script>

