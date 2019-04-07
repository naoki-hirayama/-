<?php
    $header_title = 'レス削除画面';
    include('views/layouts/header.php');
?>
<body>
    <h2>削除画面</h2>
    <!--ログイン情報-->
    <?php  include('views/layouts/loginuserinfo.php') ?>
    <!--エラーメッセージ-->
    <?php  include('views/layouts/errormessage.php'); ?>
    <ul>
        <li>
            名前：
            <?php if (!empty($reply_post['user_id'])) : ?>
                <?php echo h($current_user_name['name']) ?><br />
            <?php else : ?>
                <?php echo h($reply_post['name']) ?><br />
            <?php endif ?>
            本文：
            <?php echo h($reply_post['comment']) ?><br />
            画像：
            <?php if (!empty($reply_post['picture'])) : ?>
                <img src="images/replies/<?php echo h($reply_post['picture']) ?>" width="300" height="200"><br />
            <?php else : ?>
                なし<br />
            <?php endif ?>
            時間：
            <?php echo h($reply_post['created_at']) ?><br />
            ---------------------------------------------<br />
            <form action="deletereply.php?id=<?php echo $reply_post['id'] ?>" method="post">
            <?php if (isset($reply_post['password']) && $reply_post['password'] !== null) : ?>
                <p>削除パスワード:</p>
                <input type="password" name="password_input"><br />
                <input type="submit" value="削除"/><br />
            <?php else : ?>
                <input type="hidden" name="password_input">
                <input type="submit" value="ユーザー削除"/><br />
            <?php endif ?>
            <a href="reply.php?id=<?php echo $reply_post['post_id'] ?>">戻る</a>
            </form>        
        </li>
    </ul>
</body>
<?php
    include('views/layouts/footer.php');
?>