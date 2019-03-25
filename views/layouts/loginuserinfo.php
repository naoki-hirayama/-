<?php if (isset($_SESSION['picture'])) : ?>
    <img src="userimages/<?php echo $_SESSION['picture'] ?>" width="50" height="50"><br />
<?php endif ?>
<?php if (isset($_SESSION['username'])) : ?>
    <P>ようこそ！<?php echo $_SESSION['username'] ?>さん(<?php echo $_SESSION['login_id'] ?>)</P>
<?php else : ?>
    <P>ようこそ!ゲストさん</P>
<?php endif ?>
