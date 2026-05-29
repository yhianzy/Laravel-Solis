<?php 
require_once '../config/database.php';
require_once '../config/session_helper.php';
include 'includes/header.php'; 
include 'includes/sidebar.php'; 

$conn = getDBConnection();

// Fetch payments with member information
$payments_query = "
    SELECT 
        p.payment_id,
        p.member_id,
        p.membership_id,
        p.amount,
        p.payment_type,
        p.reference_number,
        p.receipt_image,
        p.payment_date,
        p.status,
        m.username,
        m.email
    FROM payments p
    LEFT JOIN members m ON p.member_id = m.member_id
    ORDER BY p.payment_date DESC
";
$payments_result = $conn->query($payments_query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payments</title>
    <link rel="stylesheet" href="assets/css/admin.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.5/css/dataTables.dataTables.css" />
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/2.3.5/js/dataTables.js"></script>
</head>
<body>

<div class="main-content">

    <div class="section-header">
        <h2>PAYMENTS</h2>
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
        <table id="paymentsTable" class="accounts-table">
            <thead>
                <tr>
                    <th>Payment ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Service Type</th>
                    <th>Amount</th>
                    <th>Date of Payment</th>
                    <th>View Receipt</th>
                    <th>Status</th>
                </tr>
            </thead>

            <tbody>
                <?php 
                if ($payments_result && $payments_result->num_rows > 0) {
                    while ($payment = $payments_result->fetch_assoc()) {
                        $payment_id = $payment['payment_id'];
                        $username = htmlspecialchars($payment['username'] ?? 'N/A');
                        $email = htmlspecialchars($payment['email'] ?? 'N/A');
                        $service_type = htmlspecialchars($payment['payment_type']);
                        $amount = number_format($payment['amount'], 2);
                        $payment_date_display = date('M j, Y', strtotime($payment['payment_date']));
                        $payment_date_sort = date('Y-m-d', strtotime($payment['payment_date'])); // ISO format for sorting
                        $status = htmlspecialchars($payment['status']);
                        
                        $status_class = '';
                        $status_display = '';
                        if ($status === 'Approved') {
                            $status_class = 'verified';
                            $status_display = 'Approved';
                        } elseif ($status === 'Rejected') {
                            $status_class = 'rejected';
                            $status_display = 'Rejected';
                        } else {
                            $status_class = 'pending';
                            $status_display = 'Pending';
                        }
                        
                        echo "<tr>";
                        echo "<td>PAY#" . str_pad($payment_id, 4, '0', STR_PAD_LEFT) . "</td>";
                        echo "<td>{$username}</td>";
                        echo "<td>{$email}</td>";
                        echo "<td>{$service_type}</td>";
                        echo "<td>₱{$amount}</td>";
                        echo "<td data-sort=\"" . htmlspecialchars($payment_date_sort) . "\">{$payment_date_display}</td>";
                        echo "<td>";
                        if ($payment['receipt_image'] && file_exists('../' . $payment['receipt_image'])) {
                            echo "<button class='edit-btn' onclick=\"window.open('view_receipt.php?id={$payment_id}', 'receipt', 'width=900,height=700')\">View</button>";
                        } else {
                            echo "<span style='color: #aaa;'>N/A</span>";
                        }
                        echo "</td>";
                        echo "<td>";
                        if ($status === 'Pending') {
                            echo "<form method='POST' action='verify_payment.php' style='display:inline;'>";
                            echo "<input type='hidden' name='payment_id' value='{$payment_id}'>";
                            echo "<input type='hidden' name='action' value='approve'>";
                            echo "<button type='submit' class='status-btn verified' style='margin-right: 5px;'>Verify</button>";
                            echo "</form>";
                            echo "<form method='POST' action='verify_payment.php' style='display:inline;'>";
                            echo "<input type='hidden' name='payment_id' value='{$payment_id}'>";
                            echo "<input type='hidden' name='action' value='reject'>";
                            echo "<button type='submit' class='status-btn rejected' onclick=\"return confirm('Are you sure you want to reject this payment?')\">Reject</button>";
                            echo "</form>";
                        } else {
                            echo "<span class='status-btn {$status_class}'>{$status_display}</span>";
                        }
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='8' style='text-align: center;'>No payments found</td></tr>";
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

.edit-btn {
    padding: 5px 15px;
    background: white;
    color: black;
    border: none;
    border-radius: 20px;
    cursor: pointer;
}

.status-btn {
    padding: 5px 15px;
    border-radius: 20px;
    cursor: pointer;
    border: none;
    font-size: 14px;
}

.status-btn.verified {
    background: #2ecc71;
    color: black;
}

.status-btn.pending {
    background: #f39c12;
    color: black;
}

.status-btn.rejected {
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
    var table = $('#paymentsTable').DataTable({
        "order": [[5, "asc"]], // Sort by "Date of Payment" column (index 5) - use asc (inverted) for newest first
        "pageLength": 10,
        "columnDefs": [
            { "type": "date", "targets": 5 } // Column 5 is date column
        ],
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
        // The sorting is reversed, so invert the logic
        // When user selects "desc" (Newest First), we actually need "asc" 
        // When user selects "asc" (Oldest First), we actually need "desc"
        var actualOrder = (selectedValue === 'desc') ? 'asc' : 'desc';
        table.order([5, actualOrder]).draw(); // Sort by "Date of Payment" column (index 5)
    });
});
</script>

</body>
</html>
