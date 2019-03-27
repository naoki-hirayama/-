<?php

class UserRepository
{
    protected $database;
    
    public function __construct($database)
    {
        $this->database = $database;
    }
    
    public function register()
    {
        $sql = 'INSERT INTO users (name,login_id,password) VALUES (:name,:login_id,:password)';

        $statement = $this->database->prepare($sql);
        
        $statement->bindParam(':name', $this->name);
        $statement->bindParam(':login_id', $this->login_id);
        $statement->bindParam(':password', $this->password_hash);
        
        $statement->execute();
        return $this->database->lastInsertId();
    }
    
    public function edit()
    {
        $sql = 'UPDATE users SET name = :name, login_id = :login_id, picture = :picture, comment = :comment WHERE id = :id';
        
        $statement = $this->database->prepare($sql);
        
        $statement->bindParam(':id', $this->id);
        $statement->bindParam(':name', $this->name);
        $statement->bindParam(':login_id', $this->login_id);
        $statement->bindParam(':picture', $this->picture);
        $statement->bindParam(':comment', $this->comment);
        
        return $statement->execute();
        
    }
    
    //追加　セレクト
    function fetch_user_by_id($id, $database)
    {
        $sql = 'SELECT * FROM users WHERE id = :id';
        
        $statement = $database->prepare($sql);
        
        $statement->bindParam(':id', $id);
        
        $statement->execute();
        
        return $statement->fetch(PDO::FETCH_ASSOC);
    }
    
    public function validate()
    {
        $errors = [];
        $name = trim(mb_convert_kana($_POST['name'], 's'));
        if (mb_strlen($name, 'UTF-8') === 0) {
            $errors[] = "名前は入力必須です。";
        } else if (mb_strlen($name, 'UTF-8') > 10) {
            $errors[] = "名前は１０文字以内です。";
        }
        $login_id = trim(mb_convert_kana($_POST['login_id'], 's'));
        if ($user_info['login_id'] !== $login_id) {    
            if (mb_strlen($login_id, 'UTF-8') === 0) {
                $errors[] = "ログインIDは入力必須です。";
            } else if (!preg_match("/^[a-zA-Z0-9]+$/", $login_id)) {
                $errors[] = "ログインIDは半角英数字です。";
            } else if (mb_strlen($login_id, 'UTF-8') < 4) {
                $errors[] = "ログインIDは4文字以上です。";
            } else if (mb_strlen($login_id, 'UTF-8') > 15) {
                $errors[] = "ログインIDは15文字以内です。";
            } else {
                $sql = 'SELECT * FROM users WHERE login_id = BINARY :login_id';
                $statement = $database->prepare($sql);
                $statement->bindParam(':login_id', $login_id);
                $statement->execute();
                $tmp_user = $statement->fetch();
                $errors = [];
                
                if ($tmp_user !== false) { 
                    $errors[] = "このログインIDはすでに存在します。";
                }
            }
        }
        if (strlen($_FILES['picture']['name']) !== 0) {
            if ($_FILES['picture']['error'] === 2) {
                $errors[] = "サイズが".number_format($picture_max_size)."Bを超えています。";
            } else if ($_FILES['picture']['size'] > $picture_max_size) {
                $errors[] = "不正な操作です。";
            } else {
                // 画像ファイルのMIMEタイプチェック
                $posted_picture = $_FILES['picture']['tmp_name'];
                $finfo = new finfo(FILEINFO_MIME_TYPE);
                $picture_type = $finfo->file($posted_picture);
                
                $vaild_picture_types = [
                    'image/png',
                    'image/gif',
                    'image/jpeg'
                ];
                
                if (!in_array($picture_type, $vaild_picture_types)) {
                    $errors[] = "画像が不正です。";
                }
            } 
        }
        $comment = $_POST['comment'];
        if (strlen($comment) !== 0) {
            $_comment = trim(mb_convert_kana($comment, 's'));
            if (mb_strlen($_comment, 'UTF-8') === 0) {
                $errors[] = "本文を正しく入力してください。";
            } else if (mb_strlen($_comment, 'UTF-8') > 50) {
                $errors[] = "本文は50文字以内です。";
            } 
        }
    }
}


$database = db_connect();

$user_repository = new UserRepository($database);

$errors = $user_repository->validate($values);
if (empty($errors)) {
    $user_repository->register($values);
}

