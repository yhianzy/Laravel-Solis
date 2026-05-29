<?php
// Diagnostic script to check memberships and payments
require_once 'config/database.php';

$conn = getDBConnection();

echo "<h2>Memberships Check</h2>";

// Check total memberships
$total_memberships = $conn->query("SELECT COUNT(*) as count FROM memberships")->fetch_assoc()['count'];
echo "<p>Total memberships in database: <strong>$total_memberships</strong></p>";

// Check total payments
$total_payments = $conn->query("SELECT COUNT(*) as count FROM payments")->fetch_assoc()['count'];
echo "<p>Total payments in database: <strong>$total_payments</strong></p>";

// Check payments without memberships
$orphan_payments = $conn->query("
    SELECT p.payment_id, p.member_id, p.membership_id, p.payment_type, p.status, p.payment_date
    FROM payments p
    LEFT JOIN memberships m ON p.membership_id = m.membership_id
    WHERE m.membership_id IS NULL
")->fetch_all(MYSQLI_ASSOC);

if (count($orphan_payments) > 0) {
    echo "<h3 style='color: red;'>⚠️ Payments without Memberships:</h3>";
    echo "<table border='1' cellpadding='5'>";
    echo "<tr><th>Payment ID</th><th>Member ID</th><th>Membership ID</th><th>Type</th><th>Status</th><th>Date</th></tr>";
    foreach ($orphan_payments as $payment) {
        echo "<tr>";
        echo "<td>{$payment['payment_id']}</td>";
        echo "<td>{$payment['member_id']}</td>";
        echo "<td>{$payment['membership_id']}</td>";
        echo "<td>{$payment['payment_type']}</td>";
        echo "<td>{$payment['status']}</td>";
        echo "<td>{$payment['payment_date']}</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p style='color: green;'>✅ All payments have corresponding memberships</p>";
}

// Check memberships without payments
$orphan_memberships = $conn->query("
    SELECT m.membership_id, m.member_id, m.membership_type, m.status, m.start_date
    FROM memberships m
    LEFT JOIN payments p ON m.membership_id = p.membership_id
    WHERE p.payment_id IS NULL
")->fetch_all(MYSQLI_ASSOC);

if (count($orphan_memberships) > 0) {
    echo "<h3 style='color: orange;'>⚠️ Memberships without Payments:</h3>";
    echo "<table border='1' cellpadding='5'>";
    echo "<tr><th>Membership ID</th><th>Member ID</th><th>Type</th><th>Status</th><th>Start Date</th></tr>";
    foreach ($orphan_memberships as $membership) {
        echo "<tr>";
        echo "<td>{$membership['membership_id']}</td>";
        echo "<td>{$membership['member_id']}</td>";
        echo "<td>{$membership['membership_type']}</td>";
        echo "<td>{$membership['status']}</td>";
        echo "<td>{$membership['start_date']}</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p style='color: green;'>✅ All memberships have corresponding payments</p>";
}

// Show recent memberships
echo "<h3>Recent Memberships (Last 10):</h3>";
$recent = $conn->query("
    SELECT m.membership_id, m.member_id, m.membership_type, m.status, m.start_date, m.end_date,
           p.payment_id, p.status as payment_status
    FROM memberships m
    LEFT JOIN payments p ON m.membership_id = p.membership_id
    ORDER BY m.membership_id DESC
    LIMIT 10
")->fetch_all(MYSQLI_ASSOC);

echo "<table border='1' cellpadding='5'>";
echo "<tr><th>Membership ID</th><th>Member ID</th><th>Type</th><th>Status</th><th>Start Date</th><th>Payment ID</th><th>Payment Status</th></tr>";
foreach ($recent as $row) {
    echo "<tr>";
    echo "<td>{$row['membership_id']}</td>";
    echo "<td>{$row['member_id']}</td>";
    echo "<td>{$row['membership_type']}</td>";
    echo "<td>{$row['status']}</td>";
    echo "<td>{$row['start_date']}</td>";
    echo "<td>" . ($row['payment_id'] ?? 'NULL') . "</td>";
    echo "<td>" . ($row['payment_status'] ?? 'NULL') . "</td>";
    echo "</tr>";
}
echo "</table>";

closeDBConnection($conn);
?>

