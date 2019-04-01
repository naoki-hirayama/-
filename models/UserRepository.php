<?php
require_once('BaseRepository.php');

class UserRepository extends BaseRepository
{   
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
        $user = $this->fetchById($id);
        
        if ($values['picture']['error'] === UPLOAD_ERR_OK) {
         
            if (empty($user['picture'])) {
                $values['picture']['name'] = $this->reNameFileAndMoveUpLoadFile($values);
            } else {
                $values['picture']['name'] = $this->reNameFileAndMoveUpLoadFile($values);
                unlink("userimages/{$user['picture']}"); 
            }
            
        } else {
            $values['picture']['name'] = isset($user['picture']) ? $user['picture'] : null;
        }
        
        if (empty($values['comment'])) {
            $values['comment'] = null;
        }
        
        $sql = 'UPDATE users SET name = :name, login_id = :login_id, picture = :picture, profile_comment = :profile_comment WHERE id = :id';
        
        $statement = $this->database->prepare($sql);
        
        $statement->bindParam(':id', $id);
        $statement->bindParam(':name', $values['name']);
        $statement->bindParam(':login_id', $values['login_id']);
        $statement->bindParam(':picture', $values['picture']['name']);
        $statement->bindParam(':profile_comment', $values['profile_comment']);
        
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
            $errors[] = "現在のパスワードが間違っています。";
        } else {
            if (mb_strlen($values['new_password'], 'UTF-8') === 0) {
                $errors[] = "パスワードは入力必須です。";
            } else {
                if (!preg_match("/^[a-zA-Z0-9]+$/", $values['new_password'])) {
                    $errors[] = "パスワードは半角英数字です。";
                } else if (mb_strlen($values['new_password'], 'UTF-8') < self::MIN_PASSWORD_LENGTH) {
                    $errors[] = "パスワードは" . self::MIN_PASSWORD_LENGTH . "文字以上です。";
                } else if (mb_strlen($values['new_password'], 'UTF-8') > self::MAX_PASSWORD_LENGTH) {
                    $errors[] = "パスワードが長すぎます。";
                } else if ($values['new_password'] !== $values['confirm_password']) {
                    $errors[] = "確認用パスワードが一致しません。";
                }
            }
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
        
        return $statement->fetchAll(PDO::FETCH_ASSOC);
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
        
        return $statement->fetch(PDO::FETCH_ASSOC);
    }
}
