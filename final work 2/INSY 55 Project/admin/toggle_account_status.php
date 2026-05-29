<?php
require_once '../config/database.php';
require_once '../config/session_helper.php';

// Check if user is logged in as admin
if (!isLoggedIn() || getUserType() !== 'admin') {
    header("Location: ../login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $account_type = $_POST['account_type'] ?? ''; // 'account' or 'member'
    $id = intval($_POST['id'] ?? 0);
    $current_status = $_POST['current_status'] ?? '';
    
    if (empty($account_type) || $id <= 0 || empty($current_status)) {
        header("Location: accounts.php?error=" . urlencode("Invalid request"));
        exit;
    }
    
    $conn = getDBConnection();
    
    // Toggle status
    $new_status = ($current_status === 'Working') ? 'Archived' : 'Working';
    
    if ($account_type === 'account') {
        // Update accounts table
        $stmt = $conn->prepare("UPDATE accounts SET status = ? WHERE user_id = ?");
        $stmt->bind_param("si", $new_status, $id);
    } else {
        // Update members table (if status field exists, otherwise skip)
        // For now, we'll skip members status toggle since members table doesn't have status
        // You can add status field to members table later if needed
        header("Location: accounts.php?error=" . urlencode("Status toggle for members not yet implemented"));
        exit;
    }
    
    if ($stmt->execute()) {
        $stmt->close();
        closeDBConnection($conn);
        header("Location: accounts.php?success=" . urlencode("Account status updated successfully!"));
        exit;
    } else {
        $error = "Failed to update status: " . $conn->error;
        $stmt->close();
        closeDBConnection($conn);
        header("Location: accounts.php?error=" . urlencode($error));
        exit;
    }
} else {
    header("Location: accounts.php");
    exit;
}
?>

