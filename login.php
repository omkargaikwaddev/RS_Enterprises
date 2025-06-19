<?php
session_start();

// Check if user is already logged in
if(isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

$pageTitle = "Login";
$error = null;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require_once 'config/database.php';
    
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    try {
        $stmt = $pdo->prepare("SELECT id, email, password FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['email'] = $user['email'];
            
            // Store the intended destination if it exists
            $redirect = isset($_SESSION['redirect_url']) 
                ? $_SESSION['redirect_url'] 
                : 'index.php';
            unset($_SESSION['redirect_url']);
            
            header("Location: $redirect");
            exit();
        } else {
            $error = "Invalid email or password";
        }
    } catch(PDOException $e) {
        $error = "An error occurred. Please try again later.";
    }
}

// Include header after all potential redirects
include 'header.php';
?>

<style>
    .login-container {
        max-width: 400px;
        margin: 50px auto;
        padding: 20px;
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .login-header {
        text-align: center;
        margin-bottom: 30px;
    }

    .login-header h1 {
        color: #2D336B;
        font-family: 'Montserrat', sans-serif;
        font-size: 2em;
        margin-bottom: 10px;
    }

    .login-form {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 5px;
    }

    .form-group label {
        color: #2D336B;
        font-family: 'Montserrat', sans-serif;
        font-weight: 600;
    }

    .form-group input {
        padding: 12px;
        border: 2px solid #c3cfe2;
        border-radius: 5px;
        font-size: 16px;
        transition: all 0.3s ease;
    }

    .form-group input:focus {
        border-color: #2D336B;
        outline: none;
        box-shadow: 0 0 5px rgba(45, 51, 107, 0.3);
    }

    .login-button {
        background: linear-gradient(45deg, #2D336B, #4B0082);
        color: white;
        padding: 12px;
        border: none;
        border-radius: 5px;
        font-family: 'Montserrat', sans-serif;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .login-button:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(45, 51, 107, 0.3);
    }

    .login-button:active {
        transform: translateY(0);
    }

    .error-message {
        background-color: #fff3f3;
        color: #dc3545;
        padding: 10px;
        border-radius: 5px;
        text-align: center;
        margin-bottom: 20px;
        border: 1px solid #dc3545;
    }

    .register-link {
        text-align: center;
        margin-top: 20px;
        color: #2D336B;
        font-family: 'Montserrat', sans-serif;
    }

    .register-link a {
        color: #4B0082;
        text-decoration: none;
        font-weight: 600;
    }

    .register-link a:hover {
        text-decoration: underline;
    }

    @media (max-width: 480px) {
        .login-container {
            margin: 20px;
            padding: 15px;
        }

        .login-header h1 {
            font-size: 1.5em;
        }
    }
</style>

<div class="login-container">
    <div class="login-header">
        <h1>Welcome Back!</h1>
        <p>Please login to continue</p>
    </div>

    <?php if (isset($error)): ?>
        <div class="error-message">
            <?php echo htmlspecialchars($error); ?>
        </div>
    <?php endif; ?>

    <form class="login-form" method="POST" action="">
        <div class="form-group">
            <label for="email">Email</label>
            <input 
                type="email" 
                id="email" 
                name="email" 
                required 
                placeholder="Enter your email"
                value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>"
            >
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <input 
                type="password" 
                id="password" 
                name="password" 
                required 
                placeholder="Enter your password"
            >
        </div>

        <button type="submit" class="login-button">Login</button>
    </form>

    <div class="register-link">
        <p>Don't have an account? <a href="register.php">Register here</a></p>
    </div>
</div>

<script>
    // Add some animation to form inputs
    document.querySelectorAll('.form-group input').forEach(input => {
        input.addEventListener('focus', function() {
            this.parentElement.style.transform = 'translateY(-2px)';
        });

        input.addEventListener('blur', function() {
            this.parentElement.style.transform = 'translateY(0)';
        });
    });
</script> 