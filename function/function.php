<?php
// エスケープの関数
function h($s)
{
    return htmlspecialchars($s, ENT_QUOTES, "UTF-8");
}
//ユーザーテーブルから一件のレコード取得
function select_users($id)
{
    $database = db_connect();
    
    $sql = 'SELECT * FROM users WHERE id = :id';
    
    $statement = $database->prepare($sql);
    
    $statement->bindParam(':id', $id);
    
    $statement->execute();
    
    return $statement->fetch(PDO::FETCH_ASSOC);
}
//ポストテーブルから一件のレコード取得
function select_post($id)
{
    $database = db_connect();
    
    $sql = 'SELECT * FROM post WHERE id = :id';
    
    $statement = $database->prepare($sql);
    
    $statement->bindParam(':id', $id);
    
    $statement->execute();
    
    return $statement->fetch(PDO::FETCH_ASSOC);
}