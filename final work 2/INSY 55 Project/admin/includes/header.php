<?php 
require_once '../config/session_helper.php';
$username = getLoggedInUsername() ?? 'admin_user';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

</head>
<body>
<style>
.topbar {
    height: 60px;
    background: #2f2f2f;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 25px;
    color: white;
}

.top-right {
    display: flex;
    align-items: center;
    gap: 20px;
}

.top-right i {
    font-size: 20px;
    cursor: pointer;
}

.profile-dropdown {
    position: relative;
    display: inline-block;
}

.profile {
    display: flex;
    align-items: center;
    gap: 10px;
    cursor: pointer;
    padding: 5px 10px;
    border-radius: 20px;
    transition: background 0.2s;
}

.profile:hover {
    background: rgba(255, 255, 255, 0.1);
}

.profile img {
    width: 34px;
    height: 34px;
    border-radius: 50%;
    background: white;
}

.profile span {
    color: white;
    font-size: 14px;
}

.profile i {
    font-size: 12px;
    color: white;
}

.profile-menu {
    display: none;
    position: absolute;
    right: 0;
    top: 100%;
    margin-top: 10px;
    background: #2f2f2f;
    border: 1px solid #444;
    border-radius: 8px;
    min-width: 150px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
    z-index: 1000;
    overflow: hidden;
}

.profile-menu.show {
    display: block;
}

.profile-menu a {
    display: block;
    padding: 12px 20px;
    color: white;
    text-decoration: none;
    border-bottom: 1px solid #444;
    transition: background 0.2s;
}

.profile-menu a:last-child {
    border-bottom: none;
}

.profile-menu a:hover {
    background: #3f3f3f;
}
</style>

<div class="topbar">
    <h2 style="margin:0; font-weight:normal;">ATHLETIQS GYM</h2>

    <div class="top-right">
        <!-- Notification Button -->
        <i class="fa-solid fa-bell"></i>

        <!-- Profile Section with Dropdown -->
        <div class="profile-dropdown">
            <div class="profile" onclick="toggleAdminProfileMenu()">
                <img src="https://cdn-icons-png.flaticon.com/512/149/149071.png">
                <span><?php echo htmlspecialchars($username); ?></span>
                <i class="fa-solid fa-chevron-down"></i>
            </div>
            <div class="profile-menu" id="adminProfileMenu">
                <a href="../logout.php">Logout</a>
            </div>
        </div>
    </div>
</div>

<script>
function toggleAdminProfileMenu() {
    const menu = document.getElementById('adminProfileMenu');
    if (menu) menu.classList.toggle('show');
}

// Close profile menu when clicking outside
document.addEventListener('click', function(event) {
    const dropdown = document.querySelector('.profile-dropdown');
    const menu = document.getElementById('adminProfileMenu');
    if (dropdown && menu && !dropdown.contains(event.target)) {
        menu.classList.remove('show');
    }
});
</script>

</body>
</html>