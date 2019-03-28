<?php

class UserRepository
{   
    protected $database;
    
    const MAX_PASSWORD_LENGTH = 30;
    const MIN_PASSWORD_LENGTH = 4;
    const MAX_NAME_LENGTH     = 10;
    const NIN_NAME_LENGTH     = 1;
    const MAX_LOGIN_ID_LENGTH = 15;
    const MIN_LOGIN_ID_LENGTH = 4;
    const MAX_COMMENT_LENGTH  = 50;
    const MIN_COMMENT_LENGTH  = 0;
    const MAX_PICTURE_SIZE    = 1*1024*1024;
    
    public function __construct($database)
    {
        $this->database = $database;
    }
    
    public function getMaxPictureSize()
    {
        return self::MAX_PICTURE_SIZE;
    }
    
    public function register($values)
    {
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
        $user = $this->fetchById($id);
        
        $posted_picture = $values['picture']['tmp_name'];
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $picture_type = $finfo->file($posted_picture);
        
        $specific_num = uniqid(mt_rand()); 
        $rename_file = $specific_num.'.'.basename($picture_type);
        $rename_file_path = 'userimages/'.$rename_file;
        move_uploaded_file($values['picture']['tmp_name'], $rename_file_path);
        
        if (strlen($values['picture']['name']) === 0 && empty($user['picture'])) {
            $values['picture']['name'] = null;
        } else if (strlen($values['picture']['name']) !== 0 && empty($user['picture'])) {
            $values['picture']['name'] = $rename_file;
        } else if (strlen($values['picture']['name']) !== 0 && !empty($user['picture'])) {
            $values['picture']['name'] = $rename_file;
            unlink("userimages/{$user['picture']}");
        } else {
            $values['picture']['name'] = $user['picture'];
        }
        
        if ($values['comment'] === 0) {
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
        $user = $this->fetchById($id);
        $errors = [];
        if (!password_verify($values['current_password'],$user['password'])) {
            $errors[] = "パスワードが間違っています。";
        }
        $trim_new_password = trim(mb_convert_kana($values['new_password'], 's'));
        if (mb_strlen($trim_new_password, 'UTF-8') === 0) {
            $errors[] = "パスワードは入力必須です。";
        } else if (!preg_match("/^[a-zA-Z0-9]+$/", $trim_new_password)) {
            $errors[] = "パスワードは半角英数字です。";
        } else if (mb_strlen($trim_new_password, 'UTF-8') < 4) {
            $errors[] = "パスワードは４文字以上です。";
        } else if (mb_strlen($trim_new_password, 'UTF-8') > 30) {
            $errors[] = "パスワードが長すぎます。";
        } else if ($trim_new_password !== $values['confirm_password']) {
            $errors[] = "確認用パスワードが一致しません。";
        }
        if (!empty($errors)) {
            return $errors;
        }
    }
    
    public function fetchByLoginIdAndPassword($login_id, $password)
    {
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
    
        return $statement->fetch(PDO::FETCH_ASSOC);
    }
    
    protected function fetchByLoginId($login_id)
    {
        $sql = 'SELECT * FROM users WHERE login_id = BINARY :login_id';
        
        $statement = $this->database->prepare($sql);
        
        $statement->bindParam(':login_id', $login_id);
        
        $statement->execute();
        
        return $statement->fetch();
    }
    
    public function validate($values, $id = null)
    {
        $errors = [];
        if ($values['name'] !== null) {
            $name = trim(mb_convert_kana($values['name'], 's'));
            if (mb_strlen($name, 'UTF-8') === 0) {
                $errors[] = "名前は入力必須です。";
            } else if (mb_strlen($name, 'UTF-8') > self::MAX_NAME_LENGTH) {
                $errors[] = "名前は".self::MAX_NAME_LENGTH."文字以内です。";
            }
        }
        $user = $this->fetchById($id);
        if ($values['login_id'] !== null) {
            $login_id = trim(mb_convert_kana($values['login_id'], 's'));
            if ($user['login_id'] !== $login_id) {
                if (mb_strlen($login_id, 'UTF-8') === 0) {
                    $errors[] = "ログインIDは入力必須です。";
                } else if (!preg_match("/^[a-zA-Z0-9]+$/", $login_id)) {
                    $errors[] = "ログインIDは半角英数字です。";
                } else if (mb_strlen($login_id, 'UTF-8') < self::MIN_LOGIN_ID_LENGTH) {
                    $errors[] = "ログインIDは".self::MIN_LOGIN_ID_LENGTH."文字以上です。";
                } else if (mb_strlen($login_id, 'UTF-8') > self::MAX_LOGIN_ID_LENGTH) {
                    $errors[] = "ログインIDは".self::MAX_LOGIN_ID_LENGTH."文字以内です。";
                } else {
                    $tmp_user = $this->fetchByLoginId($login_id);
                    if ($tmp_user !== false) { 
                        $errors[] = "このログインIDはすでに存在します。";
                    }
                }
            }
        }
        
        if ($values['password'] !== null) {
            $password = trim(mb_convert_kana($values['password'], 's'));
            if (mb_strlen($password, 'UTF-8') === 0) {
                $errors[] = "パスワードは入力必須です。";
            } else if (!preg_match("/^[a-zA-Z0-9]+$/", $password)) {
                $errors[] = "パスワードは半角英数字です。";
            } else if (mb_strlen($password, 'UTF-8') < self::MIN_PASSWORD_LENGTH) {
                $errors[] = "パスワードは".self::MIN_PASSWORD_LENGTH."文字以上です。";
            } else if (mb_strlen($password, 'UTF-8') > self::MAX_PASSWORD_LENGTH) {
                $errors[] = "パスワードが長すぎます。";
            } else if ($password !== $values['confirm_password']) {
                $errors[] = "パスワードが一致しません。";
            } 
        }
        
        if ($values['picture'] !== null) {
            if (strlen($values['picture']['name']) !== 0) {
                if ($values['picture']['error'] === 2) {
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
        }
        
        if ($values['picture'] !== null) {
            if (strlen($values['comment']) !== self::MIN_COMMENT_LENGTH) {
                $comment = trim(mb_convert_kana($values['comment'], 's'));
                if (mb_strlen($comment, 'UTF-8') === self::MIN_COMMENT_LENGTH) {
                    $errors[] = "本文を正しく入力してください。";
                } else if (mb_strlen($omment, 'UTF-8') > self::MAX_COMMENT_LENGTH) {
                    $errors[] = "本文は".MAX_COMMENT_LENGTH."文字以内です。";
                } 
            }
        } 
        
        return $errors;
    }
    
}