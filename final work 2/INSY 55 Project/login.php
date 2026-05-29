<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="CSS/auth.css">
</head>
<body>

<div class="auth-wrapper">
<a href="signup.php" class="back-btn">&#8592;</a>
    <div class="auth-card">
        <h1>LOGIN</h1>

        <?php if (isset($_GET['error'])): ?>
            <div style="color: #e74c3c; margin-bottom: 15px; padding: 10px; background: #2c2c2c; border-radius: 6px;">
                <?php echo htmlspecialchars($_GET['error']); ?>
            </div>
        <?php endif; ?>
        
        <?php if (isset($_GET['success'])): ?>
            <div style="color: #2ecc71; margin-bottom: 15px; padding: 10px; background: #2c2c2c; border-radius: 6px;">
                <?php echo htmlspecialchars($_GET['success']); ?>
            </div>
        <?php endif; ?>
        
        <form action="login_handler.php" method="POST">
            <label>Email</label>
            <input type="email" name="email" required>

            <label>Password</label>
            <input type="password" name="password" required>

            <!-- Forgot password -->
            <div class="forgot-row">
                <a href="forgot_password.php">Forgot password?</a>
            </div>

            <button class="auth-btn">Login</button>
        </form>
    </div>
</div>

</body>
</html>
