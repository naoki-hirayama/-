<?php
// エスケープの関数
function h($s)
{
    return htmlspecialchars($s, ENT_QUOTES, "UTF-8");
}
//ユーザーテーブルから一件のレコードを取得
function fetch_user_by_id($id, $database)
{
    $sql = 'SELECT * FROM users WHERE id = :id';
    
    $statement = $database->prepare($sql);
    
    $statement->bindParam(':id', $id);
    
    $statement->execute();
    
    return $statement->fetch(PDO::FETCH_ASSOC);
}
//ポストテーブルから一件のレコードを取得
function fetch_post_by_id($id, $database)
{
    $sql = 'SELECT * FROM posts WHERE id = :id';
    
    $statement = $database->prepare($sql);
    
    $statement->bindParam(':id', $id);
    
    $statement->execute();
    
    return $statement->fetch(PDO::FETCH_ASSOC);
}

function dd($s)
{
    return var_dump($s);
}
