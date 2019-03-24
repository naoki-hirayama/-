<?php
    $header_title = 'パスワード編集';
    include('views/layouts/header.php');
?>
<body>
    <h1><?php echo h($user['name']) ?>さんのパスワードを編集</h1>
    <!-- エラーメッセージ -->
    <?php  include('views/layouts/errormessage.php') ?>
    <!-- ここまで -->
    <form action="password.php?id=<?php echo $user['id'] ?>" method="post">
        <p>現在のパスワード:</p>
        <input type="password" name="current_password"><br />
        <p>新しいパスワード:</p>
        <input type="password" name="new_password"><br />
        <p>確認用パスワード:</p>
        <input type="password" name="confirm_password"><br />
        <input type="submit" value="変更する"/><br />
    </form>
</body>
<?php
    include('views/layouts/footer.php');
?>