<?php
    include('views/layouts/header.php');
?>
<body>
    <!--ログイン情報-->
    <?php include('views/layouts/loginuserinfo.php') ?>
    <h1>レスを削除しました。</h1>
    <a href="reply.php?id=<?php echo $_GET['id'] ?>">戻る</a>
</body>
<?php
    include('views/layouts/footer.php');
?>