<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
   <!-- sidebar.php -->
<aside class="sidebar">
    <div class="logo-area">
        <h3>ATHLETIQS GYM</h3>
        <span>Admin</span>
    </div>

    <ul class="sidebar-menu">
        <li><a href="dashboard.php"><i class="fas fa-chart-line"></i> Dashboard</a></li>
        <li><a href="accounts.php"><i class="fas fa-user"></i> Accounts</a></li>
        <li><a href="payments.php"><i class="fas fa-money-bill-wave"></i> Payments</a></li>
        <li><a href="memberships.php"><i class="fas fa-id-card"></i> Memberships</a></li>
        <li><a href="logs.php"><i class="fas fa-history"></i> Log Activity</a></li>
    </ul>

</aside>

<style>
.sidebar {
    width: 220px;
    height: 100vh;
    background: #1f1f1f;
    color: white;
    padding: 20px 0;
    position: fixed;
    left: 0;
    top: 0;
    border-right: 1px solid #444;
    font-family: 'Poppins', sans-serif;
}

.sidebar .logo-area {
    text-align: center;
    margin-bottom: 30px;
}

.sidebar .logo-area h3 {
    margin: 0;
    font-size: 18px;
}

.sidebar .logo-area span {
    font-size: 13px;
    opacity: 0.7;
}

.sidebar-menu {
    list-style: none;
    padding: 0;
    margin: 0;
}

.sidebar-menu li {
    margin: 10px 0;
}

.sidebar-menu a {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 10px 20px;
    color: white;
    text-decoration: none;
    font-size: 15px;
    transition: 0.2s;
}

.sidebar-menu a:hover {
    background: #333;
}

.logout-btn {
    display: block;
    margin-top: 50px;
    padding: 10px 20px;
    color: white;
    text-decoration: none;
    font-size: 14px;
    opacity: 0.8;
}

.logout-btn:hover {
    opacity: 1;
}
</style>

</body>
</html>