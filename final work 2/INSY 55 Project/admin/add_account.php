<?php include 'includes/header.php'; ?>
<?php include 'includes/sidebar.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="assets/css/admin.css">
</head>
<body>


<div class="main-content">

    <h2>Add Account</h2>

    <?php if (isset($_GET['error'])): ?>
        <div style="color: #e74c3c; margin-bottom: 15px; padding: 10px; background: #2c2c2c; border-radius: 6px; max-width: 400px;">
            <?php echo htmlspecialchars($_GET['error']); ?>
        </div>
    <?php endif; ?>
    
    <form class="add-form" action="add_account_handler.php" method="POST">

    <label>Username</label>
    <input type="text" name="username" required>

    <label>Email</label>
    <input type="email" name="email" required>

    <label>Password</label>
    <div class="password-field">
        <input type="password" id="password" name="password" required>
        <span class="toggle-password" onclick="togglePassword('password', this)">👁</span>
    </div>

    <label>Re-enter Password</label>
    <div class="password-field">
        <input type="password" id="confirm_password" name="confirm_password" required>
        <span class="toggle-password" onclick="togglePassword('confirm_password', this)">👁</span>
    </div>

    <label>Type</label>
    <select name="type" required>
        <option value="">Select role</option>
        <option value="Admin">Admin</option>
        <option value="Staff">Staff</option>
    </select>

    <div style="display: flex; gap: 10px;">
        <button type="submit" class="save-btn">Save</button>
        <button type="button" onclick="window.location.href='accounts.php'" style="padding: 10px; background: #666; color: white; border: none; border-radius: 10px; cursor: pointer; flex: 1;">Cancel</button>
    </div>

</form>


</div>
<script>
function togglePassword(inputId, icon) {
    const input = document.getElementById(inputId);

    if (input.type === "password") {
        input.type = "text";
        icon.textContent = "👁";
    } else {
        input.type = "password";
        icon.textContent = "👁";
    }
}
</script>

<style>
.add-form {
    display: flex;
    flex-direction: column;
    max-width: 400px;
    margin-top: 20px;
}
.add-form input,
.add-form select {
    margin-bottom: 15px;
    padding: 10px;
    border-radius: 10px;
    border: none;
}
.save-btn {
    padding: 10px;
    background: #27ae60;
    color: white;
    border: none;
    border-radius: 10px;
    cursor: pointer;
}
.password-field {
    position: relative;
}

.password-field input {
    width: 80%;
    padding-right: 40px;
}

.toggle-password {
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-80%);
    cursor: pointer;
    font-size: 18px;
}
</style>

</body>
</html>