<?php 
require_once '../config/database.php';
require_once '../config/session_helper.php';
include 'includes/header.php'; 
include 'includes/sidebar.php'; 

$conn = getDBConnection();

// Fetch all memberships (show all records, not grouped)
$memberships_query = "
    SELECT 
        mems.membership_id,
        m.member_id,
        m.username,
        m.email,
        mems.membership_type,
        mems.start_date as membership_start,
        mems.end_date as membership_end,
        mems.status
    FROM memberships mems
    INNER JOIN members m ON mems.member_id = m.member_id
";
$memberships_result = $conn->query($memberships_query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Memberships</title>
    <link rel="stylesheet" href="assets/css/admin.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.5/css/dataTables.dataTables.css" />
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/2.3.5/js/dataTables.js"></script>
</head>
<body>

<div class="main-content">

    <div class="section-header">
        <h2>MEMBERSHIPS</h2>
        <!-- <button class="add-btn" id="toggleMembershipCalendar">View Calendar</button> -->
    </div>

    <!-- Calendar panel (hidden by default) -->
    <div id="membershipCalendarPanel" style="display:none; margin-top: 15px; background:#3f3f3f; padding:20px; border-radius:10px;">
        <h3 style="margin-top:0; margin-bottom:10px;">Membership Schedule</h3>
        <label for="membershipDate" style="display:block; margin-bottom:8px;">Select a date:</label>
        <input type="date" id="membershipDate" style="padding:8px; border-radius:6px; border:none;">
        <p style="margin-top:12px; font-size:14px; color:#ddd;">Use the date picker to view or filter memberships for a specific day.</p>
    </div>

    <div class="table-controls" style="margin-top: 20px; margin-bottom: 15px; display: flex; gap: 15px; align-items: center;">
        <label for="sortOrder" style="color: white; font-size: 14px;">Sort by Date:</label>
        <select id="sortOrder" style="padding: 8px 15px; border-radius: 6px; border: none; background: white; color: black; cursor: pointer; font-size: 14px;">
            <option value="desc">Newest First</option>
            <option value="asc">Oldest First</option>
        </select>
    </div>

    <div class="table-container">
        <table id="membershipsTable" class="accounts-table">
            <thead>
                <tr>
                    <th>Membership ID</th>
                    <th>Member ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Service Type</th>
                    <th>Membership Start</th>
                    <th>Membership End</th>
                    <th>Status</th>
                </tr>
            </thead>

            <tbody>
                <?php 
                if ($memberships_result && $memberships_result->num_rows > 0) {
                    while ($membership = $memberships_result->fetch_assoc()) {
                        $membership_id = $membership['membership_id'];
                        $member_id = $membership['member_id'];
                        $username = htmlspecialchars($membership['username']);
                        $email = htmlspecialchars($membership['email']);
                        $service_type = htmlspecialchars($membership['membership_type']);
                        $start_date_display = date('M j, Y', strtotime($membership['membership_start']));
                        $start_date_sort = date('Y-m-d', strtotime($membership['membership_start'])); // ISO format for sorting
                        $end_date_display = date('M j, Y', strtotime($membership['membership_end']));
                        $status = htmlspecialchars($membership['status']);
                        
                        $status_class = '';
                        if ($status === 'Active') $status_class = 'status-active';
                        elseif ($status === 'Pending') $status_class = 'status-pending';
                        else $status_class = 'status-expired';
                        
                        echo "<tr>";
                        echo "<td>MEM#" . str_pad($membership_id, 4, '0', STR_PAD_LEFT) . "</td>";
                        echo "<td>MEM#" . str_pad($member_id, 4, '0', STR_PAD_LEFT) . "</td>";
                        echo "<td>{$username}</td>";
                        echo "<td>{$email}</td>";
                        echo "<td>{$service_type}</td>";
                        echo "<td data-sort=\"" . htmlspecialchars($start_date_sort) . "\">{$start_date_display}</td>";
                        echo "<td>{$end_date_display}</td>";
                        echo "<td><span class='status-badge {$status_class}'>{$status}</span></td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='8' style='text-align: center;'>No memberships found. Members will appear here once they purchase a service.</td></tr>";
                }
                
                closeDBConnection($conn);
                ?>
            </tbody>

        </table>
    </div>

</div>

<style>
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

.status-badge {
    padding: 4px 12px;
    border-radius: 12px;
    font-size: 13px;
    font-weight: 500;
    display: inline-block;
}

.status-badge.status-active {
    background: #2ecc71;
    color: white;
}

.status-badge.status-pending {
    background: #f39c12;
    color: white;
}

.status-badge.status-expired {
    background: #95a5a6;
    color: white;
}
</style>

<script>
// Toggle Membership Calendar Panel
document.addEventListener('DOMContentLoaded', function() {
    const toggleBtn = document.getElementById('toggleMembershipCalendar');
    const calendarPanel = document.getElementById('membershipCalendarPanel');

    if (toggleBtn && calendarPanel) {
        toggleBtn.addEventListener('click', function() {
            if (calendarPanel.style.display === 'none' || calendarPanel.style.display === '') {
                calendarPanel.style.display = 'block';
                toggleBtn.textContent = 'Hide Calendar';
            } else {
                calendarPanel.style.display = 'none';
                toggleBtn.textContent = 'View Calendar';
            }
        });
    }
});

// DataTables initialization
$(document).ready(function() {
    var table = $('#membershipsTable').DataTable({
        "order": [[5, "asc"]], // Sort by "Membership start" column (index 5) - use asc (inverted) for newest first
        "pageLength": 10,
        "columnDefs": [
            { 
                "type": "date",
                "targets": 5 // Column 5 is date column
            }
        ],
        "orderCellsTop": true,
        "stateSave": false,
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
        var selectedValue = $(this).val();
        // The sorting is reversed, so invert the logic (same as payments table)
        // When user selects "desc" (Newest First), we actually need "asc" 
        // When user selects "asc" (Oldest First), we actually need "desc"
        var actualOrder = (selectedValue === 'desc') ? 'asc' : 'desc';
        table.order([5, actualOrder]).draw(); // Sort by "Membership start" column (index 5)
    });
});
</script>

</body>
</html>
