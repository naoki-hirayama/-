<?php

    //MySQLサーバ接続
    require('db_conect.php');

    // SQLインジェクション
    // $get_id = $_GET['id'];
    
    $sql = 'SELECT * FROM post WHERE id = :id';
            
    $statement = $database->prepare($sql);
    
    $statement->bindParam(':id', $_GET['id']);
    
    $statement->execute();
    
    $record_id = $statement->fetchAll();
    
    
    //idに該当するpostがデータベースに存在するかどうかチェックする 
    $post_id =  $record_id[0][0];
    
    $password_colum = $record_id[0][4];
     
    
    if (isset($post_id) === false) {
        header('Location: index.php');
        exit;
    } elseif (!empty($password_colum)) {
        foreach ($record_id as $record) {
        $password = $record['password'];
        }
    } else {
        header('Location: index.php');
        exit;
    }
    
    // delete.phpからPOST送信された
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            //パスワードが一致しない、不正時のエラー処理
            $errors = [];
            if ($password !== $_POST['password_input']) {
                $errors[] = "パスワードが違います";
            }
            
            //パスワードが一致した時 
            if (empty($errors)) {
                
                $sql = 'DELETE FROM post WHERE id = :id';
                
                $statement = $database->prepare($sql);
                
                $statement->bindParam(':id', $_GET['id']);
                
                $statement->execute();
                
                $statement = null;
                
                header('Location: deleted.php');
                exit;
            }
            
    }
    
    $statement = null;
    
    $database = null;
    
?>
<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css">
        <title>削除確認</title>
    </head>
    <body>
        <h2>削除画面</h2>
        <ul>
            <?php if (!empty($errors)) : ?>
                <?php foreach ($errors as $error) : ?>
                    <li><?php echo $error ?></li>
                <?php endforeach ?>
            <?php endif ?>
        </ul>
        
        <?php foreach ($record_id as $record) : ?>
            <ul>
                <li>
                    名前：<?php echo htmlspecialchars($record['name'], ENT_QUOTES, "UTF-8"); ?><br />
                    本文：<font color="<?php echo $record['color'] ?>">
                                <?php echo htmlspecialchars($record['comment'], ENT_QUOTES, "UTF-8"); ?>
                        　</font><br />
                    時間：<?php echo $record['created_at'] ?><br />
                    ---------------------------------------------<br />
                    <form action="delete.php?id=<?php echo $record['id'] ?>" method="post">
                        <p>削除パスワード:</p>
                        <input type="password" name="password_input"><br />
                        <input type="submit" value="削除"/><br />
                    </form>        
                </li>
            </ul>
        <?php endforeach ?>
        
    </body>
</html>