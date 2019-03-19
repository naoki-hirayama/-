<?php
session_start();

if (!isset($_SESSION['login_id'])) {
    header("Location: login.php");
}


include('views/registered.php');