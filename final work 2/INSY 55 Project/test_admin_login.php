<?php
// Test script to check admin account and password
require_once 'config/database.php';

$conn = getDBConnection();

// Check if admin account exists
$stmt = $conn->prepare("SELECT user_id, username, email, password, type, status FROM accounts WHERE email = ?");
$email = 'admin@gmail.com';
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

echo "<h2>Admin Account Check</h2>";

if ($result->num_rows > 0) {
    $admin = $result->fetch_assoc();
    echo "<p><strong>Account Found:</strong></p>";
    echo "<ul>";
    echo "<li>User ID: " . htmlspecialchars($admin['user_id']) . "</li>";
    echo "<li>Username: " . htmlspecialchars($admin['username']) . "</li>";
    echo "<li>Email: " . htmlspecialchars($admin['email']) . "</li>";
    echo "<li>Type: " . htmlspecialchars($admin['type']) . "</li>";
    echo "<li>Status: " . htmlspecialchars($admin['status']) . "</li>";
    echo "<li>Password Hash: " . htmlspecialchars($admin['password']) . "</li>";
    echo "</ul>";
    
    // Test password verification
    $test_password = 'admin123';
    echo "<h3>Password Verification Test:</h3>";
    echo "<p>Testing password: <strong>admin123</strong></p>";
    
    if (password_verify($test_password, $admin['password'])) {
        echo "<p style='color: green;'><strong>✓ Password verification SUCCESSFUL</strong></p>";
    } else {
        echo "<p style='color: red;'><strong>✗ Password verification FAILED</strong></p>";
        echo "<p>This means the password hash in the database doesn't match 'admin123'</p>";
        echo "<p>Let's create a new hash for 'admin123':</p>";
        $new_hash = password_hash($test_password, PASSWORD_DEFAULT);
        echo "<p>New hash: <code>" . htmlspecialchars($new_hash) . "</code></p>";
        echo "<p>Run this SQL to update the password:</p>";
        echo "<pre>UPDATE accounts SET password = '" . htmlspecialchars($new_hash) . "' WHERE email = 'admin@gmail.com';</pre>";
    }
} else {
    echo "<p style='color: red;'><strong>Admin account not found!</strong></p>";
    echo "<p>You may need to run the database_setup.sql file again.</p>";
}

$stmt->close();
closeDBConnection($conn);
?>

