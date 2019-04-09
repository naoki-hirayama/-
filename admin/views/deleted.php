<?php
    $header_title = '削除完了';
    include('../admin/views/layouts/header.php');
?>
<body>
    <h1>削除完了を完了しました</h1>
    <?php dd($_POST) ?>
    <a href="index.php">戻る</a>
</body>
<?php
    include('../admin/views/layouts/footer.php');
?>