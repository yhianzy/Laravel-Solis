<?php
require_once 'config/database.php';
require_once 'config/session_helper.php';

$email = '';
$error = '';

// Check if coming from OTP verification
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['otp'])) {
    $otp = trim($_POST['otp'] ?? '');
    $email = trim($_POST['email'] ?? $_SESSION['reset_email'] ?? '');
    
    if (empty($otp) || empty($email)) {
        $error = "Please enter the OTP code";
    } else {
        $conn = getDBConnection();
        
        // Check if otp_codes table exists
        $table_check = $conn->query("SHOW TABLES LIKE 'otp_codes'");
        if ($table_check->num_rows == 0) {
            closeDBConnection($conn);
            header("Location: forgot_password.php?step=2&email=" . urlencode($email) . "&error=" . urlencode("Database error: OTP table not found. Please run database_otp_update.sql in phpMyAdmin."));
            exit;
        } else {
            // Verify OTP - check if it exists for this email
            $check_stmt = $conn->prepare("SELECT * FROM otp_codes WHERE email = ? AND otp_code = ? AND purpose = 'password_reset' ORDER BY created_at DESC LIMIT 1");
            $check_stmt->bind_param("ss", $email, $otp);
            $check_stmt->execute();
            $check_result = $check_stmt->get_result();
            $otp_record = $check_result->fetch_assoc();
            $check_stmt->close();
            
            if (!$otp_record) {
                // OTP doesn't match - check if there's any OTP for this email
                $email_check = $conn->prepare("SELECT otp_code, used, expires_at FROM otp_codes WHERE email = ? AND purpose = 'password_reset' ORDER BY created_at DESC LIMIT 1");
                $email_check->bind_param("s", $email);
                $email_check->execute();
                $email_result = $email_check->get_result();
                $email_otp = $email_result->fetch_assoc();
                $email_check->close();
                
                closeDBConnection($conn);
                
                if ($email_otp) {
                    $error_msg = "OTP code mismatch. Please check the code from your email and try again.";
                } else {
                    $error_msg = "No OTP found for this email. Please request a new OTP.";
                }
                header("Location: forgot_password.php?step=2&email=" . urlencode($email) . "&error=" . urlencode($error_msg));
                exit;
            } elseif ($otp_record['used'] == 1) {
                closeDBConnection($conn);
                header("Location: forgot_password.php?step=2&email=" . urlencode($email) . "&error=" . urlencode("This OTP has already been used. Please request a new OTP."));
                exit;
            } elseif (strtotime($otp_record['expires_at']) < time()) {
                closeDBConnection($conn);
                header("Location: forgot_password.php?step=2&email=" . urlencode($email) . "&error=" . urlencode("OTP has expired. Please request a new OTP."));
                exit;
            } else {
                // OTP is valid - mark as used
                $update_stmt = $conn->prepare("UPDATE otp_codes SET used = 1 WHERE otp_id = ?");
                $update_stmt->bind_param("i", $otp_record['otp_id']);
                $update_stmt->execute();
                $update_stmt->close();
                
                // Store verified email in session for password reset
                $_SESSION['reset_email'] = $email;
                $_SESSION['otp_verified'] = true;
                
                closeDBConnection($conn);
                
                // Redirect to same page without POST data to show password reset form
                header("Location: reset_password.php");
                exit;
            }
        }
    }
}

// Check if OTP is verified (from session)
if (isset($_SESSION['otp_verified']) && $_SESSION['otp_verified'] === true) {
    $email = $_SESSION['reset_email'] ?? '';
}

// If no verified email, redirect back
if (empty($email) && !isset($_POST['otp'])) {
    header("Location: forgot_password.php?step=2");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
    <link rel="stylesheet" href="CSS/auth.css">
</head>
<body>

<div class="auth-wrapper">
    <a href="login.php" class="back-btn">&#8592;</a>

    <div class="auth-card">
        <h1>RESET PASSWORD</h1>

        <?php if (!empty($error)): ?>
            <div style="color: #e74c3c; margin-bottom: 15px; padding: 10px; background: #2c2c2c; border-radius: 6px;">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($email)): ?>
            <form action="update_password.php" method="POST">
                <input type="hidden" name="email" value="<?php echo htmlspecialchars($email); ?>">

                <label>enter new password:</label>
                <div class="password-field">
                    <input type="password" id="password" name="password" required>
                    <span class="toggle-password" onclick="togglePassword('password', this)">👁</span>
                </div>

                <label>re-enter new password:</label>
                <div class="password-field">
                    <input type="password" id="confirm_password" name="confirm_password" required>
                    <span class="toggle-password" onclick="togglePassword('confirm_password', this)">👁</span>
                </div>

                <button class="auth-btn">done</button>
            </form>
        <?php else: ?>
            <p>Please verify your OTP first.</p>
            <a href="forgot_password.php?step=2">Back to OTP verification</a>
        <?php endif; ?>
    </div>
</div>

<script>
function togglePassword(inputId, icon) {
    const input = document.getElementById(inputId);
    if (!input) return;
    
    if (input.type === "password") {
        input.type = "text";
        icon.textContent = "👁";
    } else {
        input.type = "password";
        icon.textContent = "👁";
    }
}
</script>

</body>
</html>
