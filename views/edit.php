<?php
    $header_title = 'プロフィール編集';
    include('views/layouts/header.php');
?>
<body>
    <h1>プロフィール編集ぺージ</h1>
    <!-- エラーメッセージ -->
    <?php  include('views/layouts/errormessage.php') ?>
    <!-- ここまで -->
    <form action="edit.php?id=<?php echo h($user['id']) ?>" method="post" enctype="multipart/form-data">
        <p>ログインID：</p>
        <input type="text" name="login_id" value="<?php echo h($user['login_id']) ?>">
        <p>名前：</p>
        <input type="text" name="name" value="<?php echo h($user['name']) ?>"><br />
        <p>画像：</p>
        <?php if (!empty($user['picture'])) : ?>
            <img src="userimages/<?php echo h($user['picture']) ?>" width="150" height="150"><br />
        <?php else : ?>
            なし<br />
        <?php endif ?>
        <input type="hidden" name="MAX_FILE_SIZE" value="<?php echo $picture_max_size ?>">
        <input type="file" name="picture"><br />
        <p>一言コメント：</p>
        <?php if (!empty($user['comment'])) : ?>
             <input type="text" name="comment" value="<?php echo h($user['comment']) ?>"><br />
        <?php else : ?>
            <input type="text" name="comment" ><br />
        <?php endif ?>
        <input type="submit" name="submit" value="編集する">
    </form>
    <a href="password.php?id=<?php echo h($user['id']) ?>">パスワードを変える</a>    
    <a href="profile.php?id=<?php echo h($user['id']) ?>">戻る</a>
</body>
<?php
    include('views/layouts/footer.php');
?>