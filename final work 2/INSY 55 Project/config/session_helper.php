<?php
// Session helper functions
session_start();

function isLoggedIn() {
    return isset($_SESSION['member_id']) || isset($_SESSION['user_id']);
}

function getLoggedInUserId() {
    if (isset($_SESSION['member_id'])) {
        return $_SESSION['member_id'];
    } elseif (isset($_SESSION['user_id'])) {
        return $_SESSION['user_id'];
    }
    return null;
}

function getLoggedInUsername() {
    return isset($_SESSION['username']) ? $_SESSION['username'] : null;
}

function getLoggedInEmail() {
    return isset($_SESSION['email']) ? $_SESSION['email'] : null;
}

function getUserType() {
    if (isset($_SESSION['member_id'])) {
        return 'member';
    } elseif (isset($_SESSION['user_id'])) {
        return isset($_SESSION['user_type']) ? $_SESSION['user_type'] : 'admin';
    }
    return null;
}
?>

