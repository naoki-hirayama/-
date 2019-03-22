<?php
    $header_title = '削除画面';
    include('views/layouts/header.php');
?>
    <body>
        <h2>削除画面</h2>
        <!--エラーメッセージ-->
        <?php  include('views/layouts/errormessage.php'); ?>
        <!-- ここまで -->
        <ul>
            <li>
                名前：<?php echo h($post['name']); ?><br />
                本文：<font color="<?php echo $post['color'] ?>">
                            <?php echo h($post['comment']); ?>
                      </font><br />
                画像：
                    <?php if (isset($post['picture']) && $post['picture'] !== null) : ?>
                        <img src="images/<?php echo $post['picture'] ?>" width="300" height="200"><br />
                    <?php else : ?>
                        なし<br />
                    <?php endif ?>
                時間：<?php echo $post['created_at'] ?><br />
                ---------------------------------------------<br />
                <form action="delete.php?id=<?php echo $post['id'] ?>" method="post">
                    <?php if (isset($post['password']) && $post['password'] !== null) : ?>
                    <p>削除パスワード:</p>
                    <input type="password" name="password_input"><br />
                    <input type="submit" value="削除"/><br />
                    <?php else : ?>
                    <input type="hidden" name="password_input">
                    <input type="submit" value="ユーザー削除"/><br />
                    <?php endif ?>
                </form>        
            </li>
        </ul>
    </body>
<?php
    include('views/layouts/footer.php');
?>