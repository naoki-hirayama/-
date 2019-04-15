<?php
    $header_title = '削除完了';
    include('../admin/views/layouts/header.php');
?>
<body>
    <h1>ID:<?php echo $_POST['post_id'] ?>の投稿を削除しました。</h1>
    <a href="index.php">戻る</a>
</body>
<?php
    include('../admin/views/layouts/footer.php');
?>