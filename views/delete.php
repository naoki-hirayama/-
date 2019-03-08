<?php
    $header_title = '削除画面';
    include_once('views/layouts/header.php');
?>
    <body>
        <h2>削除画面</h2>
        <!--エラーメッセージ-->
        <?php  include('views/layouts/errormessage.php'); ?>
        <!-- ここまで -->
        <ul>
            <li>
                名前：<?php echo h($record['name']); ?><br />
                本文：<font color="<?php echo $record['color'] ?>">
                            <?php echo h($record['comment']); ?>
                      </font><br />
                画像： <br />     
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
<?php
    include_once('views/layouts/footer.php');
?>