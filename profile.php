<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

require_once 'config/database.php';
$pageTitle = "My Profile";
$message = '';
$messageType = ''; // 'success' or 'error'

// Fetch user details
try {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch();
} catch(PDOException $e) {
    $message = "Error fetching user details";
    $messageType = 'error';
}

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        // Begin transaction
        $pdo->beginTransaction();
        
        // Validate input
        $name = trim($_POST['name'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $address = trim($_POST['address'] ?? '');
        
        // Basic validation
        if (strlen($phone) > 20) {
            throw new Exception("Phone number is too long");
        }
        
        // Update user profile
        $stmt = $pdo->prepare("
            UPDATE users 
            SET name = ?, 
                phone = ?, 
                address = ? 
            WHERE id = ?
        ");
        
        $stmt->execute([$name, $phone, $address, $_SESSION['user_id']]);
        
        // Commit transaction
        $pdo->commit();
        
        $message = "Profile updated successfully!";
        $messageType = 'success';
        
        // Refresh user data
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $user = $stmt->fetch();
        
    } catch(Exception $e) {
        // Rollback transaction
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        $message = "Error updating profile: " . $e->getMessage();
        $messageType = 'error';
    }
}

include 'header.php';
?>

<style>
    .profile-container {
        max-width: 800px;
        margin: 50px auto;
        padding: 30px;
        background: white;
        border-radius: 10px;
        box-shadow: 0 0 20px rgba(0,0,0,0.1);
    }

    .profile-header {
        text-align: center;
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 2px solid #eee;
    }

    .profile-avatar {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        background: #2D336B;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 48px;
        margin: 0 auto 20px;
    }

    .profile-form {
        display: grid;
        gap: 20px;
        max-width: 600px;
        margin: 0 auto;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .form-group label {
        font-weight: 600;
        color: #2D336B;
    }

    .form-group input, .form-group textarea {
        padding: 12px;
        border: 1px solid #ddd;
        border-radius: 5px;
        font-size: 16px;
    }

    .form-group textarea {
        height: 100px;
        resize: vertical;
    }

    .update-btn {
        background: linear-gradient(45deg, #2D336B, #4B0082);
        color: white;
        padding: 12px 30px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
        font-weight: 600;
        transition: transform 0.3s ease;
    }

    .update-btn:hover {
        transform: translateY(-2px);
    }

    .message {
        text-align: center;
        padding: 12px;
        margin: 20px 0;
        border-radius: 5px;
    }

    .message.success {
        background: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }

    .message.error {
        background: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }

    .order-history {
        margin-top: 40px;
        padding-top: 20px;
        border-top: 2px solid #eee;
    }

    .stats-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin: 20px 0;
    }

    .stat-card {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 8px;
        text-align: center;
    }

    .stat-number {
        font-size: 24px;
        font-weight: bold;
        color: #2D336B;
    }
</style>

<div class="profile-container">
    <div class="profile-header">
        <div class="profile-avatar">
            <?php echo strtoupper(substr($user['email'] ?? '', 0, 1)); ?>
        </div>
        <h1><?php echo htmlspecialchars($user['email']); ?></h1>
    </div>

    <?php if ($message): ?>
        <div class="message <?php echo $messageType; ?>">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <form class="profile-form" method="POST">
        <div class="form-group">
            <label for="name">Full Name</label>
            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['name'] ?? ''); ?>" placeholder="Enter your full name">
        </div>

        <div class="form-group">
            <label for="phone">Phone Number</label>
            <input type="tel" id="phone" name="phone" value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>" placeholder="Enter your phone number">
        </div>

        <div class="form-group">
            <label for="address">Shipping Address</label>
            <textarea id="address" name="address" placeholder="Enter your shipping address"><?php echo htmlspecialchars($user['address'] ?? ''); ?></textarea>
        </div>

        <button type="submit" class="update-btn">Update Profile</button>
    </form>

    <div class="order-history">
        <h2>Account Overview</h2>
        <div class="stats-container">
            <div class="stat-card">
                <div class="stat-number">
                    <?php
                        $stmt = $pdo->prepare("SELECT COUNT(*) FROM cart WHERE user_id = ?");
                        $stmt->execute([$_SESSION['user_id']]);
                        echo $stmt->fetchColumn();
                    ?>
                </div>
                <div>Items in Cart</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">
                    <?php
                        // Placeholder for orders count
                        echo "0";
                    ?>
                </div>
                <div>Orders Placed</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">
                    <?php echo date('d M Y', strtotime($user['created_at'] ?? 'now')); ?>
                </div>
                <div>Member Since</div>
            </div>
        </div>
    </div>
</div> 