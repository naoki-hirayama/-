<?php
    $header_title = '投稿詳細';
    include('../admin/views/layouts/header.php');
?>
    <h1>投稿詳細</h1>
    <ul>
        <li>
            投稿ID:
            <?php echo $post['id'] ?><br />
            名前：
            <?php if (!empty($post['user_id'])) : ?>
                <?php echo h($current_user_name['name']); ?><br />
            <?php else : ?>
                <?php echo h($post['name']) ?><br />
            <?php endif ?>
            本文 :
            <font color="<?php echo h($post['color']) ?>">
                <?php echo h($post['comment']) ?>
            </font><br />
            画像：
            <?php if (!empty($post['picture'])) : ?>
                <img src="../images/posts/<?php echo h($post['picture']) ?>" width="300" height="200"><br />
            <?php else : ?>
                なし<br />
            <?php endif ?>
            時間：
            <?php echo h($post['created_at']) ?><br />
            ---------------------------------------------<br />
        </li>
    </ul>
    <input type="button" value="投稿削除" id="delete">
    <input type="hidden" value="<?php echo $post['id'] ?>" name="post_id">
    <input type="button" value="投稿編集">
    <h2>レス一覧</h2>
    <table border="2">
        <tr>
            <th>ID</th>
            <th>投稿日時</th>
            <th>名前</th>
            <th>本文</th>
            <th>編集リンク</th>
            <th>削除ボタン</th>
        </tr>
        <?php foreach ($reply_posts as $reply_post) : ?>
        <tr>
            <td>
                <?php echo $reply_post['id'] ?>
            </td>
            <td>
                <?php echo h($reply_post['created_at']) ?>
            </td>
            <?php if (isset($reply_post['user_id']) && isset($users)) : ?>
            <td>
                <?php echo h($user_names_are_key_as_user_ids[$reply_post['user_id']]) ?>
            </td>
            <?php else : ?>
            <td>
                <?php echo h($reply_post['name']) ?>
            </td>
            <?php endif ?>
            <td>
                <font color="<?php echo $reply_post['color'] ?>">
                    <?php echo h($reply_post['comment']) ?>
                </font>
            </td>
            <td>
                <input type="button" value="編集">
            </td>
            <td>
                <input type="button" value="削除" id="delete_reply">
                <input type="hidden" value="<?php echo $reply_post['id'] ?>" name="reply_id">
            </td>
        </tr>
        <?php endforeach ?>
    </table>
<?php
    include('../admin/views/layouts/footer.php');
?>