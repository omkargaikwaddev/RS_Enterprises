<?php
session_start();
require_once 'config/database.php';
require_once 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Check if user is already logged in
if(isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

$pageTitle = "Register";
$error = null;
$message = null;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['verify_otp'])) {
        // OTP Verification logic
        $email = $_POST['email'];
        $otp = $_POST['otp'];
        
        $stmt = $pdo->prepare("SELECT * FROM email_verification WHERE email = ? AND otp = ? AND expires_at > NOW() AND verified = 0");
        $stmt->execute([$email, $otp]);
        
        if ($stmt->fetch()) {
            // OTP is valid, proceed with registration
            $password = $_POST['password'];
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            
            $stmt = $pdo->prepare("INSERT INTO users (email, password) VALUES (?, ?)");
            $stmt->execute([$email, $hashedPassword]);
            
            // Mark OTP as verified
            $stmt = $pdo->prepare("UPDATE email_verification SET verified = 1 WHERE email = ?");
            $stmt->execute([$email]);
            
            $_SESSION['user_id'] = $pdo->lastInsertId();
            $_SESSION['email'] = $email;
            
            header("Location: index.php");
            exit();
        } else {
            $error = "Invalid or expired OTP";
        }
    } else {
        // Initial registration request
        $email = $_POST['email'];
        
        // Check if email already exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        
        if ($stmt->fetch()) {
            $error = "Email already registered";
        } else {
            // Generate OTP
            $otp = sprintf("%06d", random_int(0, 999999));
            $expires_at = date('Y-m-d H:i:s', strtotime('+10 minutes'));
            
            // Store OTP
            $stmt = $pdo->prepare("INSERT INTO email_verification (email, otp, expires_at) VALUES (?, ?, ?)");
            $stmt->execute([$email, $otp, $expires_at]);
            
            // Send OTP via email
            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'omkargaikwad9805@gmail.com';  // Your email
                $mail->Password = 'xxxx xxxx xxxx xxxx';         // Replace with your App Password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;
                
                $mail->setFrom('omkargaikwad9805@gmail.com', 'Riddhi Siddhi Enterprises');
                $mail->addAddress($email);
                $mail->Subject = 'Email Verification OTP';
                $mail->Body = "Your OTP for registration is: $otp\nThis OTP will expire in 10 minutes.";
                
                if($mail->send()) {
                    $_SESSION['registration_email'] = $email;
                    $_SESSION['registration_password'] = $_POST['password'];
                    $message = "OTP sent to your email";
                } else {
                    $error = "Failed to send OTP";
                }
            } catch (Exception $e) {
                $error = "Failed to send OTP: " . $mail->ErrorInfo;
            }
        }
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

    .otp-container {
        text-align: center;
        margin-bottom: 20px;
    }
    
    .otp-input {
        letter-spacing: 8px;
        font-size: 24px;
        padding: 10px;
        text-align: center;
        width: 200px;
        margin: 20px auto;
    }
    
    .timer {
        color: #666;
        margin-top: 10px;
        font-size: 14px;
    }
    
    .alert {
        padding: 10px;
        border-radius: 5px;
        margin-bottom: 15px;
    }
    
    .alert-danger {
        background-color: #ffe6e6;
        color: #cc0000;
        border: 1px solid #ffcccc;
    }
    
    .alert-success {
        background-color: #e6ffe6;
        color: #006600;
        border: 1px solid #ccffcc;
    }
</style>

<div class="login-container">
    <div class="login-header">
        <h1><?php echo $pageTitle; ?></h1>
        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        <?php if ($message): ?>
            <div class="alert alert-success"><?php echo $message; ?></div>
        <?php endif; ?>
    </div>

    <?php if (isset($_SESSION['registration_email'])): ?>
        <!-- OTP Verification Form -->
        <form class="login-form" method="POST" action="">
            <input type="hidden" name="email" value="<?php echo htmlspecialchars($_SESSION['registration_email']); ?>">
            <input type="hidden" name="password" value="<?php echo htmlspecialchars($_SESSION['registration_password']); ?>">
            
            <div class="otp-container">
                <h3>Enter OTP</h3>
                <p>We've sent a verification code to <?php echo htmlspecialchars($_SESSION['registration_email']); ?></p>
                <div class="form-group">
                    <input type="text" name="otp" class="otp-input" maxlength="6" required 
                           pattern="[0-9]{6}" title="Please enter 6 digits" 
                           placeholder="Enter 6-digit OTP">
                </div>
                <div class="timer" id="timer">Time remaining: 10:00</div>
            </div>
            
            <button type="submit" name="verify_otp" class="login-button">Verify OTP</button>
        </form>
    <?php else: ?>
        <!-- Initial Registration Form -->
        <form class="login-form" method="POST" action="">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required 
                       placeholder="Enter your email"
                       value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required 
                       placeholder="Enter your password"
                       minlength="6">
            </div>

            <button type="submit" class="login-button">Register</button>
        </form>
    <?php endif; ?>

    <div class="register-link">
        <p>Already have an account? <a href="login.php">Login here</a></p>
    </div>
</div>

<script>
    // Timer functionality
    function startTimer(duration, display) {
        let timer = duration;
        const interval = setInterval(function () {
            const minutes = parseInt(timer / 60, 10);
            const seconds = parseInt(timer % 60, 10);

            display.textContent = 'Time remaining: ' + 
                minutes + ':' + (seconds < 10 ? '0' : '') + seconds;

            if (--timer < 0) {
                clearInterval(interval);
                display.textContent = 'OTP expired. Please try again.';
                // Optionally, redirect to registration page
                window.location.href = 'register.php';
            }
        }, 1000);
    }

    // Start timer if we're on the OTP page
    const timerDisplay = document.getElementById('timer');
    if (timerDisplay) {
        startTimer(600, timerDisplay); // 600 seconds = 10 minutes
    }
</script> 