<?php

require_once('BaseRepository.php');

class PostRepository extends BaseRepository
{
    const SELECT_COLOR_OPTIONS = ['black'=>'黒', 'red'=>'赤', 'blue'=>'青', 'yellow'=>'黄', 'green'=>'緑'];
    const MAX_PASSWORD_LENGTH  = 15;
    const MIN_PASSWORD_LENGTH  = 4;
    const MAX_NAME_LENGTH      = 10;
    const MAX_COMMENT_LENGTH    = 100;
    const MAX_PICTURE_SIZE     = 1*1024*1024;
    
    
    public function create($values, $id = null)
    {   
        $values = $this->trimValues($values);
        if ($values['picture']['error'] === UPLOAD_ERR_OK) {
            
            $posted_picture = $values['picture']['tmp_name'];
            $finfo = new finfo(FILEINFO_MIME_TYPE);
            $picture_type = $finfo->file($posted_picture);
            $specific_num = uniqid(mt_rand()); 
            $rename_file = $specific_num.'.'.basename($picture_type);
            $rename_file_path = 'images/'.$rename_file;
            move_uploaded_file($values['picture']['tmp_name'], $rename_file_path);
            
            $values['picture']['name'] = $rename_file;
        } else {
            $values['picture']['name'] = null;
        }
        //パスワードが入力されない時の処理
        if (strlen($values['password']) === 0) {
            $values['password'] = null;
        }
        
        $values['user_id'] = $id;
        
        $sql = 'INSERT INTO posts (name,comment,color,password,picture,user_id) VALUES (:name,:comment,:color,:password,:picture,:user_id)';
        
        $statement = $this->database->prepare($sql);
        
        $statement->bindParam(':name', $values['name']);
        $statement->bindParam(':comment', $values['comment']);
        $statement->bindParam(':color', $values['color']);
        $statement->bindParam(':password', $values['password']);
        $statement->bindParam(':picture', $values['picture']['name']);
        $statement->bindParam(':user_id', $values['user_id']);
        
        $statement->execute();
    }
    
    public function delete($id)
    {   
        parent::delete($id);
        // 投稿に画像がある時
        if (isset($post['picture'])) {
            unlink("images/{$post['picture']}");
        }
    }
    
    public function fetchByOffSetAndPerPageRecords($offset, $per_page_records)
    {
        $sql = 'SELECT * FROM posts ORDER BY created_at DESC LIMIT :offset, :per_page_records';
        $statement = $this->database->prepare($sql);
        
        $statement->bindParam(':offset', $offset, PDO::PARAM_INT);
        $statement->bindParam(':per_page_records', $per_page_records, PDO::PARAM_INT);
        
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function validate($values)
    {   
        $errors = [];
        if (empty($values)) {
            $errors [] = 'エラーが発生しました。画像が大きすぎます。';
        } else {
            $values = $this->trimValues($values);
            if (isset($values['name'])) {
                if (mb_strlen($values['name'], 'UTF-8') === 0) {
                    $errors[] = "名前は入力必須です。";
                } else {
                    if (mb_strlen($values['name'], 'UTF-8') > self::MAX_NAME_LENGTH) {
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
                if (!array_key_exists($values['color'], self::SELECT_COLOR_OPTIONS)) {
                    $errors[] = "文字色が不正です"; 
                }
            }
                
            if (isset($values['password'])) {
                if (strlen($values['password']) !== 0) {
                    if (!preg_match("/^[a-zA-Z0-9]+$/", $values['password'])) {
                        $errors[] = " パスワードは半角英数字です。";
                    }
                    if (mb_strlen($values['password'], 'UTF-8') < self::MIN_PASSWORD_LENGTH) {
                        $errors[] = " パスワードは".self::MIN_PASSWORD_LENGTH."文字以上です。";
                    }
                    if (mb_strlen($values['password'], 'UTF-8') > self::MAX_PASSWORD_LENGTH) {
                        $errors[] = "パスワードが長すぎます。";
                    }    
                }
            }
            
            if (isset($values['comment'])) {
                if (mb_strlen($values['comment'], 'UTF-8') === 0) {
                    $errors[] = "本文は入力必須です。";
                } else {
                    if (mb_strlen($values['comment'], 'UTF-8') > self::MAX_COMMENT_LENGTH) {
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
            $values['name'] = trim(mb_convert_kana($values['name'], 's'));
        }
        
        if (isset($values['password'])) {
            $values['password'] = trim(mb_convert_kana($values['password'], 's'));
        }
        
        if (isset($values['comment'])) {
            $values['comment'] = trim(mb_convert_kana($values['comment'], 's'));
        }
        
        return $values;
    }
}