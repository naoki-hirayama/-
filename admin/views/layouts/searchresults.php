<h2>検索結果一覧</h2>
<a href="index.php">戻る</a><br />
<table border="1">
    <tr>
        <th>投稿ID</th> 
        <th>投稿日時</th>
        <th>名前</th>
        <th>本文</th>
        <th>レス数</th>
        <th>編集リンク</th>
        <th>詳細リンク</th>
    </tr>
    <?php foreach ($searched_posts as $key => $searched_post) : ?>
    <tr>
        <td>
        <?php echo h($searched_post['id']) ?>
        </td>
        <td>
        <?php echo h($searched_post['created_at']) ?>
        </td>
        <td>
        <?php if (isset($searched_post['user_id'])) : ?>
            <?php echo h($user_names[$searched_post['user_id']]) ?>
        <?php else : ?>
            <?php echo h($searched_post['name']) ?>
        <?php endif ?>
        </td>
        <td>
            <font color="<?php echo $searched_post['color'] ?>">
                <?php echo h($searched_post['comment']) ?>
            </font>
        </td>
        <td>          
            <?php echo (isset($reply_counts[$searched_post['id']])) ? $reply_counts[$searched_post['id']] : 0 ?>件
        </td>        
        <td>
            <input type="button" id="btn" value="編集" class="show-modal" data-key="<?php echo $key; ?>">
        </td>
        <td>
            <a href="postdetail.php?id=<?php echo $searched_post['id'] ?>">投稿詳細</a>
        </td>
    </tr>
    <?php endforeach ?>
</table>
<!--ページング処理-->
<?php if ($pager->hasPreviousPage()) : ?>
    <a href="?name=<?php echo $_GET['name'] ?>&comment=<?php echo $_GET['comment'] ?>&page=<?php echo $pager->getPreviousPage() ?>&color=<?php echo $_GET['color'] ?>">前へ</a>
<?php endif ?>

<?php foreach ($pager->getPageNumbers() as $i) : ?>
    <?php if ($i === $pager->getCurrentPage()) : ?>
        <span>
            <?php echo $i ?>
        </span>
    <?php else : ?>
        <a href="?name=<?php echo $_GET['name'] ?>&comment=<?php echo $_GET['comment'] ?>&page=<?php echo $i ?>&color=<?php echo $_GET['color'] ?>">
            <?php echo $i ?>
        </a>
    <?php endif ?>
<?php endforeach ?>

<?php if ($pager->hasNextPage()) : ?>           
    <a href="?name=<?php echo $_GET['name'] ?>&comment=<?php echo $_GET['comment'] ?>&page=<?php echo $pager->getNextPage() ?>&color=<?php echo $_GET['color'] ?>">次へ</a>
<?php endif ?>
<!--ここまで-->