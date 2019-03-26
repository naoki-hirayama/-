<?php
session_start();

if (isset($_SESSION['user_id'])) {
    session_destroy();
    include('views/logout.php');
} else {
    header("Location: login.php");
    exit;
}










