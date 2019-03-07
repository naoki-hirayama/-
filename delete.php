<?php

    //MySQLサーバ接続
    require('db_conect.php');

    // TODO データベースから削除対象のレコードを取得する
    $sql = 'SELECT * FROM post WHERE id = '.$_GET['id'].'';
            
    $statement = $database->query($sql);
            
    $record_id = $statement->fetchAll();
    
    foreach ($record_id as $record) {
        $password = $record['password'];
    }
    
    //パスワードが設定されてない投稿にアクセスされた時の処理
    if ($password == null) {
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
                
                if (strlen($_POST['password_input']) >= 1) {
                    if (strlen($_POST['password_input']) < 4) {
                        $errors[] = " パスワードは4文字以上です。";
                    }
                }
                
                if ($_POST['password_input'] ==! null) {
                    if (!preg_match("/^[a-zA-Z0-9]+$/", $_POST['password_input'])) {
                        $errors[] = " パスワードは半角英数字です。";
                    }
                }
            
            //パスワードが一致した時 
            if (empty($errors) && ($password === $_POST['password_input'])) {
                //DELETE文
                $sql = 'DELETE FROM post WHERE id = '.$_GET['id'].'';
                
                $statement = $database->prepare($sql);
                
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
                        <input type="text" name="password_input"><br />
                        <input type="submit" value="削除"/><br />
                    </form>        
                </li>
            </ul>
        <?php endforeach ?>
        
    </body>
</html>