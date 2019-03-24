<?php
session_start();
require_once('function/db_connect.php');
require_once('function/function.php');
$database = db_connect();

$sql = 'SELECT * FROM users WHERE id = :id';

$statement = $database->prepare($sql);

$statement->bindParam(':id', $_GET['id']);

$statement->execute();

$user = $statement->fetch(PDO::FETCH_ASSOC);
if ($user === false) {
    header('HTTP/1.1 404 Not Found');
    exit;
}



include('views/profile.php');