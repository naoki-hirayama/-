<?php
class UserRepository
{
    protected $database;
    
    public function __construct($database)
    {
        $this->database = $database;
    }
    
    public function register($name, $login_id, $password_hash)
    {
        $sql = 'INSERT INTO users (name,login_id,password) VALUES (:name,:login_id,:password)';

        $statement = $this->database->prepare($sql);
        
        $statement->bindParam(':name', $name);
        $statement->bindParam(':login_id', $login_id);
        $statement->bindParam(':password', $password_hash);
        
        $statement->execute();
        
        return $this->database->lastInsertId();
    }
    
    public function edit($id, $name, $login_id, $picture, $comment)
    {
        $sql = 'UPDATE users SET name = :name, login_id = :login_id, picture = :picture, comment = :comment WHERE id = :id';
        
        $statement = $this->database->prepare($sql);
        
        $statement->bindParam(':id', $id);
        $statement->bindParam(':name', $name);
        $statement->bindParam(':login_id', $login_id);
        $statement->bindParam(':picture', $picture);
        $statement->bindParam(':comment', $comment);
        
        return $statement->execute();
    }
    //password.phpのバリデーション
    public function validatePassword($id, $current_password, $new_password, $confirm_password)
    {
        $user = $this->getUserDetailByUserId($id);
        
        $errors = [];
        if (!password_verify($current_password,$user['password'])) {
            $errors[] = "パスワードが間違っています。";
        }
        $trim_new_password = trim(mb_convert_kana($new_password, 's'));
        if (mb_strlen($trim_new_password, 'UTF-8') === 0) {
            $errors[] = "パスワードは入力必須です。";
        } else if (!preg_match("/^[a-zA-Z0-9]+$/", $trim_new_password)) {
            $errors[] = "パスワードは半角英数字です。";
        } else if (mb_strlen($trim_new_password, 'UTF-8') < 4) {
            $errors[] = "パスワードは４文字以上です。";
        } else if (mb_strlen($trim_new_password, 'UTF-8') > 30) {
            $errors[] = "パスワードが長すぎます。";
        } else if ($trim_new_password !== $confirm_password) {
            $errors[] = "確認用パスワードが一致しません。";
        }
        if (!empty($errors)) {
            return $errors;
        } else {
            return password_hash($trim_new_password, PASSWORD_DEFAULT);
        }
            
    }
    
    public function editPassword($id, $password_hash)
    {
        $sql = 'UPDATE users SET password = :password WHERE id = :id';
            
        $statement = $this->database->prepare($sql);
        
        $statement->bindParam(':id', $id);
        $statement->bindParam(':password', $password_hash);
        
        return $statement->execute();
    }
    
    public function login($login_id, $password)
    {
        $sql = 'SELECT * FROM users WHERE login_id = BINARY :login_id';
        
        $statement = $this->database->prepare($sql);
        
        $statement->bindParam(':login_id', $login_id);
        
        $statement->execute();
        
        $user = $statement->fetch();
        
        if ($user === false) {
            return "パスワードまたはログインidが間違っています。";
        } else if (!password_verify($password, $user['password'])) {
            return  "パスワードまたはログインidが間違っています。";
        } else {
            return $user['id'];
        } 
    }
    
    public function getPerPageUsersDetails($ids)
    {
        $sql = 'SELECT * FROM users WHERE id IN ('.$ids.')';
        
        $statement = $this->database->prepare($sql);
        
        $statement->execute();
        
        return $statement->fetchAll();
    }
    
    public function getUserDetailByUserId($id)
    {
        $sql = 'SELECT * FROM users WHERE id = :id';
    
        $statement = $this->database->prepare($sql);
        
        $statement->bindParam(':id', $id);
        
        $statement->execute();
    
        return $statement->fetch(PDO::FETCH_ASSOC);
    }
    
    public function getUserDetailByUserLoginId($login_id)
    {
        $sql = 'SELECT * FROM users WHERE login_id = BINARY :login_id';
        
        $statement = $this->database->prepare($sql);
        
        $statement->bindParam(':login_id', $login_id);
        
        $statement->execute();
        
        return $statement->fetch();
    }
    
    public function validateRegister($name, $login_id, $password, $confirm_password)
    {
        $errors = [];
        
        $_name = trim(mb_convert_kana($name, 's'));
        if (mb_strlen($_name, 'UTF-8') === 0) {
            $errors[] = "名前は入力必須です。";
        } else if (mb_strlen($_name, 'UTF-8') > 10) {
            $errors[] = "名前は１０文字以内です。";
        }
        
        $_login_id = trim(mb_convert_kana($login_id, 's'));
        if (mb_strlen($_login_id, 'UTF-8') === 0) {
            $errors[] = "ログインIDは入力必須です。";
        } else if (!preg_match("/^[a-zA-Z0-9]+$/", $_login_id)) {
            $errors[] = "ログインIDは半角英数字です。";
        } else if (mb_strlen($_login_id, 'UTF-8') < 4) {
            $errors[] = "ログインIDは4文字以上です。";
        } else if (mb_strlen($_login_id, 'UTF-8') > 15) {
            $errors[] = "ログインIDは15文字以内です。";
        } else {
            $tmp_user = $this->getUserDetailByUserLoginId($_login_id);
            if ($tmp_user !== false) { 
                $errors[] = "このログインIDはすでに存在します。";
            }
        }
        
        $_password = trim(mb_convert_kana($password, 's'));
        if (mb_strlen($_password, 'UTF-8') === 0) {
            $errors[] = "パスワードは入力必須です。";
        } else if (!preg_match("/^[a-zA-Z0-9]+$/", $_password)) {
            $errors[] = "パスワードは半角英数字です。";
        } else if (mb_strlen($_password, 'UTF-8') < 4) {
            $errors[] = "パスワードは４文字以上です。";
        } else if (mb_strlen($_password, 'UTF-8') > 30) {
            $errors[] = "パスワードが長すぎます。";
        } else if ($_password !== $confirm_password) {
            $errors[] = "パスワードが一致しません。";
        } 
        return $errors;
    }
    
    public function validateEdit($name, $login_id, $file, $comment, $user_id)
    {   
        $user = $this->getUserDetailByUserId($user_id);
        $errors = [];
        $_name = trim(mb_convert_kana($name, 's'));
        if (mb_strlen($name, 'UTF-8') === 0) {
            $errors[] = "名前は入力必須です。";
        } else if (mb_strlen($_name, 'UTF-8') > 10) {
            $errors[] = "名前は１０文字以内です。";
        }
        $_login_id = trim(mb_convert_kana($login_id, 's'));
        if ($user['login_id'] !== $_login_id) {    
            if (mb_strlen($login_id, 'UTF-8') === 0) {
                $errors[] = "ログインIDは入力必須です。";
            } else if (!preg_match("/^[a-zA-Z0-9]+$/", $_login_id)) {
                $errors[] = "ログインIDは半角英数字です。";
            } else if (mb_strlen($_login_id, 'UTF-8') < 4) {
                $errors[] = "ログインIDは4文字以上です。";
            } else if (mb_strlen($_login_id, 'UTF-8') > 15) {
                $errors[] = "ログインIDは15文字以内です。";
            } else {
                $tmp_user = $this->getUserDetailByUserLoginId($_login_id);
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
        if (strlen($comment) !== 0) {
            $_comment = trim(mb_convert_kana($comment, 's'));
            if (mb_strlen($_comment, 'UTF-8') === 0) {
                $errors[] = "本文を正しく入力してください。";
            } else if (mb_strlen($_comment, 'UTF-8') > 50) {
                $errors[] = "本文は50文字以内です。";
            } 
        }
        return $errors;
    }
    
}
