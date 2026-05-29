<?php 
require_once '../config/database.php';
require_once '../config/session_helper.php';
include 'includes/header.php'; 
include 'includes/sidebar.php'; 

$conn = getDBConnection();

// Get all statistics (only from approved payments)
// General View Stats
$total_accounts_query = "SELECT COUNT(*) as count FROM members";
$total_accounts_result = $conn->query($total_accounts_query);
$total_accounts = $total_accounts_result->fetch_assoc()['count'];

$revenue_today_query = "SELECT COALESCE(SUM(amount), 0) as total FROM payments WHERE status = 'Approved' AND DATE(payment_date) = CURDATE()";
$revenue_today_result = $conn->query($revenue_today_query);
$revenue_today = $revenue_today_result->fetch_assoc()['total'];

$revenue_all_query = "SELECT COALESCE(SUM(amount), 0) as total FROM payments WHERE status = 'Approved'";
$revenue_all_result = $conn->query($revenue_all_query);
$revenue_all = $revenue_all_result->fetch_assoc()['total'];

// Count members by service type (active memberships)
$monthly_members_query = "SELECT COUNT(DISTINCT member_id) as count FROM memberships WHERE membership_type = 'Monthly' AND status = 'Active'";
$monthly_members_result = $conn->query($monthly_members_query);
$monthly_members = $monthly_members_result->fetch_assoc()['count'];

$boxing_members_query = "SELECT COUNT(DISTINCT member_id) as count FROM memberships WHERE membership_type = 'Boxing' AND status = 'Active'";
$boxing_members_result = $conn->query($boxing_members_query);
$boxing_members = $boxing_members_result->fetch_assoc()['count'];

$dancing_members_query = "SELECT COUNT(DISTINCT member_id) as count FROM memberships WHERE membership_type = 'Dancing' AND status = 'Active'";
$dancing_members_result = $conn->query($dancing_members_query);
$dancing_members = $dancing_members_result->fetch_assoc()['count'];

$day_members_query = "SELECT COUNT(DISTINCT member_id) as count FROM memberships WHERE membership_type = 'Day Session' AND status = 'Active'";
$day_members_result = $conn->query($day_members_query);
$day_members = $day_members_result->fetch_assoc()['count'];

// Monthly View Stats
$monthly_total_query = "SELECT COALESCE(SUM(amount), 0) as total FROM payments WHERE status = 'Approved' AND payment_type = 'Monthly'";
$monthly_total_result = $conn->query($monthly_total_query);
$monthly_total = $monthly_total_result->fetch_assoc()['total'];

$monthly_today_query = "SELECT COALESCE(SUM(amount), 0) as total FROM payments WHERE status = 'Approved' AND payment_type = 'Monthly' AND DATE(payment_date) = CURDATE()";
$monthly_today_result = $conn->query($monthly_today_query);
$monthly_today = $monthly_today_result->fetch_assoc()['total'];

$monthly_weekly_query = "SELECT COALESCE(SUM(amount), 0) as total FROM payments WHERE status = 'Approved' AND payment_type = 'Monthly' AND payment_date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)";
$monthly_weekly_result = $conn->query($monthly_weekly_query);
$monthly_weekly = $monthly_weekly_result->fetch_assoc()['total'];

$monthly_monthly_query = "SELECT COALESCE(SUM(amount), 0) as total FROM payments WHERE status = 'Approved' AND payment_type = 'Monthly' AND payment_date >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)";
$monthly_monthly_result = $conn->query($monthly_monthly_query);
$monthly_monthly = $monthly_monthly_result->fetch_assoc()['total'];

$monthly_yearly_query = "SELECT COALESCE(SUM(amount), 0) as total FROM payments WHERE status = 'Approved' AND payment_type = 'Monthly' AND YEAR(payment_date) = YEAR(CURDATE())";
$monthly_yearly_result = $conn->query($monthly_yearly_query);
$monthly_yearly = $monthly_yearly_result->fetch_assoc()['total'];

// Day Session View Stats
$day_total_query = "SELECT COALESCE(SUM(amount), 0) as total FROM payments WHERE status = 'Approved' AND payment_type = 'Day Session'";
$day_total_result = $conn->query($day_total_query);
$day_total = $day_total_result->fetch_assoc()['total'];

$day_today_query = "SELECT COALESCE(SUM(amount), 0) as total FROM payments WHERE status = 'Approved' AND payment_type = 'Day Session' AND DATE(payment_date) = CURDATE()";
$day_today_result = $conn->query($day_today_query);
$day_today = $day_today_result->fetch_assoc()['total'];

$day_weekly_query = "SELECT COALESCE(SUM(amount), 0) as total FROM payments WHERE status = 'Approved' AND payment_type = 'Day Session' AND payment_date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)";
$day_weekly_result = $conn->query($day_weekly_query);
$day_weekly = $day_weekly_result->fetch_assoc()['total'];

$day_monthly_query = "SELECT COALESCE(SUM(amount), 0) as total FROM payments WHERE status = 'Approved' AND payment_type = 'Day Session' AND payment_date >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)";
$day_monthly_result = $conn->query($day_monthly_query);
$day_monthly = $day_monthly_result->fetch_assoc()['total'];

$day_yearly_query = "SELECT COALESCE(SUM(amount), 0) as total FROM payments WHERE status = 'Approved' AND payment_type = 'Day Session' AND YEAR(payment_date) = YEAR(CURDATE())";
$day_yearly_result = $conn->query($day_yearly_query);
$day_yearly = $day_yearly_result->fetch_assoc()['total'];

// Boxing View Stats
$boxing_total_query = "SELECT COALESCE(SUM(amount), 0) as total FROM payments WHERE status = 'Approved' AND payment_type = 'Boxing'";
$boxing_total_result = $conn->query($boxing_total_query);
$boxing_total = $boxing_total_result->fetch_assoc()['total'];

$boxing_today_query = "SELECT COALESCE(SUM(amount), 0) as total FROM payments WHERE status = 'Approved' AND payment_type = 'Boxing' AND DATE(payment_date) = CURDATE()";
$boxing_today_result = $conn->query($boxing_today_query);
$boxing_today = $boxing_today_result->fetch_assoc()['total'];

$boxing_weekly_query = "SELECT COALESCE(SUM(amount), 0) as total FROM payments WHERE status = 'Approved' AND payment_type = 'Boxing' AND payment_date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)";
$boxing_weekly_result = $conn->query($boxing_weekly_query);
$boxing_weekly = $boxing_weekly_result->fetch_assoc()['total'];

$boxing_monthly_query = "SELECT COALESCE(SUM(amount), 0) as total FROM payments WHERE status = 'Approved' AND payment_type = 'Boxing' AND payment_date >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)";
$boxing_monthly_result = $conn->query($boxing_monthly_query);
$boxing_monthly = $boxing_monthly_result->fetch_assoc()['total'];

$boxing_yearly_query = "SELECT COALESCE(SUM(amount), 0) as total FROM payments WHERE status = 'Approved' AND payment_type = 'Boxing' AND YEAR(payment_date) = YEAR(CURDATE())";
$boxing_yearly_result = $conn->query($boxing_yearly_query);
$boxing_yearly = $boxing_yearly_result->fetch_assoc()['total'];

// Dancing View Stats
$dancing_total_query = "SELECT COALESCE(SUM(amount), 0) as total FROM payments WHERE status = 'Approved' AND payment_type = 'Dancing'";
$dancing_total_result = $conn->query($dancing_total_query);
$dancing_total = $dancing_total_result->fetch_assoc()['total'];

$dancing_today_query = "SELECT COALESCE(SUM(amount), 0) as total FROM payments WHERE status = 'Approved' AND payment_type = 'Dancing' AND DATE(payment_date) = CURDATE()";
$dancing_today_result = $conn->query($dancing_today_query);
$dancing_today = $dancing_today_result->fetch_assoc()['total'];

$dancing_weekly_query = "SELECT COALESCE(SUM(amount), 0) as total FROM payments WHERE status = 'Approved' AND payment_type = 'Dancing' AND payment_date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)";
$dancing_weekly_result = $conn->query($dancing_weekly_query);
$dancing_weekly = $dancing_weekly_result->fetch_assoc()['total'];

$dancing_monthly_query = "SELECT COALESCE(SUM(amount), 0) as total FROM payments WHERE status = 'Approved' AND payment_type = 'Dancing' AND payment_date >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)";
$dancing_monthly_result = $conn->query($dancing_monthly_query);
$dancing_monthly = $dancing_monthly_result->fetch_assoc()['total'];

$dancing_yearly_query = "SELECT COALESCE(SUM(amount), 0) as total FROM payments WHERE status = 'Approved' AND payment_type = 'Dancing' AND YEAR(payment_date) = YEAR(CURDATE())";
$dancing_yearly_result = $conn->query($dancing_yearly_query);
$dancing_yearly = $dancing_yearly_result->fetch_assoc()['total'];

closeDBConnection($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="assets/css/admin.css">
</head>
<body>

<div class="main-content">

    <!-- DASHBOARD HEADER WITH VIEW TABS -->
    <div class="dashboard-header">
        <h1 class="page-title">Dashboard</h1>
        <div class="dashboard-tabs">
            <button class="dash-tab active" data-view="general">General</button>
            <button class="dash-tab" data-view="monthly">Monthly Members</button>
            <button class="dash-tab" data-view="day">Day Session</button>
            <button class="dash-tab" data-view="boxing">Boxing Session</button>
            <button class="dash-tab" data-view="dancing">Dancing Session</button>
        </div>
    </div>

    <!-- GENERAL VIEW -->
    <section class="dashboard-view active" id="view-general">
        <div class="dashboard-toolbar">
            <div class="revenue-filter">
                <span>Revenue period:</span>
                <button class="rev-filter-btn active" data-period="weekly" disabled>Weekly</button>
                <button class="rev-filter-btn" data-period="monthly" disabled>Monthly</button>
                <button class="rev-filter-btn" data-period="yearly" disabled>Yearly</button>
            </div>
            <button class="generate-report-btn" type="button" data-tab="general">
                <i class="fa-solid fa-download"></i> Generate Report
            </button>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <h2>Total Accounts</h2>
                <p><?php echo number_format($total_accounts); ?></p>
            </div>

            <div class="stat-card">
                <h2>Total Revenue Today</h2>
                <p>₱<?php echo number_format($revenue_today, 2); ?></p>
            </div>

            <div class="stat-card">
                <h2>Total Revenue (All Services)</h2>
                <p>₱<?php echo number_format($revenue_all, 2); ?></p>
            </div>

            <div class="stat-card">
                <h2>Monthly Members</h2>
                <p><?php echo number_format($monthly_members); ?></p>
            </div>

            <div class="stat-card">
                <h2>Boxing Members</h2>
                <p><?php echo number_format($boxing_members); ?></p>
            </div>

            <div class="stat-card">
                <h2>Dancing Members</h2>
                <p><?php echo number_format($dancing_members); ?></p>
            </div>

            <div class="stat-card">
                <h2>Day Sessions</h2>
                <p><?php echo number_format($day_members); ?></p>
            </div>
        </div>
    </section>

    <!-- MONTHLY VIEW -->
    <section class="dashboard-view" id="view-monthly">
        <div class="dashboard-toolbar">
            <button class="generate-report-btn" type="button" data-tab="monthly">
                <i class="fa-solid fa-download"></i> Generate Report
            </button>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <h2>Total Monthly Members</h2>
                <p><?php echo number_format($monthly_members); ?></p>
            </div>
            <div class="stat-card">
                <h2>Total Revenue</h2>
                <p>₱<?php echo number_format($monthly_total, 2); ?></p>
            </div>
            <div class="stat-card">
                <h2>Revenue Today</h2>
                <p>₱<?php echo number_format($monthly_today, 2); ?></p>
            </div>
            <div class="stat-card">
                <h2>Weekly Revenue</h2>
                <p>₱<?php echo number_format($monthly_weekly, 2); ?></p>
            </div>
            <div class="stat-card">
                <h2>Monthly Revenue</h2>
                <p>₱<?php echo number_format($monthly_monthly, 2); ?></p>
            </div>
            <div class="stat-card">
                <h2>Yearly Revenue</h2>
                <p>₱<?php echo number_format($monthly_yearly, 2); ?></p>
            </div>
        </div>
    </section>

    <!-- DAY SESSION VIEW -->
    <section class="dashboard-view" id="view-day">
        <div class="dashboard-toolbar">
            <button class="generate-report-btn" type="button" data-tab="day">
                <i class="fa-solid fa-download"></i> Generate Report
            </button>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <h2>Total Day Session Members</h2>
                <p><?php echo number_format($day_members); ?></p>
            </div>
            <div class="stat-card">
                <h2>Total Revenue</h2>
                <p>₱<?php echo number_format($day_total, 2); ?></p>
            </div>
            <div class="stat-card">
                <h2>Revenue Today</h2>
                <p>₱<?php echo number_format($day_today, 2); ?></p>
            </div>
            <div class="stat-card">
                <h2>Weekly Revenue</h2>
                <p>₱<?php echo number_format($day_weekly, 2); ?></p>
            </div>
            <div class="stat-card">
                <h2>Monthly Revenue</h2>
                <p>₱<?php echo number_format($day_monthly, 2); ?></p>
            </div>
            <div class="stat-card">
                <h2>Yearly Revenue</h2>
                <p>₱<?php echo number_format($day_yearly, 2); ?></p>
            </div>
        </div>
    </section>

    <!-- BOXING SESSION VIEW -->
    <section class="dashboard-view" id="view-boxing">
        <div class="dashboard-toolbar">
            <button class="generate-report-btn" type="button" data-tab="boxing">
                <i class="fa-solid fa-download"></i> Generate Report
            </button>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <h2>Total Boxing Members</h2>
                <p><?php echo number_format($boxing_members); ?></p>
            </div>
            <div class="stat-card">
                <h2>Total Revenue</h2>
                <p>₱<?php echo number_format($boxing_total, 2); ?></p>
            </div>
            <div class="stat-card">
                <h2>Revenue Today</h2>
                <p>₱<?php echo number_format($boxing_today, 2); ?></p>
            </div>
            <div class="stat-card">
                <h2>Weekly Revenue</h2>
                <p>₱<?php echo number_format($boxing_weekly, 2); ?></p>
            </div>
            <div class="stat-card">
                <h2>Monthly Revenue</h2>
                <p>₱<?php echo number_format($boxing_monthly, 2); ?></p>
            </div>
            <div class="stat-card">
                <h2>Yearly Revenue</h2>
                <p>₱<?php echo number_format($boxing_yearly, 2); ?></p>
            </div>
        </div>
    </section>

    <!-- DANCING SESSION VIEW -->
    <section class="dashboard-view" id="view-dancing">
        <div class="dashboard-toolbar">
            <button class="generate-report-btn" type="button" data-tab="dancing">
                <i class="fa-solid fa-download"></i> Generate Report
            </button>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <h2>Total Dancing Members</h2>
                <p><?php echo number_format($dancing_members); ?></p>
            </div>
            <div class="stat-card">
                <h2>Total Revenue</h2>
                <p>₱<?php echo number_format($dancing_total, 2); ?></p>
            </div>
            <div class="stat-card">
                <h2>Revenue Today</h2>
                <p>₱<?php echo number_format($dancing_today, 2); ?></p>
            </div>
            <div class="stat-card">
                <h2>Weekly Revenue</h2>
                <p>₱<?php echo number_format($dancing_weekly, 2); ?></p>
            </div>
            <div class="stat-card">
                <h2>Monthly Revenue</h2>
                <p>₱<?php echo number_format($dancing_monthly, 2); ?></p>
            </div>
            <div class="stat-card">
                <h2>Yearly Revenue</h2>
                <p>₱<?php echo number_format($dancing_yearly, 2); ?></p>
            </div>
        </div>
    </section>

</div>

<?php include 'includes/footer.php'; ?>

<script>
// Dashboard tab switching
document.addEventListener('DOMContentLoaded', function() {
    const tabs = document.querySelectorAll('.dash-tab');
    const views = document.querySelectorAll('.dashboard-view');
    
    tabs.forEach(tab => {
        tab.addEventListener('click', function() {
            const viewName = this.getAttribute('data-view');
            
            // Remove active class from all tabs and views
            tabs.forEach(t => t.classList.remove('active'));
            views.forEach(v => v.classList.remove('active'));
            
            // Add active class to clicked tab and corresponding view
            this.classList.add('active');
            document.getElementById('view-' + viewName).classList.add('active');
        });
    });
    
    // Generate report functionality
    const generateReportBtns = document.querySelectorAll('.generate-report-btn');
    generateReportBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const tab = this.getAttribute('data-tab');
            window.location.href = 'generate_report.php?tab=' + encodeURIComponent(tab);
        });
    });
});
</script>

</body>
</html>
