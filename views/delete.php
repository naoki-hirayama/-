<?php
    $header_title = '削除画面';
    include('views/layouts/header.php');
?>
<body>
    <h2>削除画面</h2>
    <!--ログイン情報-->
    <?php  include('views/layouts/loginuserinfo.php') ?>
    <!--エラーメッセージ-->
    <?php  include('views/layouts/errormessage.php'); ?>
    <!-- ここまで -->
    <ul>
        <li>
            名前：<?php echo h($post['name']); ?><br />
            本文：<font color="<?php echo h($post['color']) ?>">
                        <?php echo h($post['comment']); ?>
                  </font><br />
            画像：
                <?php if (!empty($post['picture'])) : ?>
                    <img src="images/<?php echo h($post['picture']) ?>" width="300" height="200"><br />
                <?php else : ?>
                    なし<br />
                <?php endif ?>
            時間：<?php echo $post['created_at'] ?><br />
            ---------------------------------------------<br />
            <form action="delete.php?id=<?php echo h($post['id']) ?>" method="post">
                <?php if (isset($post['password']) && $post['password'] !== null) : ?>
                    <p>削除パスワード:</p>
                    <input type="password" name="password_input"><br />
                    <input type="submit" value="削除"/><br />
                <?php else : ?>
                    <input type="hidden" name="password_input">
                    <input type="submit" value="ユーザー削除"/><br />
                <?php endif ?>
                <a href="index.php">戻る</a>
            </form>        
        </li>
    </ul>
</body>
<?php
    include('views/layouts/footer.php');
?>