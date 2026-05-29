<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Signup</title>
    <link rel="stylesheet" href="CSS/auth.css">
</head>
<body>

<div class="auth-wrapper auth-box">
<a href="index.php" class="back-btn">&#8592;</a>
    <div class="auth-card">
        <h1>SIGNUP</h1>

        <?php if (isset($_GET['error'])): ?>
            <div style="color: #e74c3c; margin-bottom: 15px; padding: 10px; background: #2c2c2c; border-radius: 6px;">
                <?php echo htmlspecialchars($_GET['error']); ?>
            </div>
        <?php endif; ?>
        
        <form action="send_otp.php" method="POST">
            <label>Username</label>
            <input type="text" name="username" required>

            <label>Email</label>
            <input type="email" name="email" required>

            <label>Password</label>
            <input type="password" name="password" required>

            <label>Re-enter Password</label>
            <input type="password" name="confirm_password" required>

            <button type="submit" class="auth-btn">Signup</button>
        </form>

        <p>Already have an account? click <a href="login.php">here</a></p>
    </div>
</div>

</body>
</html>
