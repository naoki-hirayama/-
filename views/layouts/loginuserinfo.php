<?php if (isset($user_info['picture'])) : ?>
    <img src="userimages/<?php echo h($user_info['picture']) ?>" width="50" height="50"><br />
<?php endif ?>
<?php if (isset($_SESSION['user_id'])) : ?>
    <P>ようこそ！<?php echo h($user_info['name']) ?>さん(<?php echo h($user_info['login_id']) ?>)</P>
<?php else : ?>
    <P>ようこそ!ゲストさん</P>
<?php endif ?>
