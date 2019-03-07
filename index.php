<?php

    //MySQLサーバ接続
    require('db_conect.php');
    
    // セレクトボックスの連想配列
    $select_options = ['black'=>'黒','red'=>'赤','blue'=>'青','yellow'=>'黄','green'=>'緑'];
    
    // POSTでアクセスされたら投稿処理を行う
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $errors = [];
        
        // バリデーションを行う
        if (strlen($_POST['name']) === 0) {
            $errors[] = "名前は入力必須です。";
        } else if (strlen($_POST['name']) > 10) {
            $errors[] = "名前は１０文字以内です。";
        }
        
        if (strlen($_POST['comment']) === 0) {
            $errors[] = "本文は入力必須です。";
        } else if (strlen($_POST['comment']) > 100) {
            $errors[] = "本文は１００文字以内です。";
        }
        
        if (!array_key_exists($_POST['color'], $select_options)) {
           $errors[] = "文字色が不正です"; 
        }
        
        if (strlen($_POST['password']) !== 0) {
            if (strlen($_POST['password']) < 4) {
                $errors[] = " パスワードは4文字以上です。";
            }
            
            if (!preg_match("/^[a-zA-Z0-9]+$/", $_POST['password'])) {
                $errors[] = " パスワードは半角英数字です。";
            }
        }
        
        // 成功した場合はDBへ保存してsend.phpにリダイレクトする
        if (empty($errors)) {
            $sql = 'INSERT INTO post (name,comment,color,password) VALUES(:name,:comment,:color,:password)';
            
            $statement = $database->prepare($sql);
            
            //パスワードが入力されない時の処理
            if (empty($_POST['password'])) {
                $_POST['password'] = null;
            }
            
            $statement->bindParam(':name', $_POST['name']);
            $statement->bindParam(':comment', $_POST['comment']);
            $statement->bindParam(':color', $_POST['color']);
            $statement->bindParam(':password', $_POST['password']);
            
            $statement->execute();
            
            $statement = null;
    
            header('Location: send.php');
            exit;
        }
    // GETでアクセスされたら一覧表示用にDBから投稿を取得する
    } else {
        $sql = 'SELECT * FROM post ORDER BY created_at DESC';
        
        $statement = $database->prepare($sql);
        
        $statement->execute();
        
        $records = $statement->fetchAll();
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
        <title>掲示板</title>
    </head>
    <body>
        <h1>投稿画面</h1>
        <ul>
            <?php if (!empty($errors)) : ?>
                <?php foreach ($errors as $error) : ?>
                    <li><?php echo $error ?></li>
                <?php endforeach ?>
            <?php endif ?>
        </ul>
        <!-- エラーメッセージを出してね -->
        <form action="index.php" method="post">
            <p>名前：</p>
            <input type="text" name="name" value="<?php echo $_POST['name'] ?>">
            <p>本文：</p>
            <textarea name="comment" rows="4" cols="20"><?php echo $_POST['comment'] ?></textarea><br />
            <select name="color">
                <?php foreach($select_options as $key => $value) : ?>
                    <option value="<?php echo $key ?>"<?php echo ($key === $_POST['color']) ? 'selected' : ''; ?>>
                        <?php echo $value; ?>
                    </option>
                <?php endforeach ?>
            </select><br />
            <p>削除パスワード:</p>
            <input type="password" name="password"><br />
            <input type="submit" name="submit" value="投稿">
        </form>
        <?php if (empty($errors)) : ?>
            <h2>投稿一覧</h2>
            <ul>
                <?php if ($records) : ?>
                    <?php foreach ($records as $record) : ?>
                        <li>
                            ID : <?php echo $record['id'] ?><br />
                            名前：<?php echo htmlspecialchars($record['name'], ENT_QUOTES, "UTF-8"); ?><br />
                            本文：<font color="<?php echo $record['color'] ?>">
                                      <?php echo htmlspecialchars($record['comment'], ENT_QUOTES, "UTF-8"); ?>
                                  </font><br />
                            時間：<?php echo $record['created_at'] ?><br />
    
                            <!--if文でパスワードが設定されていなかったら非表示   -->
                            <?php if (isset($record['password']) && $record['password'] !== null) : ?>
                            <form action="delete.php" method="get">
                                <input type="hidden" name="id" value="<?php echo $record['id'] ?>">
                                <input type="submit" value="削除"/><br />
                            </form>
                            <?php endif ?>    
                            <!--　ここまで　-->
                        
                            ---------------------------------------------<br />
                        </li>
                    <?php endforeach ?>
                <?php endif ?>
            </ul>
        <?php endif ?>        
    </body>
</html>