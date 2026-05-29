<?php
require_once 'config/database.php';
require_once 'config/session_helper.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: forgot_password.php?step=1&error=" . urlencode("Valid email is required"));
        exit;
    }
    
    $conn = getDBConnection();
    
    // Check if email exists in members table
    $check_stmt = $conn->prepare("SELECT member_id FROM members WHERE email = ?");
    $check_stmt->bind_param("s", $email);
    $check_stmt->execute();
    $result = $check_stmt->get_result();
    
    if ($result->num_rows === 0) {
        // Check accounts table
        $check_stmt->close();
        $check_stmt = $conn->prepare("SELECT user_id FROM accounts WHERE email = ?");
        $check_stmt->bind_param("s", $email);
        $check_stmt->execute();
        $result = $check_stmt->get_result();
        
        if ($result->num_rows === 0) {
            $check_stmt->close();
            closeDBConnection($conn);
            header("Location: forgot_password.php?step=1&error=" . urlencode("Email not found in our system"));
            exit;
        }
    }
    $check_stmt->close();
    
    // Generate 6-digit OTP
    $otp = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
    
    // Store OTP in database (expires in 10 minutes)
    $expires_at = date('Y-m-d H:i:s', strtotime('+10 minutes'));
    
    // Delete old OTPs for this email and purpose
    $delete_stmt = $conn->prepare("DELETE FROM otp_codes WHERE email = ? AND purpose = 'password_reset'");
    $delete_stmt->bind_param("s", $email);
    $delete_stmt->execute();
    $delete_stmt->close();
    
    // Insert new OTP
    $otp_stmt = $conn->prepare("INSERT INTO otp_codes (email, otp_code, purpose, expires_at) VALUES (?, ?, 'password_reset', ?)");
    $otp_stmt->bind_param("sss", $email, $otp, $expires_at);
    
    if ($otp_stmt->execute()) {
        // Send OTP via email
        $subject = "Athletiqs Gym - Password Reset OTP";
        $message = "Your password reset code is: $otp\n\nThis code will expire in 10 minutes.\n\nIf you didn't request this, please ignore this email.";
        $headers = "From: Athletiqs Gym <noreply@athletiqs.com>\r\n";
        $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
        
        // In development, log the OTP
        if (mail($email, $subject, $message, $headers)) {
            error_log("Password reset OTP sent to $email: $otp");
        } else {
            error_log("Password reset OTP for $email: $otp (Email sending failed, but OTP is logged)");
        }
        
        // Store email in session for next step
        $_SESSION['reset_email'] = $email;
        
        // Redirect to OTP verification step
        header("Location: forgot_password.php?step=2&email=" . urlencode($email));
        exit;
    } else {
        header("Location: forgot_password.php?step=1&error=" . urlencode("Failed to send OTP. Please try again."));
    }
    
    $otp_stmt->close();
    closeDBConnection($conn);
} else {
    header("Location: forgot_password.php");
    exit;
}
?>

