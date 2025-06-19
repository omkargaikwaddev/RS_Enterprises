<?php
session_start();
require_once 'config/database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Fetch wishlist items
$stmt = $pdo->prepare("
    SELECT p.*, w.id as wishlist_id 
    FROM wishlist w 
    JOIN products p ON w.product_id = p.id 
    WHERE w.user_id = ?
");
$stmt->execute([$_SESSION['user_id']]);
$wishlistItems = $stmt->fetchAll();

$pageTitle = "My Wishlist";
include 'header.php';
?>

<div class="wishlist-container">
    <h1>My Wishlist</h1>
    
    <?php if (empty($wishlistItems)): ?>
        <p>Your wishlist is empty. <a href="shop.php">Continue shopping</a></p>
    <?php else: ?>
        <div class="wishlist-grid">
            <?php foreach ($wishlistItems as $item): ?>
                <div class="wishlist-item">
                    <img src="<?php echo htmlspecialchars($item['image_url']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>">
                    <h3><?php echo htmlspecialchars($item['name']); ?></h3>
                    <p class="price">₹<?php echo number_format($item['price'], 2); ?></p>
                    <div class="wishlist-actions">
                        <button onclick="addToCart(<?php echo $item['id']; ?>)">Add to Cart</button>
                        <button onclick="removeFromWishlist(<?php echo $item['wishlist_id']; ?>)" class="remove-btn">Remove</button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div> 