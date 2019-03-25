<?php
    $header_title = 'パスワード編集';
    include('views/layouts/header.php');
?>
<body>
    <h1>パスワードを編集</h1>
    <!--ログイン情報-->
    <?php  include('views/layouts/loginuserinfo.php') ?>
    <!-- エラーメッセージ -->
    <?php  include('views/layouts/errormessage.php') ?>
    <!-- ここまで -->
    <form action="password.php" method="post">
        <p>現在のパスワード:</p>
        <input type="password" name="current_password"><br />
        <p>新しいパスワード:</p>
        <input type="password" name="new_password"><br />
        <p>確認用パスワード:</p>
        <input type="password" name="confirm_password"><br />
        <input type="submit" value="変更する"/><br />
    </form>
    <a href="edit.php">戻る</a><br />
</body>
<?php
    include('views/layouts/footer.php');
?>