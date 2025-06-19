<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle . ' - ' : ''; ?>Riddhi Siddhi Enterprises</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap');
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            padding-top: 0; /* Remove top padding */
        }

        header {
            background-color: rgba(0, 0, 0, 0.1);
            padding: 5px 0;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            transition: background-color 0.3s ease;
            font-family: 'Poppins', sans-serif;
            position: relative; /* Changed from sticky to relative */
            width: 100%;
        }

        nav {
            background: linear-gradient(45deg, #2D336B, #4B0082);
            padding: 15px 50px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .nav-left, .nav-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        nav a {
            color: white;
            text-decoration: none;
            padding: 8px 15px;
            border-radius: 5px;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        nav a:hover {
            background: rgba(255,255,255,0.1);
            transform: translateY(-2px);
        }

        .logo {
            height: 60px;
            margin-right: 20px;
        }

        .cart-icon {
            position: relative;
        }

        #cart-count {
            background-color: #ff4444;
            color: white;
            border-radius: 50%;
            padding: 2px 6px;
            font-size: 12px;
            position: absolute;
            top: -8px;
            right: -8px;
        }

        .user-menu {
            position: relative;
            cursor: pointer;
        }

        .user-dropdown {
            display: none;
            position: absolute;
            right: 0;
            top: 100%;
            background: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            border-radius: 5px;
            min-width: 200px;
            z-index: 1000;
        }

        .user-menu:hover .user-dropdown {
            display: block;
        }

        .user-dropdown a {
            color: #2D336B;
            padding: 12px 20px;
            display: block;
            border-bottom: 1px solid #eee;
        }

        .user-dropdown a:last-child {
            border-bottom: none;
        }

        .user-dropdown a:hover {
            background: #f5f5f5;
        }

        .logout-btn {
            color: #dc3545 !important;
        }

        @media screen and (max-width: 768px) {
            nav {
                padding: 15px 20px;
                flex-wrap: wrap;
            }

            .nav-right {
                margin-top: 10px;
                width: 100%;
                justify-content: space-around;
            }
        }
    </style>
</head>
<body>
    <header>
        <nav>
            <div class="nav-left">
                <img src="image/rsLogo.png" alt="Riddhi Siddhi Enterprises Logo" class="logo" onclick="window.location.href='index.php'">
                <a href="index.php"><i class="fas fa-home"></i> Home</a>
                <a href="shop.php"><i class="fas fa-store"></i> Shop</a>
                <a href="#faqs"><i class="fas fa-question-circle"></i> FAQs</a>
            </div>
            <div class="nav-right">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="checkout.php" class="cart-icon">
                        <i class="fas fa-shopping-cart"></i> Cart
                        <span id="cart-count">0</span>
                    </a>
                    <div class="user-menu">
                        <a href="#"><i class="fas fa-user"></i> <?php echo htmlspecialchars($_SESSION['email']); ?></a>
                        <div class="user-dropdown">
                            <a href="profile.php"><i class="fas fa-user-circle"></i> Profile</a>
                            <a href="orders.php"><i class="fas fa-box"></i> My Orders</a>
                            <a href="logout.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Logout</a>
                        </div>
                    </div>
                <?php else: ?>
                    <a href="login.php"><i class="fas fa-sign-in-alt"></i> Login</a>
                    <a href="register.php"><i class="fas fa-user-plus"></i> Register</a>
                <?php endif; ?>
            </div>
        </nav>
    </header>
    <div class="content-wrapper">
        <!-- Content of other pages will be inserted here -->
    </div>

    <script>
        // Update cart count on page load
        document.addEventListener('DOMContentLoaded', function() {
            updateCartCount();
        });

        function updateCartCount() {
            fetch('api/get-cart-count.php')
                .then(response => response.json())
                .then(data => {
                    document.getElementById('cart-count').textContent = data.count;
                });
        }
    </script>
</body>
</html>
