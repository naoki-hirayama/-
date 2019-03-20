<?php
session_start();

if (!isset($_SESSION['login_id'], $_SESSION['name'])) {
    header("Location: login.php");
    exit;
}


include('views/registered.php');