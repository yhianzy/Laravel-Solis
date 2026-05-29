<?php
// Script to fix admin password - generates correct hash for admin123
require_once 'config/database.php';

$conn = getDBConnection();

// Generate a new password hash for admin123
$new_password = 'admin123';
$hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

echo "<h2>Admin Password Fix</h2>";
echo "<p>New password hash for 'admin123':</p>";
echo "<pre>" . htmlspecialchars($hashed_password) . "</pre>";

// Update the admin account password
$stmt = $conn->prepare("UPDATE accounts SET password = ? WHERE email = 'admin@gmail.com'");
$stmt->bind_param("s", $hashed_password);

if ($stmt->execute()) {
    echo "<p style='color: green;'><strong>✓ Admin password updated successfully!</strong></p>";
    echo "<p>You can now login with:</p>";
    echo "<ul>";
    echo "<li>Email: <strong>admin@gmail.com</strong></li>";
    echo "<li>Password: <strong>admin123</strong></li>";
    echo "</ul>";
    
    // Verify it works
    $check_stmt = $conn->prepare("SELECT password FROM accounts WHERE email = 'admin@gmail.com'");
    $check_stmt->execute();
    $result = $check_stmt->get_result();
    $admin = $result->fetch_assoc();
    $check_stmt->close();
    
    if (password_verify($new_password, $admin['password'])) {
        echo "<p style='color: green;'><strong>✓ Password verification test PASSED</strong></p>";
    } else {
        echo "<p style='color: red;'><strong>✗ Password verification test FAILED - something went wrong</strong></p>";
    }
} else {
    echo "<p style='color: red;'><strong>✗ Failed to update password: " . $conn->error . "</strong></p>";
}

$stmt->close();
closeDBConnection($conn);
?>

