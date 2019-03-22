<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit;
}
require_once('function/db_connect.php');
require_once('function/function.php');
$database = db_connect();

$sql = 'SELECT * FROM users WHERE id = :id';

$statement = $database->prepare($sql);

$statement->bindParam(':id', $_GET['id']);

$statement->execute();

$user = $statement->fetch(PDO::FETCH_ASSOC);

include('views/edit.php');