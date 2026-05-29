<?php
require_once 'config/database.php';
require_once 'config/session_helper.php';

// Check if user is logged in as a member
if (!isLoggedIn() || !isset($_SESSION['member_id'])) {
    header("Location: login.php");
    exit;
}

$member_id = $_SESSION['member_id'];
$conn = getDBConnection();

// Get member information
$member_stmt = $conn->prepare("SELECT member_id, username, email, date_registered, status FROM members WHERE member_id = ?");
$member_stmt->bind_param("i", $member_id);
$member_stmt->execute();
$member_result = $member_stmt->get_result();
$member = $member_result->fetch_assoc();
$member_stmt->close();

if (!$member) {
    header("Location: login.php");
    exit;
}

// Get memberships for this member
$memberships_stmt = $conn->prepare("
    SELECT 
        m.membership_id,
        m.membership_type,
        m.start_date,
        m.end_date,
        m.status as membership_status,
        COALESCE(p.payment_date, m.start_date) as purchase_date,
        CASE 
            WHEN m.status = 'Expired' THEN 'Expired'
            WHEN m.status = 'Pending' THEN 'Pending'
            WHEN m.status = 'Active' AND m.end_date >= CURDATE() THEN 'Active'
            WHEN m.end_date < CURDATE() THEN 'Expired'
            ELSE 'Pending'
        END as display_status
    FROM memberships m
    LEFT JOIN payments p ON m.membership_id = p.membership_id AND p.member_id = m.member_id
    WHERE m.member_id = ?
    ORDER BY m.start_date DESC
");
$memberships_stmt->bind_param("i", $member_id);
$memberships_stmt->execute();
$memberships_result = $memberships_stmt->get_result();
closeDBConnection($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Management</title>
    <link rel="stylesheet" href="CSS/auth.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body {
            background: #111;
            color: white;
            font-family: 'Poppins', sans-serif;
        }
        
        .account-container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 20px;
        }
        
        .account-header {
            margin-bottom: 30px;
        }
        
        .back-link {
            color: white;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 20px;
            font-size: 14px;
        }
        
        .back-link:hover {
            text-decoration: underline;
        }
        
        .account-info-card {
            background: #2c2c2c;
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 30px;
        }
        
        .account-info-card h2 {
            margin-top: 0;
            margin-bottom: 20px;
            color: white;
        }
        
        .info-row {
            display: flex;
            margin-bottom: 15px;
            padding: 10px 0;
            border-bottom: 1px solid #444;
        }
        
        .info-label {
            font-weight: 600;
            width: 150px;
            color: #aaa;
        }
        
        .info-value {
            color: white;
        }
        
        .action-buttons {
            display: flex;
            gap: 15px;
            margin-top: 25px;
        }
        
        .btn {
            padding: 12px 24px;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            transition: 0.2s;
        }
        
        .btn-change-password {
            background: #3498db;
            color: white;
        }
        
        .btn-change-password:hover {
            background: #2980b9;
        }
        
        .btn-delete {
            background: #e74c3c;
            color: white;
        }
        
        .btn-delete:hover {
            background: #c0392b;
        }
        
        .services-table {
            background: #2c2c2c;
            border-radius: 12px;
            padding: 25px;
            overflow-x: auto;
        }
        
        .services-table h2 {
            margin-top: 0;
            margin-bottom: 20px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        table th,
        table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #444;
        }
        
        table th {
            background: #1f1f1f;
            color: #aaa;
            font-weight: 600;
            font-size: 14px;
        }
        
        table td {
            color: white;
        }
        
        .status-badge {
            padding: 5px 12px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
        }
        
        .status-pending {
            background: #f39c12;
            color: black;
        }
        
        .status-active {
            background: #2ecc71;
            color: black;
        }
        
        .status-expired {
            background: #e74c3c;
            color: white;
        }
        
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
        }
        
        .modal-content {
            background: #2c2c2c;
            margin: 15% auto;
            padding: 30px;
            border-radius: 12px;
            width: 90%;
            max-width: 500px;
        }
        
        .modal-content h3 {
            margin-top: 0;
        }
        
        .modal-buttons {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }
        
        .btn-cancel {
            background: #666;
            color: white;
        }
        
        .btn-cancel:hover {
            background: #555;
        }
        
        .btn-confirm {
            background: #e74c3c;
            color: white;
        }
        
        .btn-confirm:hover {
            background: #c0392b;
        }
        
        .success-msg {
            background: #2ecc71;
            color: white;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        
        .error-msg {
            background: #e74c3c;
            color: white;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<div class="account-container">
    <a href="index.php" class="back-link">
        <i class="fa-solid fa-arrow-left"></i> Back to Home
    </a>
    
    <div class="account-header">
        <h1>Account Management</h1>
    </div>
    
    <?php if (isset($_GET['success'])): ?>
        <div class="success-msg">
            <?php echo htmlspecialchars($_GET['success']); ?>
        </div>
    <?php endif; ?>
    
    <?php if (isset($_GET['error'])): ?>
        <div class="error-msg">
            <?php echo htmlspecialchars($_GET['error']); ?>
        </div>
    <?php endif; ?>
    
    <!-- Account Information -->
    <div class="account-info-card">
        <h2>Account Information</h2>
        
        <div class="info-row">
            <div class="info-label">Username:</div>
            <div class="info-value"><?php echo htmlspecialchars($member['username']); ?></div>
        </div>
        
        <div class="info-row">
            <div class="info-label">Email:</div>
            <div class="info-value"><?php echo htmlspecialchars($member['email']); ?></div>
        </div>
        
        <div class="info-row" style="border-bottom: none;">
            <div class="info-label">Member Since:</div>
            <div class="info-value"><?php echo date('F j, Y', strtotime($member['date_registered'])); ?></div>
        </div>
        
        <div class="action-buttons">
            <button class="btn btn-change-password" onclick="showChangePassword()">Change Password</button>
            <button class="btn btn-delete" onclick="showDeleteConfirm()">Delete Account</button>
        </div>
    </div>
    
    <!-- Services Table -->
    <div class="services-table">
        <h2>My Services</h2>
        
        <?php if ($memberships_result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Type of Service</th>
                        <th>Purchased Date</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($membership = $memberships_result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($membership['membership_type']); ?></td>
                            <td><?php echo date('M j, Y', strtotime($membership['purchase_date'])); ?></td>
                            <td><?php echo date('M j, Y', strtotime($membership['start_date'])); ?></td>
                            <td><?php echo date('M j, Y', strtotime($membership['end_date'])); ?></td>
                            <td>
                                <?php
                                $status = $membership['display_status'];
                                $status_class = '';
                                if ($status === 'Pending') $status_class = 'status-pending';
                                elseif ($status === 'Active') $status_class = 'status-active';
                                else $status_class = 'status-expired';
                                ?>
                                <span class="status-badge <?php echo $status_class; ?>"><?php echo htmlspecialchars($status); ?></span>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p style="color: #aaa;">No services found. You haven't purchased any memberships yet.</p>
        <?php endif; ?>
    </div>
</div>

<!-- Change Password Modal -->
<div id="changePasswordModal" class="modal">
    <div class="modal-content">
        <h3>Change Password</h3>
        <form action="member_change_password.php" method="POST">
            <label style="display: block; margin-bottom: 8px;">Current Password:</label>
            <input type="password" name="current_password" required style="width: 100%; padding: 10px; margin-bottom: 15px; border-radius: 6px; border: none; background: #3c3c3c; color: white;">
            
            <label style="display: block; margin-bottom: 8px;">New Password:</label>
            <input type="password" name="new_password" required style="width: 100%; padding: 10px; margin-bottom: 15px; border-radius: 6px; border: none; background: #3c3c3c; color: white;">
            
            <label style="display: block; margin-bottom: 8px;">Confirm New Password:</label>
            <input type="password" name="confirm_password" required style="width: 100%; padding: 10px; margin-bottom: 15px; border-radius: 6px; border: none; background: #3c3c3c; color: white;">
            
            <div class="modal-buttons">
                <button type="button" class="btn btn-cancel" onclick="closeChangePassword()">Cancel</button>
                <button type="submit" class="btn btn-change-password">Change Password</button>
            </div>
        </form>
    </div>
</div>

<!-- Delete Account Confirmation Modal -->
<div id="deleteAccountModal" class="modal">
    <div class="modal-content">
        <h3>Delete Account</h3>
        <p>Are you sure you want to delete your account? This action will archive your account and you won't be able to log in anymore.</p>
        <p style="color: #e74c3c; font-weight: 600;">This action cannot be undone!</p>
        <form action="member_delete_account.php" method="POST">
            <div class="modal-buttons">
                <button type="button" class="btn btn-cancel" onclick="closeDeleteConfirm()">Cancel</button>
                <button type="submit" class="btn btn-confirm">Yes, Delete My Account</button>
            </div>
        </form>
    </div>
</div>

<script>
function showChangePassword() {
    document.getElementById('changePasswordModal').style.display = 'block';
}

function closeChangePassword() {
    document.getElementById('changePasswordModal').style.display = 'none';
}

function showDeleteConfirm() {
    document.getElementById('deleteAccountModal').style.display = 'block';
}

function closeDeleteConfirm() {
    document.getElementById('deleteAccountModal').style.display = 'none';
}

// Close modals when clicking outside
window.onclick = function(event) {
    const changeModal = document.getElementById('changePasswordModal');
    const deleteModal = document.getElementById('deleteAccountModal');
    if (event.target == changeModal) {
        changeModal.style.display = 'none';
    }
    if (event.target == deleteModal) {
        deleteModal.style.display = 'none';
    }
}
</script>

</body>
</html>

