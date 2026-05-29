<?php
require_once 'config/database.php';
require_once 'config/session_helper.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    
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
    
    $conn = getDBConnection();
    
    // Check if email or username already exists
    $check_stmt = $conn->prepare("SELECT member_id FROM members WHERE email = ? OR username = ?");
    $check_stmt->bind_param("ss", $email, $username);
    $check_stmt->execute();
    $result = $check_stmt->get_result();
    
    if ($result->num_rows > 0) {
        $errors[] = "Email or username already exists";
        $check_stmt->close();
        closeDBConnection($conn);
        header("Location: signup.php?error=" . urlencode(implode(", ", $errors)));
        exit;
    }
    $check_stmt->close();
    
    if (empty($errors)) {
        // Store signup data in session temporarily
        $_SESSION['signup_username'] = $username;
        $_SESSION['signup_email'] = $email;
        $_SESSION['signup_password'] = password_hash($password, PASSWORD_DEFAULT);
        
        // Generate 6-digit OTP
        $otp = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
        
        // Store OTP in database (expires in 10 minutes)
        $expires_at = date('Y-m-d H:i:s', strtotime('+10 minutes'));
        
        // Delete old OTPs for this email and purpose
        $delete_stmt = $conn->prepare("DELETE FROM otp_codes WHERE email = ? AND purpose = 'signup'");
        $delete_stmt->bind_param("s", $email);
        $delete_stmt->execute();
        $delete_stmt->close();
        
        // Insert new OTP
        $otp_stmt = $conn->prepare("INSERT INTO otp_codes (email, otp_code, purpose, expires_at) VALUES (?, ?, 'signup', ?)");
        $otp_stmt->bind_param("sss", $email, $otp, $expires_at);
        
        if ($otp_stmt->execute()) {
            // Send OTP via email
            $subject = "Athletiqs Gym - Email Verification OTP";
            $message = "Your verification code is: $otp\n\nThis code will expire in 10 minutes.\n\nIf you didn't request this, please ignore this email.";
            $headers = "From: Athletiqs Gym <noreply@athletiqs.com>\r\n";
            $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
            
            // In development, you might want to log the OTP instead of actually sending
            // For production, use proper SMTP configuration
            if (mail($email, $subject, $message, $headers)) {
                // Email sent successfully
                error_log("OTP sent to $email: $otp"); // Log for development
            } else {
                // Log OTP for development purposes
                error_log("OTP for $email: $otp (Email sending failed, but OTP is logged)");
            }
            
            // Redirect to verify OTP page
            header("Location: verify_otp.php?email=" . urlencode($email));
            exit;
        } else {
            $errors[] = "Failed to generate OTP. Please try again.";
        }
        
        $otp_stmt->close();
    }
    
    closeDBConnection($conn);
    
    if (!empty($errors)) {
        header("Location: signup.php?error=" . urlencode(implode(", ", $errors)));
        exit;
    }
} else {
    header("Location: signup.php");
    exit;
}
?>

