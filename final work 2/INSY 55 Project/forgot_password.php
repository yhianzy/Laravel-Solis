<?php
require_once 'config/session_helper.php';

$step = $_GET['step'] ?? '1';
$email = $_GET['email'] ?? $_SESSION['reset_email'] ?? '';
$error = $_GET['error'] ?? '';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Account Recovery</title>
    <link rel="stylesheet" href="CSS/auth.css">
</head>
<body>

<div class="auth-wrapper">
    <a href="login.php" class="back-btn">&#8592;</a>

    <div class="auth-card">
        <h1>ACCOUNT RECOVERY</h1>

        <?php if (!empty($error)): ?>
            <div style="color: #e74c3c; margin-bottom: 15px; padding: 10px; background: #2c2c2c; border-radius: 6px;">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <?php if ($step == '1'): ?>
            <!-- STEP 1: EMAIL -->
            <form action="send_otp_forgot.php" method="POST">
                <label>enter a valid email:</label>
                <input type="email" name="email" required>

                <button class="auth-btn">submit</button>
            </form>
        <?php elseif ($step == '2'): ?>
            <!-- STEP 2: OTP -->
            <?php if (empty($email)): ?>
                <div style="color: #e74c3c; margin-bottom: 15px; padding: 10px; background: #2c2c2c; border-radius: 6px;">
                    No email found. Please start from step 1.
                </div>
                <a href="forgot_password.php?step=1" style="color: white; text-decoration: underline;">Go to step 1</a>
            <?php else: ?>
                <form action="reset_password.php" method="POST">
                    <input type="hidden" name="email" value="<?php echo htmlspecialchars($email); ?>">
                    <label>enter otp received for <?php echo htmlspecialchars($email); ?>:</label>
                    <input type="text" name="otp" required maxlength="6" placeholder="Enter 6-digit OTP" pattern="[0-9]{6}" autocomplete="off">

                    <button class="auth-btn">next</button>
                </form>
                
                <p style="margin-top: 15px; font-size: 14px; color: #aaa;">
                    <a href="forgot_password.php?step=1" style="color: white;">Back to email step</a>
                </p>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>

</body>
</html>
