<?php
require_once '../config/database.php';
require_once '../config/session_helper.php';

// Check if user is logged in as admin
if (!isLoggedIn() || getUserType() !== 'admin') {
    header("Location: ../login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $payment_id = intval($_POST['payment_id'] ?? 0);
    $action = $_POST['action'] ?? ''; // 'approve' or 'reject'
    
    if ($payment_id <= 0 || !in_array($action, ['approve', 'reject'])) {
        header("Location: payments.php?error=" . urlencode("Invalid request"));
        exit;
    }
    
    $conn = getDBConnection();
    
    // Get payment info to find associated membership
    $payment_stmt = $conn->prepare("SELECT membership_id, payment_type, amount FROM payments WHERE payment_id = ?");
    $payment_stmt->bind_param("i", $payment_id);
    $payment_stmt->execute();
    $payment_result = $payment_stmt->get_result();
    $payment_data = $payment_result->fetch_assoc();
    $payment_stmt->close();
    
    if (!$payment_data) {
        closeDBConnection($conn);
        header("Location: payments.php?error=" . urlencode("Payment not found"));
        exit;
    }
    
    $membership_id = $payment_data['membership_id'];
    
    // Start transaction
    $conn->begin_transaction();
    
    try {
        // Update payment status
        if ($action === 'approve') {
            $payment_status = 'Approved';
            $membership_status = 'Active';
        } else {
            $payment_status = 'Rejected';
            $membership_status = 'Expired'; // Set to Expired if rejected
        }
        
        $update_payment = $conn->prepare("UPDATE payments SET status = ? WHERE payment_id = ?");
        $update_payment->bind_param("si", $payment_status, $payment_id);
        $update_payment->execute();
        $update_payment->close();
        
        // Update membership status based on action
        if ($membership_id) {
            $update_membership = $conn->prepare("UPDATE memberships SET status = ? WHERE membership_id = ?");
            $update_membership->bind_param("si", $membership_status, $membership_id);
            $update_membership->execute();
            $update_membership->close();
        }
        
        // Commit transaction
        $conn->commit();
        closeDBConnection($conn);
        
        $success_msg = $action === 'approve' ? "Payment approved successfully!" : "Payment rejected.";
        header("Location: payments.php?success=" . urlencode($success_msg));
        exit;
    } catch (Exception $e) {
        // Rollback on error
        $conn->rollback();
        closeDBConnection($conn);
        header("Location: payments.php?error=" . urlencode("Failed to update payment: " . $e->getMessage()));
        exit;
    }
} else {
    header("Location: payments.php");
    exit;
}
?>

