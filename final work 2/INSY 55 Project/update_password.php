<?php
require_once 'config/database.php';
require_once 'config/session_helper.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    // Validate
    if (empty($email) || !isset($_SESSION['otp_verified']) || $_SESSION['otp_verified'] !== true) {
        header("Location: forgot_password.php?step=2&error=" . urlencode("Please verify your OTP first"));
        exit;
    }
    
    if (empty($password) || strlen($password) < 6) {
        header("Location: reset_password.php?error=" . urlencode("Password must be at least 6 characters"));
        exit;
    }
    
    if ($password !== $confirm_password) {
        header("Location: reset_password.php?error=" . urlencode("Passwords do not match"));
        exit;
    }
    
    $conn = getDBConnection();
    
    // Hash new password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    // Update password in members table
    $stmt = $conn->prepare("UPDATE members SET password = ? WHERE email = ?");
    $stmt->bind_param("ss", $hashed_password, $email);
    $update_member = $stmt->execute();
    $affected_members = $conn->affected_rows;
    $stmt->close();
    
    // If not found in members, try accounts table
    if ($affected_members === 0) {
        $stmt = $conn->prepare("UPDATE accounts SET password = ? WHERE email = ?");
        $stmt->bind_param("ss", $hashed_password, $email);
        $update_account = $stmt->execute();
        $affected_accounts = $conn->affected_rows;
        $stmt->close();
        
        if ($affected_accounts === 0) {
            closeDBConnection($conn);
            header("Location: reset_password.php?error=" . urlencode("Email not found"));
            exit;
        }
    }
    
    // Clear reset session variables
    unset($_SESSION['reset_email']);
    unset($_SESSION['otp_verified']);
    
    closeDBConnection($conn);
    
    // Redirect to login page with success message
    header("Location: login.php?success=" . urlencode("Password reset successfully! Please login with your new password."));
    exit;
} else {
    header("Location: forgot_password.php");
    exit;
}
?>

