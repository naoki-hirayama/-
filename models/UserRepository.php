<?php

class UserRepository
{   
    protected $database;
    
    const MAX_PASSWORD_LENGTH = 30;
    const MIN_PASSWORD_LENGTH = 4;
    const MAX_NAME_LENGTH     = 10;
    const MAX_LOGIN_ID_LENGTH = 15;
    const MIN_LOGIN_ID_LENGTH = 4;
    const MAX_COMMENT_LENGTH  = 50;
    const MAX_PICTURE_SIZE    = 1*1024*1024;
    
    public function __construct($database)
    {
        $this->database = $database;
    }
    
    public function register($values)
    {   
        $values = $this->trimValues($values);
        $password_hash = password_hash($values['password'], PASSWORD_DEFAULT);
        
        $sql = 'INSERT INTO users (name,login_id,password) VALUES (:name,:login_id,:password)';

        $statement = $this->database->prepare($sql);
        
        $statement->bindParam(':name', $values['name']);
        $statement->bindParam(':login_id', $values['login_id']);
        $statement->bindParam(':password', $password_hash);
        
        $statement->execute();
        
        return $this->database->lastInsertId();
    }
    
    public function edit($id, $values)
    {
        $values = $this->trimValues($values);
        
        if ($values['picture']['error'] !== UPLOAD_ERR_NO_FILE) {
         
            $posted_picture = $values['picture']['tmp_name'];
            
            $finfo = new finfo(FILEINFO_MIME_TYPE);
            
            $picture_type = $finfo->file($posted_picture);
            
            $specific_num = uniqid(mt_rand()); 
            
            $rename_file = $specific_num.'.'.basename($picture_type);
            
            $rename_file_path = 'userimages/'.$rename_file;
            
            move_uploaded_file($values['picture']['tmp_name'], $rename_file_path);
        }  
        
        $user = $this->fetchById($id);
        if ($values['picture']['error'] === UPLOAD_ERR_OK && empty($user['picture'])) {
            $values['picture']['name'] = $rename_file;
        } else if ($values['picture']['error'] === UPLOAD_ERR_OK && !empty($user['picture'])) {
            $values['picture']['name'] = $rename_file;
            unlink("userimages/{$user['picture']}");
        } else if ($user['picture']) {
            $values['picture']['name'] = $user['picture'];
        } else{
            $values['picture']['name'] = null;
        }
    
        
        if (!isset($values['comment'])) {
            $values['comment'] = null;
        }
        
        $sql = 'UPDATE users SET name = :name, login_id = :login_id, picture = :picture, comment = :comment WHERE id = :id';
        
        $statement = $this->database->prepare($sql);
        
        $statement->bindParam(':id', $id);
        $statement->bindParam(':name', $values['name']);
        $statement->bindParam(':login_id', $values['login_id']);
        $statement->bindParam(':picture', $values['picture']['name']);
        $statement->bindParam(':comment', $values['comment']);
        
        $statement->execute();
    }
    
    public function changePassword($id, $values)
    {   
        $values = $this->trimValues($values);
        
        $password_hash = password_hash($values['new_password'], PASSWORD_DEFAULT);
        
        $sql = 'UPDATE users SET password = :password WHERE id = :id';
        
        $statement = $this->database->prepare($sql);
        
        $statement->bindParam(':id', $id);
        $statement->bindParam(':password', $password_hash);
        
        $statement->execute();
    }
    
    public function validateChangePassword($id, $values)
    {
        // エラーがあったらメッセージを配列で返す。無ければ返り値なし
        $values = $this->trimValues($values);
        $user = $this->fetchById($id);
        $errors = [];

        if (!password_verify($values['current_password'], $user['password'])) {
            $errors[] = "パスワードが間違っています。";
        }
        if (mb_strlen($values['new_password'], 'UTF-8') === 0) {
            $errors[] = "パスワードは入力必須です。";
        } else if (!preg_match("/^[a-zA-Z0-9]+$/", $values['new_password'])) {
            $errors[] = "パスワードは半角英数字です。";
        } else if (mb_strlen($values['new_password'], 'UTF-8') < self::MIN_PASSWORD_LENGTH) {
            $errors[] = "パスワードは" . self::MIN_PASSWORD_LENGTH . "文字以上です。";
        } else if (mb_strlen($values['new_password'], 'UTF-8') > self::MAX_PASSWORD_LENGTH) {
            $errors[] = "パスワードが長すぎます。";
        } else if ($values['new_password'] !== $values['confirm_password']) {
            $errors[] = "確認用パスワードが一致しません。";
        }

        if (!empty($errors)) {
            return $errors;
        }
    }
    
    public function fetchByLoginIdAndPassword($login_id, $password)
    {   //トリムするべきか？
        $user = $this->fetchByLoginId($login_id);
        
        if ($user !== false) {
            if (!password_verify($password, $user['password'])) {
                $user = false;
            }
        }
        
        return $user;
    }
    
    public function fetchByIds($ids)
    {
        $sql = 'SELECT * FROM users WHERE id IN ('.$ids.')';
        
        $statement = $this->database->prepare($sql);
        
        $statement->execute();
        
        return $statement->fetchAll();
    }
    
    public function fetchById($id)
    {
        $sql = 'SELECT * FROM users WHERE id = :id';
    
        $statement = $this->database->prepare($sql);
        
        $statement->bindParam(':id', $id);
        
        $statement->execute();
    
        return $statement->fetch();
    }
    
    public function validate($values, $id = null)
    {
        $values = $this->trimValues($values);
        
        $errors = [];

        if (isset($values['name'])) {
            if (mb_strlen($values['name'], 'UTF-8') === 0) {
                $errors[] = "名前は入力必須です。";
            } else if (mb_strlen($values['name'], 'UTF-8') > self::MAX_NAME_LENGTH) {
                $errors[] = "名前は".self::MAX_NAME_LENGTH."文字以内です。";
            }
        }

        if (isset($values['login_id'])) {
            $user = $this->fetchById($id);
            $tmp_user = $this->fetchByLoginId($values['login_id']);
            if (mb_strlen($values['login_id'], 'UTF-8') === 0) {
                $errors[] = "ログインIDは入力必須です。";
            } else if (!preg_match("/^[a-zA-Z0-9]+$/", $values['login_id'])) {
                $errors[] = "ログインIDは半角英数字です。";
            } else if (mb_strlen($values['login_id'], 'UTF-8') < self::MIN_LOGIN_ID_LENGTH) {
                $errors[] = "ログインIDは".self::MIN_LOGIN_ID_LENGTH."文字以上です。";
            } else if (mb_strlen($values['login_id'], 'UTF-8') > self::MAX_LOGIN_ID_LENGTH) {
                $errors[] = "ログインIDは".self::MAX_LOGIN_ID_LENGTH."文字以内です。";
            } else if ($tmp_user !== false && is_null($id)) {
                $errors[] = "このログインIDはすでに存在します。";
            } else if ($user['login_id'] !== $tmp_user['login_id'] && $tmp_user['login_id'] === $values['login_id']) {
                $errors[] = "このログインIDはすでに存在します。";
            }
        }
        
        if (isset($values['password'])) {
            if (mb_strlen($values['password'], 'UTF-8') === 0) {
                $errors[] = "パスワードは入力必須です。";
            } else if (!preg_match("/^[a-zA-Z0-9]+$/", $values['password'])) {
                $errors[] = "パスワードは半角英数字です。";
            } else if (mb_strlen($values['password'], 'UTF-8') < self::MIN_PASSWORD_LENGTH) {
                $errors[] = "パスワードは".self::MIN_PASSWORD_LENGTH."文字以上です。";
            } else if (mb_strlen($values['password'], 'UTF-8') > self::MAX_PASSWORD_LENGTH) {
                $errors[] = "パスワードが長すぎます。";
            } else if ($values['password'] !== $values['confirm_password']) {
                $errors[] = "パスワードが一致しません。";
            } 
        }
        
        if (isset($values['picture']) && $values['picture']['error'] !== UPLOAD_ERR_NO_FILE) {
           if ($values['picture']['error'] === UPLOAD_ERR_FORM_SIZE) {
               $errors[] = "サイズが".number_format(self::MAX_PICTURE_SIZE)."MBを超えています。";
           } else if ($values['picture']['size'] > self::MAX_PICTURE_SIZE) {
               $errors[] = "不正な操作です。";
           } else {
               // 画像ファイルのMIMEタイプチェック
               $posted_picture = $values['picture']['tmp_name'];
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
        
        if (isset($values['comment'])) {
            if (mb_strlen($values['comment'], 'UTF-8') > self::MAX_COMMENT_LENGTH) {
                $errors[] = "本文は".self::MAX_COMMENT_LENGTH."文字以内です。";
            }
        } 
        
        return $errors;
    }
    
    protected function trimValues($values)
    {   
        if (isset($values['name'])) {
            $values['name'] = trim(mb_convert_kana($values['name'], 's'));
        }
        
        if (isset($values['login_id'])) {
            $values['login_id'] = trim(mb_convert_kana($values['login_id'], 's'));
        }
        
        if (isset($values['password'])) {
            $values['password'] = trim(mb_convert_kana($values['password'], 's'));
        }
        
        if (isset($values['new_password'])) {
            $values['new_password'] = trim(mb_convert_kana($values['new_password'], 's'));
        }
        
        if (isset($values['confirm_password'])) {
            $values['confirm_password'] = trim(mb_convert_kana($values['confirm_password'], 's'));
        }
        
        if (isset($values['current_password'])) {
           $values['current_password'] = trim(mb_convert_kana($values['current_password'], 's'));
        }
        
        if (isset($values['comment'])) {
            $values['comment'] = trim(mb_convert_kana($values['comment'], 's'));
        }
        
        return $values;
    }

    protected function fetchByLoginId($login_id)
    {
        $sql = 'SELECT * FROM users WHERE login_id = BINARY :login_id';
        
        $statement = $this->database->prepare($sql);
        
        $statement->bindParam(':login_id', $login_id);
        
        $statement->execute();
        
        return $statement->fetch();
    }
}
