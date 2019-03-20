<?php
    $header_title = '登録画面';
    include('views/layouts/header.php');
?>
<body>
    <h1>ユーザー登録画面</h1>
    <!-- エラーメッセージ -->
    <?php  include('views/layouts/errormessage.php'); ?>
    <form action="register.php" method="post" >
        <p>名前：</p>
        <input type="text" name="name" value=""><br />
        <p>ログインID：</p>
        <input type="text" name="login_id" value=""><br />
        <p>パスワード：</p>
        <input type="password" name="password"><br />
        <p>パスワード(確認)：</p>
        <input type="password" name="confirm_password"><br />
        <input type="submit" name="signup" value="登録する">
    </form>
    <a href="login.php"　class="btn btn-primary">すでに登録済みの方はこちらへ</a>
</body>
<?php
    include('views/layouts/footer.php');
?>