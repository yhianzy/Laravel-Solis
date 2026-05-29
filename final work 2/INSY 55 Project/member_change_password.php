<?php
require_once 'config/database.php';
require_once 'config/session_helper.php';

// Check if user is logged in as a member
if (!isLoggedIn() || !isset($_SESSION['member_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current_password = $_POST['current_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
        header("Location: member_account.php?error=" . urlencode("All fields are required"));
        exit;
    }
    
    if (strlen($new_password) < 6) {
        header("Location: member_account.php?error=" . urlencode("New password must be at least 6 characters"));
        exit;
    }
    
    if ($new_password !== $confirm_password) {
        header("Location: member_account.php?error=" . urlencode("New passwords do not match"));
        exit;
    }
    
    $member_id = $_SESSION['member_id'];
    $conn = getDBConnection();
    
    // Get current password hash
    $stmt = $conn->prepare("SELECT password FROM members WHERE member_id = ?");
    $stmt->bind_param("i", $member_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $member = $result->fetch_assoc();
    $stmt->close();
    
    if (!$member) {
        closeDBConnection($conn);
        header("Location: login.php");
        exit;
    }
    
    // Verify current password
    if (!password_verify($current_password, $member['password'])) {
        closeDBConnection($conn);
        header("Location: member_account.php?error=" . urlencode("Current password is incorrect"));
        exit;
    }
    
    // Hash new password and update
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
    $update_stmt = $conn->prepare("UPDATE members SET password = ? WHERE member_id = ?");
    $update_stmt->bind_param("si", $hashed_password, $member_id);
    
    if ($update_stmt->execute()) {
        $update_stmt->close();
        closeDBConnection($conn);
        header("Location: member_account.php?success=" . urlencode("Password changed successfully!"));
        exit;
    } else {
        $error = "Failed to update password: " . $conn->error;
        $update_stmt->close();
        closeDBConnection($conn);
        header("Location: member_account.php?error=" . urlencode($error));
        exit;
    }
} else {
    header("Location: member_account.php");
    exit;
}
?>

