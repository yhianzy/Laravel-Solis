<?php
require_once 'config/database.php';
require_once 'config/session_helper.php';

$email = $_GET['email'] ?? '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $otp = trim($_POST['otp'] ?? '');
    $email = trim($_POST['email'] ?? $_SESSION['signup_email'] ?? '');
    
    if (empty($otp) || empty($email)) {
        $error = "Please enter the OTP code";
    } else {
        $conn = getDBConnection();
        
        // Check if otp_codes table exists
        $table_check = $conn->query("SHOW TABLES LIKE 'otp_codes'");
        if ($table_check->num_rows == 0) {
            $error = "Database error: OTP table not found. Please run database_otp_update.sql in phpMyAdmin.";
            closeDBConnection($conn);
        } else {
            // Verify OTP - check if it exists for this email
            $check_stmt = $conn->prepare("SELECT * FROM otp_codes WHERE email = ? AND otp_code = ? AND purpose = 'signup' ORDER BY created_at DESC LIMIT 1");
            $check_stmt->bind_param("ss", $email, $otp);
            $check_stmt->execute();
            $check_result = $check_stmt->get_result();
            $otp_record = $check_result->fetch_assoc();
            $check_stmt->close();
            
            if (!$otp_record) {
                // OTP doesn't match - check if there's any OTP for this email
                $email_check = $conn->prepare("SELECT otp_code, used, expires_at FROM otp_codes WHERE email = ? AND purpose = 'signup' ORDER BY created_at DESC LIMIT 1");
                $email_check->bind_param("s", $email);
                $email_check->execute();
                $email_result = $email_check->get_result();
                $email_otp = $email_result->fetch_assoc();
                $email_check->close();
                
                if ($email_otp) {
                    $error = "OTP code mismatch. Please check the code from your email and try again.";
                } else {
                    $error = "No OTP found for this email. Please request a new OTP from signup page.";
                }
            } elseif ($otp_record['used'] == 1) {
                $error = "This OTP has already been used. Please request a new OTP.";
            } elseif (strtotime($otp_record['expires_at']) < time()) {
                $error = "OTP has expired. Please request a new OTP from signup page.";
            } else {
                // OTP is valid - mark as used
                $update_stmt = $conn->prepare("UPDATE otp_codes SET used = 1 WHERE otp_id = ?");
                $update_stmt->bind_param("i", $otp_record['otp_id']);
                $update_stmt->execute();
                $update_stmt->close();
                
                // Check if we have signup data in session
                if (isset($_SESSION['signup_username']) && isset($_SESSION['signup_email']) && isset($_SESSION['signup_password'])) {
                    // Create the member account
                    $insert_stmt = $conn->prepare("INSERT INTO members (username, email, password) VALUES (?, ?, ?)");
                    $insert_stmt->bind_param("sss", $_SESSION['signup_username'], $_SESSION['signup_email'], $_SESSION['signup_password']);
                    
                    if ($insert_stmt->execute()) {
                        // Clear signup session data
                        unset($_SESSION['signup_username']);
                        unset($_SESSION['signup_email']);
                        unset($_SESSION['signup_password']);
                        
                        // Redirect to login page with success message
                        header("Location: login.php?success=" . urlencode("Account created successfully! Please login."));
                        exit;
                    } else {
                        $error = "Failed to create account. Please try signing up again.";
                    }
                    
                    $insert_stmt->close();
                } else {
                    $error = "Session expired. Please sign up again.";
                }
            }
            
            closeDBConnection($conn);
        }
    }
}

// If no email in URL, try to get from session
if (empty($email) && isset($_SESSION['signup_email'])) {
    $email = $_SESSION['signup_email'];
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>OTP Verification</title>
    <link rel="stylesheet" href="CSS/auth.css">
</head>
<body>

<div class="auth-wrapper auth-box">
    <a href="signup.php" class="back-btn">&#8592;</a>
    <div class="auth-card">
        <h1>ENTER OTP</h1>
        
        <?php if (!empty($error)): ?>
            <div style="color: #e74c3c; margin-bottom: 15px; padding: 10px; background: #2c2c2c; border-radius: 6px;">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>
        
        <form action="verify_otp.php" method="POST">
            <input type="hidden" name="email" value="<?php echo htmlspecialchars($email); ?>">
            <label>An OTP was sent to <?php echo htmlspecialchars($email); ?></label>
            <input type="text" name="otp" required maxlength="6" placeholder="Enter 6-digit OTP" pattern="[0-9]{6}" autocomplete="off">
            
            <button class="auth-btn">Verify OTP</button>
        </form>
        
        <p style="margin-top: 15px; font-size: 14px; color: #aaa;">
            Didn't receive the code? <a href="signup.php" style="color: white;">Request a new OTP</a>
        </p>
    </div>
</div>

</body>
</html>
