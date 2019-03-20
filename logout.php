<?php
session_start();

if (isset($_SESSION['login_id'], $_SESSION['name'])) {
    session_destroy();
    include('views/logout.php');
} else {
    header("Location: login.php");
    exit;
}










