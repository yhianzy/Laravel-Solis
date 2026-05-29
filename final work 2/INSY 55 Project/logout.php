<?php
require_once 'config/database.php';
require_once 'config/session_helper.php';

// Check if user is logged in before logging out
if (isLoggedIn()) {
    $conn = getDBConnection();
    
    // Get user info from session
    $user_type = getUserType();
    $username = getLoggedInUsername() ?? '';
    $email = $_SESSION['email'] ?? '';
    
    // Get user_id based on type
    $user_id = null;
    if ($user_type === 'admin' || $user_type === 'staff') {
        $user_id = $_SESSION['user_id'] ?? null;
    } elseif ($user_type === 'member') {
        $user_id = $_SESSION['member_id'] ?? null;
    }
    
    // Log logout activity if we have user info (Philippines timezone)
    if ($user_id && $username) {
        date_default_timezone_set('Asia/Manila');
        $log_action = "Logout";
        $log_description = "User logged out";
        $logout_time = date('Y-m-d H:i:s');
        
        // Try to update existing login log with logout time, or insert new
        // First, find the most recent login log for this user without a logout time
        $find_login = $conn->prepare("SELECT log_id FROM activity_logs WHERE user_id = ? AND user_type = ? AND action = 'Login' AND logout_time IS NULL ORDER BY log_date DESC LIMIT 1");
        $find_login->bind_param("is", $user_id, $user_type);
        $find_login->execute();
        $login_result = $find_login->get_result();
        
        if ($login_result->num_rows > 0) {
            // Update existing login record with logout time
            $login_log = $login_result->fetch_assoc();
            $update_log = $conn->prepare("UPDATE activity_logs SET logout_time = ? WHERE log_id = ?");
            $update_log->bind_param("si", $logout_time, $login_log['log_id']);
            $update_log->execute();
            $update_log->close();
        } else {
            // Create new logout log entry
            $log_stmt = $conn->prepare("INSERT INTO activity_logs (user_id, user_type, username, email, action, description, logout_time) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $log_stmt->bind_param("issssss", $user_id, $user_type, $username, $email, $log_action, $log_description, $logout_time);
            $log_stmt->execute();
            $log_stmt->close();
        }
        
        $find_login->close();
    }
    
    closeDBConnection($conn);
}

// Destroy session
session_destroy();

// Redirect to home page
header("Location: index.php");
exit;
?>
