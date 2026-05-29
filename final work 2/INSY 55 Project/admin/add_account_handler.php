<?php
require_once '../config/database.php';
require_once '../config/session_helper.php';

// Check if user is logged in as admin
if (!isLoggedIn() || getUserType() !== 'admin') {
    header("Location: ../login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $type = $_POST['type'] ?? '';
    
    // Validation
    $errors = [];
    
    if (empty($username)) {
        $errors[] = "Username is required";
    }
    
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Valid email is required";
    }
    
    if (empty($password) || strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters";
    }
    
    if ($password !== $confirm_password) {
        $errors[] = "Passwords do not match";
    }
    
    if (empty($type) || !in_array($type, ['Admin', 'Staff'])) {
        $errors[] = "Please select a valid account type";
    }
    
    $conn = getDBConnection();
    
    // Check if email or username already exists in accounts table
    $check_stmt = $conn->prepare("SELECT user_id FROM accounts WHERE email = ? OR username = ?");
    $check_stmt->bind_param("ss", $email, $username);
    $check_stmt->execute();
    $result = $check_stmt->get_result();
    
    if ($result->num_rows > 0) {
        $errors[] = "Email or username already exists in accounts";
    }
    $check_stmt->close();
    
    // Check if email or username exists in members table
    $check_member = $conn->prepare("SELECT member_id FROM members WHERE email = ? OR username = ?");
    $check_member->bind_param("ss", $email, $username);
    $check_member->execute();
    $member_result = $check_member->get_result();
    
    if ($member_result->num_rows > 0) {
        $errors[] = "Email or username already exists in members";
    }
    $check_member->close();
    
    if (empty($errors)) {
        // Hash password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        // Capitalize first letter of type
        $type = ucfirst($type);
        
        // Insert into accounts table
        $insert_stmt = $conn->prepare("INSERT INTO accounts (username, email, password, type, status) VALUES (?, ?, ?, ?, 'Working')");
        $insert_stmt->bind_param("ssss", $username, $email, $hashed_password, $type);
        
        if ($insert_stmt->execute()) {
            $insert_stmt->close();
            closeDBConnection($conn);
            header("Location: accounts.php?success=" . urlencode("Account created successfully!"));
            exit;
        } else {
            $errors[] = "Failed to create account: " . $conn->error;
        }
        
        $insert_stmt->close();
    }
    
    closeDBConnection($conn);
    
    // Redirect back with errors
    $error_msg = implode(", ", $errors);
    header("Location: add_account.php?error=" . urlencode($error_msg));
    exit;
} else {
    header("Location: add_account.php");
    exit;
}
?>

