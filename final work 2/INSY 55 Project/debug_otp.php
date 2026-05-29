<?php
// Debug script to check OTP in database
// Access this via: http://localhost/INSY%2055%20Project/debug_otp.php?email=your@email.com
require_once 'config/database.php';

$email = $_GET['email'] ?? '';

if (empty($email)) {
    die("Please provide email as parameter: ?email=your@email.com");
}

$conn = getDBConnection();

// Check if table exists
$table_check = $conn->query("SHOW TABLES LIKE 'otp_codes'");
if ($table_check->num_rows == 0) {
    die("ERROR: otp_codes table does not exist! Please run database_otp_update.sql");
}

// Get all OTPs for this email
$stmt = $conn->prepare("SELECT otp_id, email, otp_code, purpose, used, created_at, expires_at, NOW() as current_time, 
    CASE WHEN expires_at > NOW() THEN 'Valid' ELSE 'Expired' END as status
    FROM otp_codes WHERE email = ? AND purpose = 'signup' ORDER BY created_at DESC");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

echo "<h2>OTP Debug for: " . htmlspecialchars($email) . "</h2>";
echo "<table border='1' cellpadding='5'>";
echo "<tr><th>OTP Code</th><th>Purpose</th><th>Used</th><th>Created At</th><th>Expires At</th><th>Current Time</th><th>Status</th></tr>";

if ($result->num_rows == 0) {
    echo "<tr><td colspan='7'>No OTP found for this email</td></tr>";
} else {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['otp_code']) . "</td>";
        echo "<td>" . htmlspecialchars($row['purpose']) . "</td>";
        echo "<td>" . ($row['used'] ? 'Yes' : 'No') . "</td>";
        echo "<td>" . htmlspecialchars($row['created_at']) . "</td>";
        echo "<td>" . htmlspecialchars($row['expires_at']) . "</td>";
        echo "<td>" . htmlspecialchars($row['current_time']) . "</td>";
        echo "<td>" . htmlspecialchars($row['status']) . "</td>";
        echo "</tr>";
    }
}

echo "</table>";
$stmt->close();
closeDBConnection($conn);
?>

