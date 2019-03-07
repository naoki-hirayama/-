<?php
    //MySQLサーバ接続
    require('db_conect.php');
    
    include('function_delete.php');

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
        
    </body>
</html>