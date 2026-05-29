<?php
require_once 'config/database.php';
require_once 'config/session_helper.php';

// Check if user is logged in as a member
if (!isLoggedIn() || !isset($_SESSION['member_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $member_id = $_SESSION['member_id'];
    $conn = getDBConnection();
    
    // Check if status column exists, if not, we'll use a different approach
    // For now, we'll add status field if it doesn't exist, or update it if it does
    $check_column = $conn->query("SHOW COLUMNS FROM members LIKE 'status'");
    
    if ($check_column->num_rows > 0) {
        // Status column exists, update it
        $stmt = $conn->prepare("UPDATE members SET status = 'Archived' WHERE member_id = ?");
        $stmt->bind_param("i", $member_id);
    } else {
        // Status column doesn't exist - we can't archive, so we'll just log them out
        // In a real scenario, you'd want to add the column first
        closeDBConnection($conn);
        session_destroy();
        header("Location: index.php?error=" . urlencode("Account deletion feature requires database update. Please contact administrator."));
        exit;
    }
    
    if ($stmt->execute()) {
        $stmt->close();
        closeDBConnection($conn);
        
        // Destroy session and log out
        session_destroy();
        
        header("Location: index.php?success=" . urlencode("Your account has been archived successfully."));
        exit;
    } else {
        $error = "Failed to archive account: " . $conn->error;
        $stmt->close();
        closeDBConnection($conn);
        header("Location: member_account.php?error=" . urlencode($error));
        exit;
    }
} else {
    header("Location: member_account.php");
    exit;
}
?>

