<?php
function db_connect($dbname = 'bbs', $username = 'root', $password = '')
{
    $database =  new PDO("mysql:host=localhost;dbname={$dbname};charset=utf8mb4;", $username, $password);
    $database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $database;
}

