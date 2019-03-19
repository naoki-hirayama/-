<?php
    $header_title = 'ログイン画面';
    include('views/layouts/header.php');
?>

<body>
    <h1>ログイン画面</h1>
    <!-- エラーメッセージ -->
    <?php  include('views/layouts/errormessage.php'); ?>
    <form action="login.php" method="post" >
        <p>ログインID：</p>
        <input type="text" name="login_id" value=""><br />
        <p>パスワード：</p>
        <input type="password" name="password"><br />
        <input type="submit" name="login" value="ログインする">
    </form>
    <a href="register.php"　class="btn btn-primary"> 未登録の方はこちらへ</a>
</body>

<?php
    include('views/layouts/footer.php');
?>