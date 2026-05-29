<?php
require_once 'config/database.php';

echo "<h2>Testing Database Connection</h2>";

try {
    $conn = getDBConnection();
    echo "<p style='color: green;'>✓ Database connected successfully!</p>";
    
    // Test query
    $result = $conn->query("SHOW TABLES");
    echo "<h3>Tables in database:</h3>";
    echo "<ul>";
    if ($result->num_rows > 0) {
        while($row = $result->fetch_array()) {
            echo "<li>" . $row[0] . "</li>";
        }
    } else {
        echo "<li style='color: orange;'>No tables found. Run database_setup.sql first!</li>";
    }
    echo "</ul>";
    
    closeDBConnection($conn);
} catch (Exception $e) {
    echo "<p style='color: red;'>✗ Connection failed: " . $e->getMessage() . "</p>";
    echo "<p>Make sure:</p>";
    echo "<ul>";
    echo "<li>MySQL is running in XAMPP</li>";
    echo "<li>Database 'athletiqs_gym' exists</li>";
    echo "<li>config/database.php has correct credentials</li>";
    echo "</ul>";
}
?>

