<?php 
require_once '../config/database.php';
require_once '../config/session_helper.php';
include 'includes/header.php'; 
include 'includes/sidebar.php';

// Set timezone to Philippines
date_default_timezone_set('Asia/Manila'); 

$conn = getDBConnection();

// Check if new columns exist, if not use fallback query
$check_columns = $conn->query("SHOW COLUMNS FROM activity_logs LIKE 'user_type'");
$has_new_columns = $check_columns->num_rows > 0;

if ($has_new_columns) {
    // Fetch activity logs with new columns
    $logs_query = "
        SELECT 
            log_id,
            COALESCE(user_id, 0) as user_id,
            COALESCE(user_type, 'N/A') as user_type,
            COALESCE(username, 'N/A') as username,
            COALESCE(email, 'N/A') as email,
            action,
            COALESCE(DATE(log_date), CURDATE()) as log_date,
            login_time,
            logout_time
        FROM activity_logs
        WHERE action IN ('Login', 'Logout')
        ORDER BY log_date DESC, login_time DESC, logout_time DESC
    ";
} else {
    // Fallback query for old table structure
    $logs_query = "
        SELECT 
            log_id,
            COALESCE(user_id, 0) as user_id,
            'N/A' as user_type,
            'N/A' as username,
            'N/A' as email,
            action,
            COALESCE(DATE(log_date), CURDATE()) as log_date,
            NULL as login_time,
            NULL as logout_time
        FROM activity_logs
        WHERE action IN ('Login', 'Logout')
        ORDER BY log_date DESC
    ";
}
$logs_result = $conn->query($logs_query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log Activity</title>
    <link rel="stylesheet" href="assets/css/admin.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.5/css/dataTables.dataTables.css" />
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/2.3.5/js/dataTables.js"></script>
</head>
<body>

<div class="main-content">

    <div class="section-header">
        <h2>LOG ACTIVITY</h2>
    </div>

    <div class="table-controls" style="margin-top: 20px; margin-bottom: 15px; display: flex; gap: 15px; align-items: center;">
        <label for="sortOrder" style="color: white; font-size: 14px;">Sort by Date:</label>
        <select id="sortOrder" style="padding: 8px 15px; border-radius: 6px; border: none; background: white; color: black; cursor: pointer; font-size: 14px;">
            <option value="desc">Newest First</option>
            <option value="asc">Oldest First</option>
        </select>
    </div>

    <div class="table-container">
        <table id="logsTable" class="accounts-table">
            <thead>
                <tr>
                    <th>User ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>User Type</th>
                    <th>Date</th>
                    <th>Login Time</th>
                    <th>Logout Time</th>
                </tr>
            </thead>

            <tbody>
                <?php 
                if ($logs_result && $logs_result->num_rows > 0) {
                    // Show all log entries individually (not grouped)
                    while ($log = $logs_result->fetch_assoc()) {
                        $user_id = isset($log['user_id']) ? (int)$log['user_id'] : 0;
                        $username = isset($log['username']) && $log['username'] !== null && $log['username'] !== 'N/A' ? htmlspecialchars($log['username']) : 'N/A';
                        $email = isset($log['email']) && $log['email'] !== null && $log['email'] !== 'N/A' ? htmlspecialchars($log['email']) : 'N/A';
                        $user_type_raw = isset($log['user_type']) ? strtolower($log['user_type']) : '';
                        $user_type = ($user_type_raw === 'member') ? 'Member' : (($user_type_raw === 'admin' || $user_type_raw === 'staff') ? 'Admin' : 'N/A');
                        
                        // Format date (convert to Philippines timezone)
                        $date = 'N/A';
                        $date_sort = ''; // For DataTables sorting
                        if (!empty($log['log_date']) && $log['log_date'] !== null) {
                            try {
                                $dt = new DateTime($log['log_date'], new DateTimeZone('UTC'));
                                $dt->setTimezone(new DateTimeZone('Asia/Manila'));
                                $date = $dt->format('M j, Y');
                                $date_sort = $dt->format('Y-m-d'); // ISO format for proper sorting
                            } catch (Exception $e) {
                                $date = date('M j, Y', strtotime($log['log_date']));
                                $date_sort = date('Y-m-d', strtotime($log['log_date']));
                            }
                        }
                        
                        // Format login time (already in Philippines timezone)
                        $login_time = 'N/A';
                        if (!empty($log['login_time']) && $log['login_time'] !== null) {
                            try {
                                // login_time is stored as DATETIME, format it for display
                                $dt = new DateTime($log['login_time'], new DateTimeZone('Asia/Manila'));
                                $login_time = $dt->format('g:i A');
                            } catch (Exception $e) {
                                // Fallback - try to parse as-is
                                try {
                                    $dt = new DateTime($log['login_time']);
                                    $dt->setTimezone(new DateTimeZone('Asia/Manila'));
                                    $login_time = $dt->format('g:i A');
                                } catch (Exception $e2) {
                                    // Last resort
                                    $login_time = date('g:i A', strtotime($log['login_time']));
                                }
                            }
                        }
                        
                        // Format logout time (already in Philippines timezone)
                        $logout_time = 'N/A';
                        if (!empty($log['logout_time']) && $log['logout_time'] !== null) {
                            try {
                                // logout_time is stored as DATETIME, format it for display
                                $dt = new DateTime($log['logout_time'], new DateTimeZone('Asia/Manila'));
                                $logout_time = $dt->format('g:i A');
                            } catch (Exception $e) {
                                // Fallback - try to parse as-is
                                try {
                                    $dt = new DateTime($log['logout_time']);
                                    $dt->setTimezone(new DateTimeZone('Asia/Manila'));
                                    $logout_time = $dt->format('g:i A');
                                } catch (Exception $e2) {
                                    // Last resort
                                    $logout_time = date('g:i A', strtotime($log['logout_time']));
                                }
                            }
                        }
                        
                        // Format user ID based on type
                        $user_id_display = (strtolower($user_type_raw) === 'member') ? 
                            'MEM#' . str_pad($user_id, 4, '0', STR_PAD_LEFT) : 
                            'ADM#' . str_pad($user_id, 4, '0', STR_PAD_LEFT);
                        
                        // Output all 7 columns
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($user_id_display) . "</td>";
                        echo "<td>" . $username . "</td>";
                        echo "<td>" . $email . "</td>";
                        echo "<td>" . htmlspecialchars($user_type) . "</td>";
                        echo "<td data-sort=\"" . htmlspecialchars($date_sort) . "\">" . htmlspecialchars($date) . "</td>";
                        echo "<td>" . htmlspecialchars($login_time) . "</td>";
                        echo "<td>" . htmlspecialchars($logout_time) . "</td>";
                        echo "</tr>";
                    }
                } else {
                    // Output empty row with all 7 columns
                    echo "<tr>";
                    echo "<td colspan='7' style='text-align: center;'>No activity logs found</td>";
                    echo "</tr>";
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
    var table = $('#logsTable').DataTable({
        "order": [[4, "desc"]], // Sort by "Date" column (index 4) descending by default (newest first)
        "pageLength": 10,
        "autoWidth": false,
        "columns": [
            null, // User ID (column 0)
            null, // Username (column 1)
            null, // Email (column 2)
            null, // User Type (column 3)
            null, // Date (column 4)
            null, // Login Time (column 5)
            null  // Logout Time (column 6)
        ],
        "language": {
            "search": "Search:",
            "lengthMenu": "Show _MENU_ entries",
            "info": "Showing _START_ to _END_ of _TOTAL_ entries",
            "infoEmpty": "Showing 0 to 0 of 0 entries",
            "infoFiltered": "(filtered from _TOTAL_ total entries)",
            "emptyTable": "No activity logs found",
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
        table.order([4, order]).draw(); // Sort by "Date" column (index 4)
    });
});
</script>

</body>
</html>
