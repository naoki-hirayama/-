<?php

require_once('BaseRepository.php');

class ReplyRepository extends BaseRepository
{
    protected $table_name = 'replies';
    
    const MAX_PASSWORD_LENGTH  = 15;
    const MIN_PASSWORD_LENGTH  = 4;
    const MAX_NAME_LENGTH      = 10;
    const MAX_COMMENT_LENGTH   = 50;
    const MAX_PICTURE_SIZE     = 1*1024*1024;
    
    public static function getSelectColorOptions()
    {
        return ['black'=>'黒', 'red'=>'赤', 'blue'=>'青', 'yellow'=>'黄', 'green'=>'緑'];
    }
    
    public function create($post_id, $values, $user_id = null)
    {   
        $values = $this->trimValues($values);
        if ($values['picture']['error'] === UPLOAD_ERR_OK) {
            
            $posted_picture = $values['picture']['tmp_name'];
            $finfo = new finfo(FILEINFO_MIME_TYPE);
            $picture_type = $finfo->file($posted_picture);
            $specific_num = uniqid(mt_rand()); 
            $rename_file = $specific_num.'.'.basename($picture_type);
            $rename_file_path = 'replyimages/'.$rename_file;
            move_uploaded_file($values['picture']['tmp_name'], $rename_file_path);
            
            $values['picture']['name'] = $rename_file;
        } else {
            $values['picture']['name'] = null;
        }
        //パスワードが入力されない時の処理
        if ($this->getStringLength($values['password']) === 0) {
            $values['password'] = null;
        }
        
        $values['user_id'] = $user_id;
        
        $sql = 'INSERT INTO replies (name,comment,color,password,picture,user_id,post_id) VALUES (:name,:comment,:color,:password,:picture,:user_id,:post_id)';
        
        $statement = $this->database->prepare($sql);
        
        $statement->bindParam(':name', $values['name']);
        $statement->bindParam(':comment', $values['comment']);
        $statement->bindParam(':color', $values['color']);
        $statement->bindParam(':password', $values['password']);
        $statement->bindParam(':picture', $values['picture']['name']);
        $statement->bindParam(':user_id', $values['user_id']);
        $statement->bindParam(':post_id', $post_id);
        
        $statement->execute();
    }
    
    public function delete($reply_id)
    {   
        $reply_post = $this->fetchById($reply_id);
        
        parent::delete($reply_id);
        
        if (isset($reply_post['picture'])) {
            unlink("replyimages/{$reply_post['picture']}");
        }
    }
    
    public function deleteByPostId($post_id)
    {   
        $replies = $this->fetchByPostId($post_id);
        
        foreach ($replies as $reply) {
            $this->delete($reply['id']);
        }
    }
    
    public function fetchCountByPostIds($post_ids)
    {   
        $sanitized_ids = [];
        foreach ($post_ids as $post_id) {
            $sanitized_ids[] = (int)$post_id; 
        }
        $sql = "SELECT post_id, COUNT(*) AS cnt FROM replies WHERE post_id IN (".implode(',', $sanitized_ids).") GROUP BY post_id";
        
        //$sql = "SELECT post_id, COUNT(*) AS cnt FROM (SELECT post_id FROM replies WHERE post_id IN (".implode(',', $sanitized_ids).") GROUP BY post_id) A";
        $statement = $this->database->query($sql);
        
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function fetchCountByPostId($post_id)
    {   
        $sanitized_id = (int)$post_id;
        $sql = "SELECT COUNT(*) AS CNT FROM replies WHERE post_id = {$sanitized_id}";
        
        $statement = $this->database->query($sql);
        
        return $statement->fetchColumn();
    }
    
    public function fetchByPostId($post_id)
    {
        $sql = 'SELECT * FROM replies WHERE post_id = :post_id ORDER BY created_at DESC';
        
        $statement = $this->database->prepare($sql);
        
        $statement->bindParam(':post_id', $post_id);
        
        $statement->execute();
        
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function validate($values)
    {   
        $errors = [];
        if (empty($values)) {
            $errors [] = "エラーが発生しました。画像が大きすぎます。";
        } else {
            $values = $this->trimValues($values);
            if (isset($values['name'])) {
                if ($this->getStringLength($values['name']) === 0) {
                    $errors[] = "名前は入力必須です。";
                } else {
                    if ($this->getStringLength($values['name']) > self::MAX_NAME_LENGTH) {
                        $errors[] = "名前は".self::MAX_NAME_LENGTH."文字以内です。";
                    }
                }
            }
            
            if (isset($values['picture'])) {
                if (strlen($values['picture']['name']) > 0) {
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
            }
            
            if (isset($values['color'])) {
                if (!array_key_exists($values['color'], self::getSelectColorOptions())) {
                    $errors[] = "文字色が不正です"; 
                }
            }
                
            if (isset($values['password'])) {
                if ($this->getStringLength($values['password']) !== 0) {
                    if (!$this->validateAlphaNumeric($values['password'])) {
                        $errors[] = "パスワードは半角英数字です。";
                    }
                    if ($this->getStringLength($values['password']) < self::MIN_PASSWORD_LENGTH) {
                        $errors[] = "パスワードは".self::MIN_PASSWORD_LENGTH."文字以上です。";
                    }
                    if ($this->getStringLength($values['password']) > self::MAX_PASSWORD_LENGTH) {
                        $errors[] = "パスワードが長すぎます。";
                    }
                }
            }
            
            if (isset($values['comment'])) {
                if ($this->getStringLength($values['comment']) === 0) {
                    $errors[] = "本文は入力必須です。";
                } else {
                    if ($this->getStringLength($values['comment']) > self::MAX_COMMENT_LENGTH) {
                        $errors[] = "本文は".self:: MAX_COMMENT_LENGTH."文字以内です。";
                    }    
                } 
            }
            
        }
        return $errors;
    }
    
    protected function trimValues($values)
    {
        if (isset($values['name'])) {
            $values['name'] = $this->trimString($values['name']);
        }
        
        if (isset($values['password'])) {
            $values['password'] = $this->trimString($values['password']);
        }
        
        if (isset($values['comment'])) {
            $values['comment'] = $this->trimString($values['comment']);
        }
        
        return $values;
    }
}   