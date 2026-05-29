<?php
require_once 'config/database.php';
require_once 'config/session_helper.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($email) || empty($password)) {
        header("Location: login.php?error=" . urlencode("Email and password are required"));
        exit;
    }
    
    $conn = getDBConnection();
    
    // Check in accounts table first (admin/staff have priority)
    $stmt = $conn->prepare("SELECT user_id, username, email, password, type FROM accounts WHERE email = ? AND status = 'Working'");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        // Verify password
        if (password_verify($password, $user['password'])) {
            // Login successful - set session variables
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['user_type'] = strtolower($user['type']);
            
            // Log login activity (Philippines timezone)
            date_default_timezone_set('Asia/Manila');
            $log_action = "Login";
            $log_description = "User logged in";
            $login_time = date('Y-m-d H:i:s');
            $log_stmt = $conn->prepare("INSERT INTO activity_logs (user_id, user_type, username, email, action, description, login_time) VALUES (?, 'admin', ?, ?, ?, ?, ?)");
            $log_stmt->bind_param("isssss", $user['user_id'], $user['username'], $user['email'], $log_action, $log_description, $login_time);
            $log_stmt->execute();
            $log_stmt->close();
            
            $stmt->close();
            closeDBConnection($conn);
            
            // Redirect to admin dashboard
            header("Location: admin/dashboard.php");
            exit;
        } else {
            $error = "Invalid email or password";
        }
    } else {
        // Check in members table
        $stmt->close();
        // Check if status column exists in members table
        $check_column = $conn->query("SHOW COLUMNS FROM members LIKE 'status'");
        if ($check_column->num_rows > 0) {
            $stmt = $conn->prepare("SELECT member_id, username, email, password FROM members WHERE email = ? AND (status = 'Active' OR status IS NULL)");
        } else {
            $stmt = $conn->prepare("SELECT member_id, username, email, password FROM members WHERE email = ?");
        }
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            
            // Verify password
            if (password_verify($password, $user['password'])) {
                // Login successful - set session variables
                $_SESSION['member_id'] = $user['member_id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['user_type'] = 'member';
                
                // Log login activity (Philippines timezone)
                date_default_timezone_set('Asia/Manila');
                $log_action = "Login";
                $log_description = "Member logged in";
                $login_time = date('Y-m-d H:i:s');
                $log_stmt = $conn->prepare("INSERT INTO activity_logs (user_id, user_type, username, email, action, description, login_time) VALUES (?, 'member', ?, ?, ?, ?, ?)");
                $log_stmt->bind_param("isssss", $user['member_id'], $user['username'], $user['email'], $log_action, $log_description, $login_time);
                $log_stmt->execute();
                $log_stmt->close();
                
                $stmt->close();
                closeDBConnection($conn);
                
                // Redirect to home page
                header("Location: index.php");
                exit;
            } else {
                $error = "Invalid email or password";
            }
        } else {
            $error = "Invalid email or password";
        }
    }
    
    $stmt->close();
    closeDBConnection($conn);
    
    header("Location: login.php?error=" . urlencode($error));
    exit;
} else {
    header("Location: login.php");
    exit;
}
?>
