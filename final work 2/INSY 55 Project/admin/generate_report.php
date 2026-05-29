<?php
require_once '../config/database.php';
require_once '../config/session_helper.php';

// Check if user is logged in as admin
if (!isLoggedIn() || getUserType() !== 'admin') {
    header("Location: ../login.php");
    exit;
}

$tab = $_GET['tab'] ?? 'general';
$conn = getDBConnection();

// Get data based on selected tab
$report_data = [];
$report_title = '';

switch ($tab) {
    case 'general':
        $report_title = 'General Report';
        $report_data = [
            'total_accounts' => $conn->query("SELECT COUNT(*) as count FROM members")->fetch_assoc()['count'],
            'revenue_today' => $conn->query("SELECT COALESCE(SUM(amount), 0) as total FROM payments WHERE status = 'Approved' AND DATE(payment_date) = CURDATE()")->fetch_assoc()['total'],
            'revenue_all' => $conn->query("SELECT COALESCE(SUM(amount), 0) as total FROM payments WHERE status = 'Approved'")->fetch_assoc()['total'],
            'monthly_members' => $conn->query("SELECT COUNT(DISTINCT member_id) as count FROM memberships WHERE membership_type = 'Monthly' AND status = 'Active'")->fetch_assoc()['count'],
            'boxing_members' => $conn->query("SELECT COUNT(DISTINCT member_id) as count FROM memberships WHERE membership_type = 'Boxing' AND status = 'Active'")->fetch_assoc()['count'],
            'dancing_members' => $conn->query("SELECT COUNT(DISTINCT member_id) as count FROM memberships WHERE membership_type = 'Dancing' AND status = 'Active'")->fetch_assoc()['count'],
            'day_members' => $conn->query("SELECT COUNT(DISTINCT member_id) as count FROM memberships WHERE membership_type = 'Day Session' AND status = 'Active'")->fetch_assoc()['count']
        ];
        break;
        
    case 'monthly':
        $report_title = 'Monthly Members Report';
        $report_data = [
            'total_members' => $conn->query("SELECT COUNT(DISTINCT member_id) as count FROM memberships WHERE membership_type = 'Monthly' AND status = 'Active'")->fetch_assoc()['count'],
            'total_revenue' => $conn->query("SELECT COALESCE(SUM(amount), 0) as total FROM payments WHERE status = 'Approved' AND payment_type = 'Monthly'")->fetch_assoc()['total'],
            'revenue_today' => $conn->query("SELECT COALESCE(SUM(amount), 0) as total FROM payments WHERE status = 'Approved' AND payment_type = 'Monthly' AND DATE(payment_date) = CURDATE()")->fetch_assoc()['total'],
            'revenue_weekly' => $conn->query("SELECT COALESCE(SUM(amount), 0) as total FROM payments WHERE status = 'Approved' AND payment_type = 'Monthly' AND payment_date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)")->fetch_assoc()['total'],
            'revenue_monthly' => $conn->query("SELECT COALESCE(SUM(amount), 0) as total FROM payments WHERE status = 'Approved' AND payment_type = 'Monthly' AND payment_date >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)")->fetch_assoc()['total'],
            'revenue_yearly' => $conn->query("SELECT COALESCE(SUM(amount), 0) as total FROM payments WHERE status = 'Approved' AND payment_type = 'Monthly' AND YEAR(payment_date) = YEAR(CURDATE())")->fetch_assoc()['total']
        ];
        break;
        
    case 'day':
        $report_title = 'Day Session Report';
        $report_data = [
            'total_members' => $conn->query("SELECT COUNT(DISTINCT member_id) as count FROM memberships WHERE membership_type = 'Day Session' AND status = 'Active'")->fetch_assoc()['count'],
            'total_revenue' => $conn->query("SELECT COALESCE(SUM(amount), 0) as total FROM payments WHERE status = 'Approved' AND payment_type = 'Day Session'")->fetch_assoc()['total'],
            'revenue_today' => $conn->query("SELECT COALESCE(SUM(amount), 0) as total FROM payments WHERE status = 'Approved' AND payment_type = 'Day Session' AND DATE(payment_date) = CURDATE()")->fetch_assoc()['total'],
            'revenue_weekly' => $conn->query("SELECT COALESCE(SUM(amount), 0) as total FROM payments WHERE status = 'Approved' AND payment_type = 'Day Session' AND payment_date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)")->fetch_assoc()['total'],
            'revenue_monthly' => $conn->query("SELECT COALESCE(SUM(amount), 0) as total FROM payments WHERE status = 'Approved' AND payment_type = 'Day Session' AND payment_date >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)")->fetch_assoc()['total'],
            'revenue_yearly' => $conn->query("SELECT COALESCE(SUM(amount), 0) as total FROM payments WHERE status = 'Approved' AND payment_type = 'Day Session' AND YEAR(payment_date) = YEAR(CURDATE())")->fetch_assoc()['total']
        ];
        break;
        
    case 'boxing':
        $report_title = 'Boxing Session Report';
        $report_data = [
            'total_members' => $conn->query("SELECT COUNT(DISTINCT member_id) as count FROM memberships WHERE membership_type = 'Boxing' AND status = 'Active'")->fetch_assoc()['count'],
            'total_revenue' => $conn->query("SELECT COALESCE(SUM(amount), 0) as total FROM payments WHERE status = 'Approved' AND payment_type = 'Boxing'")->fetch_assoc()['total'],
            'revenue_today' => $conn->query("SELECT COALESCE(SUM(amount), 0) as total FROM payments WHERE status = 'Approved' AND payment_type = 'Boxing' AND DATE(payment_date) = CURDATE()")->fetch_assoc()['total'],
            'revenue_weekly' => $conn->query("SELECT COALESCE(SUM(amount), 0) as total FROM payments WHERE status = 'Approved' AND payment_type = 'Boxing' AND payment_date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)")->fetch_assoc()['total'],
            'revenue_monthly' => $conn->query("SELECT COALESCE(SUM(amount), 0) as total FROM payments WHERE status = 'Approved' AND payment_type = 'Boxing' AND payment_date >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)")->fetch_assoc()['total'],
            'revenue_yearly' => $conn->query("SELECT COALESCE(SUM(amount), 0) as total FROM payments WHERE status = 'Approved' AND payment_type = 'Boxing' AND YEAR(payment_date) = YEAR(CURDATE())")->fetch_assoc()['total']
        ];
        break;
        
    case 'dancing':
        $report_title = 'Dancing Session Report';
        $report_data = [
            'total_members' => $conn->query("SELECT COUNT(DISTINCT member_id) as count FROM memberships WHERE membership_type = 'Dancing' AND status = 'Active'")->fetch_assoc()['count'],
            'total_revenue' => $conn->query("SELECT COALESCE(SUM(amount), 0) as total FROM payments WHERE status = 'Approved' AND payment_type = 'Dancing'")->fetch_assoc()['total'],
            'revenue_today' => $conn->query("SELECT COALESCE(SUM(amount), 0) as total FROM payments WHERE status = 'Approved' AND payment_type = 'Dancing' AND DATE(payment_date) = CURDATE()")->fetch_assoc()['total'],
            'revenue_weekly' => $conn->query("SELECT COALESCE(SUM(amount), 0) as total FROM payments WHERE status = 'Approved' AND payment_type = 'Dancing' AND payment_date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)")->fetch_assoc()['total'],
            'revenue_monthly' => $conn->query("SELECT COALESCE(SUM(amount), 0) as total FROM payments WHERE status = 'Approved' AND payment_type = 'Dancing' AND payment_date >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)")->fetch_assoc()['total'],
            'revenue_yearly' => $conn->query("SELECT COALESCE(SUM(amount), 0) as total FROM payments WHERE status = 'Approved' AND payment_type = 'Dancing' AND YEAR(payment_date) = YEAR(CURDATE())")->fetch_assoc()['total']
        ];
        break;
        
    default:
        $report_title = 'General Report';
        break;
}

closeDBConnection($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($report_title); ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            color: #333;
        }
        .report-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .report-header h1 {
            margin: 0;
            color: #33a852;
        }
        .report-date {
            color: #666;
            font-size: 14px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table th, table td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        table th {
            background-color: #33a852;
            color: white;
        }
        table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .stat-row {
            font-weight: bold;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            color: #666;
            font-size: 12px;
        }
        @media print {
            body { margin: 0; }
            .no-print { display: none; }
        }
        .print-btn {
            background: #33a852;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<div class="no-print">
    <button class="print-btn" onclick="window.print()">Print Report</button>
    <button class="print-btn" onclick="window.close()" style="background: #666;">Close</button>
</div>

<div class="report-header">
    <h1><?php echo htmlspecialchars($report_title); ?></h1>
    <div class="report-date">Generated on: <?php echo date('F j, Y g:i A'); ?></div>
</div>

<?php if ($tab === 'general'): ?>
    <table>
        <tr>
            <th>Metric</th>
            <th>Value</th>
        </tr>
        <tr>
            <td>Total Accounts</td>
            <td><?php echo number_format($report_data['total_accounts']); ?></td>
        </tr>
        <tr>
            <td>Total Revenue Today</td>
            <td>₱<?php echo number_format($report_data['revenue_today'], 2); ?></td>
        </tr>
        <tr>
            <td>Total Revenue (All Services)</td>
            <td>₱<?php echo number_format($report_data['revenue_all'], 2); ?></td>
        </tr>
        <tr>
            <td>Monthly Members</td>
            <td><?php echo number_format($report_data['monthly_members']); ?></td>
        </tr>
        <tr>
            <td>Boxing Members</td>
            <td><?php echo number_format($report_data['boxing_members']); ?></td>
        </tr>
        <tr>
            <td>Dancing Members</td>
            <td><?php echo number_format($report_data['dancing_members']); ?></td>
        </tr>
        <tr>
            <td>Day Session Members</td>
            <td><?php echo number_format($report_data['day_members']); ?></td>
        </tr>
    </table>
<?php else: ?>
    <table>
        <tr>
            <th>Metric</th>
            <th>Value</th>
        </tr>
        <tr>
            <td>Total Members</td>
            <td><?php echo number_format($report_data['total_members']); ?></td>
        </tr>
        <tr>
            <td>Total Revenue</td>
            <td>₱<?php echo number_format($report_data['total_revenue'], 2); ?></td>
        </tr>
        <tr>
            <td>Revenue Today</td>
            <td>₱<?php echo number_format($report_data['revenue_today'], 2); ?></td>
        </tr>
        <tr>
            <td>Weekly Revenue</td>
            <td>₱<?php echo number_format($report_data['revenue_weekly'], 2); ?></td>
        </tr>
        <tr>
            <td>Monthly Revenue</td>
            <td>₱<?php echo number_format($report_data['revenue_monthly'], 2); ?></td>
        </tr>
        <tr>
            <td>Yearly Revenue</td>
            <td>₱<?php echo number_format($report_data['revenue_yearly'], 2); ?></td>
        </tr>
    </table>
<?php endif; ?>

<div class="footer">
    <p>Generated by Athletiqs Gym Admin Dashboard</p>
    <p>All revenue figures are based on approved payments only.</p>
</div>

</body>
</html>

