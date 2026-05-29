<?php 
require_once '../config/database.php';
require_once '../config/session_helper.php';
include 'includes/header.php'; 
include 'includes/sidebar.php'; 

$conn = getDBConnection();

// Fetch accounts from accounts table
$accounts_query = "SELECT user_id, username, email, type, status, account_created FROM accounts ORDER BY account_created DESC";
$accounts_result = $conn->query($accounts_query);

// Fetch members from members table
$members_query = "SELECT member_id, username, email, date_registered FROM members ORDER BY date_registered DESC";
$members_result = $conn->query($members_query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accounts</title>
    <link rel="stylesheet" href="assets/css/admin.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.5/css/dataTables.dataTables.css" />
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/2.3.5/js/dataTables.js"></script>
</head>
<body>

<div class="main-content">

    <div class="section-header">
        <h2>ACCOUNTS</h2>
        <button class="add-btn" onclick="window.location.href='add_account.php'">Add</button>
    </div>

    <?php if (isset($_GET['success'])): ?>
        <div style="color: #2ecc71; margin-bottom: 15px; padding: 10px; background: #2c2c2c; border-radius: 6px; max-width: 600px;">
            <?php echo htmlspecialchars($_GET['success']); ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['error'])): ?>
        <div style="color: #e74c3c; margin-bottom: 15px; padding: 10px; background: #2c2c2c; border-radius: 6px; max-width: 600px;">
            <?php echo htmlspecialchars($_GET['error']); ?>
        </div>
    <?php endif; ?>

    <div class="table-controls" style="margin-top: 20px; margin-bottom: 15px; display: flex; gap: 15px; align-items: center;">
        <label for="sortOrder" style="color: white; font-size: 14px;">Sort by Date:</label>
        <select id="sortOrder" style="padding: 8px 15px; border-radius: 6px; border: none; background: white; color: black; cursor: pointer; font-size: 14px;">
            <option value="desc">Newest First</option>
            <option value="asc">Oldest First</option>
        </select>
    </div>

    <div class="table-container">
        <table id="accountsTable" class="accounts-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Type</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Account Created</th>
                    <th>Status</th>
                </tr>
            </thead>

            <tbody>
                <?php 
                // Display accounts from accounts table
                if ($accounts_result && $accounts_result->num_rows > 0) {
                    while ($account = $accounts_result->fetch_assoc()) {
                        $account_id = $account['user_id'];
                        $account_type = htmlspecialchars($account['type']);
                        $username = htmlspecialchars($account['username']);
                        $email = htmlspecialchars($account['email']);
                        $status = htmlspecialchars($account['status']);
                        $created = date('m/d/Y', strtotime($account['account_created']));
                        
                        $status_class = ($status === 'Working') ? 'working' : 'archived';
                        $status_display = ($status === 'Working') ? 'Working' : 'Archived';
                        
                        echo "<tr>";
                        echo "<td>ACC#" . str_pad($account_id, 4, '0', STR_PAD_LEFT) . "</td>";
                        echo "<td>{$account_type}</td>";
                        echo "<td>{$username}</td>";
                        echo "<td>{$email}</td>";
                        echo "<td>{$created}</td>";
                        echo "<td>";
                        echo "<form method='POST' action='toggle_account_status.php' style='display:inline;'>";
                        echo "<input type='hidden' name='account_type' value='account'>";
                        echo "<input type='hidden' name='id' value='{$account_id}'>";
                        echo "<input type='hidden' name='current_status' value='{$status}'>";
                        echo "<button type='submit' class='status-btn {$status_class}'>{$status_display}</button>";
                        echo "</form>";
                        echo "</td>";
                        echo "</tr>";
                    }
                }
                
                // Display members from members table
                if ($members_result && $members_result->num_rows > 0) {
                    while ($member = $members_result->fetch_assoc()) {
                        $member_id = $member['member_id'];
                        $username = htmlspecialchars($member['username']);
                        $email = htmlspecialchars($member['email']);
                        $created = date('m/d/Y', strtotime($member['date_registered']));
                        
                        echo "<tr>";
                        echo "<td>MEM#" . str_pad($member_id, 4, '0', STR_PAD_LEFT) . "</td>";
                        echo "<td>Member</td>";
                        echo "<td>{$username}</td>";
                        echo "<td>{$email}</td>";
                        echo "<td>{$created}</td>";
                        echo "<td>";
                        echo "<span class='status-btn working'>Working</span>";
                        echo "</td>";
                        echo "</tr>";
                    }
                }
                
                $accounts_count = ($accounts_result && $accounts_result->num_rows) ? $accounts_result->num_rows : 0;
                $members_count = ($members_result && $members_result->num_rows) ? $members_result->num_rows : 0;
                
                if ($accounts_count == 0 && $members_count == 0) {
                    echo "<tr><td colspan='6' style='text-align: center;'>No accounts found</td></tr>";
                }
                
                closeDBConnection($conn);
                ?>
            </tbody>

        </table>
    </div>

</div>

<style>
/* Small UI tweaks */
.main-content {
    margin-left: 230px;
    padding: 25px;
    color: white;
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.add-btn {
    padding: 10px 25px;
    background: white;
    color: black;
    border-radius: 20px;
    border: none;
    cursor: pointer;
    font-size: 16px;
}

.add-btn:hover {
    background: #e3e3e3;
}

.table-container {
    margin-top: 20px;
}

.accounts-table {
    width: 100%;
    border-collapse: collapse;
}

.accounts-table th,
.accounts-table td {
    border: 1px solid #777;
    padding: 15px;
    text-align: center;
    background: #3c3c3c;
}

.accounts-table th {
    background: #1f1f1f;
}

.status-btn {
    padding: 5px 15px;
    border-radius: 20px;
    cursor: pointer;
    border: none;
    font-size: 14px;
}

.status-btn.working {
    background: #2ecc71;
    color: black;
}

.status-btn.archived {
    background: #e74c3c;
    color: white;
}

/* DataTables Custom Styling */
.dataTables_wrapper {
    color: white;
}

.dataTables_wrapper .dataTables_filter input {
    background: #3c3c3c;
    border: 1px solid #777;
    color: white;
    padding: 8px;
    border-radius: 6px;
    margin-left: 10px;
}

.dataTables_wrapper .dataTables_filter input:focus {
    outline: none;
    border-color: white;
}

.dataTables_wrapper .dataTables_filter label {
    color: white;
    font-size: 14px;
}

.dataTables_wrapper .dataTables_length select {
    background: #3c3c3c;
    border: 1px solid #777;
    color: white;
    padding: 6px;
    border-radius: 6px;
}

.dataTables_wrapper .dataTables_length label {
    color: white;
    font-size: 14px;
}

.dataTables_wrapper .dataTables_info {
    color: white;
}

.dataTables_wrapper .dataTables_paginate .paginate_button {
    color: white !important;
    background: #3c3c3c !important;
    border: 1px solid #777 !important;
}

.dataTables_wrapper .dataTables_paginate .paginate_button:hover {
    background: #4c4c4c !important;
}

.dataTables_wrapper .dataTables_paginate .paginate_button.current {
    background: white !important;
    color: black !important;
}

.dataTables_wrapper .dataTables_paginate .paginate_button.disabled {
    opacity: 0.5;
    cursor: not-allowed;
}
</style>

<script>
$(document).ready(function() {
    var table = $('#accountsTable').DataTable({
        "order": [[4, "desc"]], // Sort by "Account Created" column (index 4) descending by default
        "pageLength": 10,
        "language": {
            "search": "Search:",
            "lengthMenu": "Show _MENU_ entries",
            "info": "Showing _START_ to _END_ of _TOTAL_ entries",
            "infoEmpty": "Showing 0 to 0 of 0 entries",
            "infoFiltered": "(filtered from _TOTAL_ total entries)",
            "paginate": {
                "first": "First",
                "last": "Last",
                "next": "Next",
                "previous": "Previous"
            }
        }
    });

    // Sort order change handler
    $('#sortOrder').on('change', function() {
        var order = $(this).val();
        table.order([4, order]).draw(); // Sort by "Account Created" column (index 4)
    });
});
</script>

</body>
</html>
