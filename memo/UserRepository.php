<?php

class UserRepository
{   
    //同じ処理を書かない　一回の変更で済むように！
    protected $database;
    protected $password_lengthes = ['max' => 30, 'min' => 4];
    protected $name_lengthes = ['max' => 10, 'min' => 1];
    protected $login_id_lengthes = ['max' => 15, 'min' => 4];
    protected $comment_lengthes = ['max' => 50, 'min' => 0];
    private $picture_max_size = 1*1024*1024;
    
    
    public function __construct($database)
    {
        $this->database = $database;
    }
    
    public function register($name, $login_id, $password_hash )//$values 
    {
        //インサートされたuseridを返す
        $sql = 'INSERT INTO users (name,login_id,password) VALUES (:name,:login_id,:password)';

        $statement = $this->database->prepare($sql);
        
        $statement->bindParam(':name', $name);
        $statement->bindParam(':login_id', $login_id);
        $statement->bindParam(':password', $password_hash);
        
        $statement->execute();
        
        return $this->database->lastInsertId();
    }
    
    public function edit($id, //$values $name, $login_id, $picture, $comment)
    {
        //$valies['picture'] = $_FILES['picture'];
        $sql = 'UPDATE users SET name = :name, login_id = :login_id, picture = :picture, comment = :comment WHERE id = :id';
        
        $statement = $this->database->prepare($sql);
        
        $statement->bindParam(':id', $id);
        $statement->bindParam(':name', $name);
        $statement->bindParam(':login_id', $login_id);
        $statement->bindParam(':picture', $picture);
        $statement->bindParam(':comment', $comment);
        
        //$statement->execute();引数なし
    }
    //password.phpのバリデーション
    public function validateChangePassword($id, $current_password, $new_password, $confirm_password)
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
        }
        //     return password_hash($trim_new_password, PASSWORD_DEFAULT);
        // }
    }
    
    // public function editPassword($id, $password_hash)
    // {
    //     $sql = 'UPDATE users SET password = :password WHERE id = :id';
            
    //     $statement = $this->database->prepare($sql);
        
    //     $statement->bindParam(':id', $id);
    //     $statement->bindParam(':password', $password_hash);
        
    //     return $statement->execute();
    // }
    
    public function fetchby($login_id, $password)
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
                $errors[] = "サイズが".number_format($this->getaxPictureSize())."MBを超えています。";
            } else if ($_FILES['picture']['size'] > $this->getmaxPictureSize()) {
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
    //画像ファイルの大きさ
    public function setMaxPictureSize($picture_max_size)
    {
        $this->picture_max_size = $picture_max_size;
    }
    
    public function getMaxPictureSize()
    {
        return $this->picture_max_size;
    }
    //パスワードの長さ
    public function setPasswordSize($max_password, $min_password)
    {   
        $password_lengthes = [];
        $password_lengthes['max'] = $max_password;
        $password_lengthes['min'] = $min_password；
    }
    public function getPasswordLengthes()
    {
        return $this->password_lengthes;
    }
    //名前の長さ
    public function setNameLengthes($max_name, $min_name)
    {
        $name_lengthes = [];
        $name_lengthes['max'] = $max_name;
        $name_lengthes['min'] = $min_name;
    }
    public function getNameLengthes()
    {
        return $this->name_lengthes;
    }
    //login_idの長さ
    public function setLoginIdLengthes($max_login_id, $min_login_id)
    {
        $login_id_lengthes = [];
        $login_id_lengthes['max'] = $max_login_id;
        $login_id_lengthes['min'] = $min_login_id;
    }
    public function getLoginIdLengthes()
    {
        return $this->login_id_lengthes;
    }
    //一言コメントの長さ
    public function getCommentLengthes($max_comment, $min_comment)
    {
        $comment_lengthes = [];
        $comment_lengthes['max'] = $max_comment;
        $comment_lengthes['min'] = $min_comment;
    }
    public function setCommentLengthes()
    {
        return $this->comment_lengthes;
    }
    
    
}
