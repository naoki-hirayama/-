<?php

class BaseRepository
{
    protected $database;
    
    const SELECT_COLOR_OPTIONS       = ['black'=>'黒', 'red'=>'赤', 'blue'=>'青', 'yellow'=>'黄', 'green'=>'緑'];
    const MAX_PASSWORD_LENGTH        = 30;
    const MIN_PASSWORD_LENGTH        = 4;
    const MAX_DELETE_PASSWORD_LENGTH = 15;
    const MIN_DELETE_PASSWORD_LENGTH = 4;
    const MAX_NAME_LENGTH            = 10;
    const MAX_COMMENT_LENGTH         = 100;
    const MAX_PROFILE_COMMENT_LENGTH = 50;
    const MAX_PICTURE_SIZE           = 1*1024*1024;
    const MAX_LOGIN_ID_LENGTH        = 15;
    const MIN_LOGIN_ID_LENGTH        = 4;
    
    public function __construct($database)
    {
        $this->database = $database;
    }
    
    public function validate($values, $id = null)
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
    
            if (isset($values['login_id'])) {
                if (mb_strlen($values['login_id'], 'UTF-8') === 0) {
                    $errors[] = "ログインIDは入力必須です。";
                } else {
                    if (!preg_match("/^[a-zA-Z0-9]+$/", $values['login_id'])) {
                        $errors[] = "ログインIDは半角英数字です。";
                    } else if (mb_strlen($values['login_id'], 'UTF-8') < self::MIN_LOGIN_ID_LENGTH) {
                        $errors[] = "ログインIDは".self::MIN_LOGIN_ID_LENGTH."文字以上です。";
                    } else if (mb_strlen($values['login_id'], 'UTF-8') > self::MAX_LOGIN_ID_LENGTH) {
                        $errors[] = "ログインIDは".self::MAX_LOGIN_ID_LENGTH."文字以内です。";
                    } else {
                        $tmp_user = $this->fetchByLoginId($values['login_id']);
                        if ($tmp_user !== false) {
                            if (is_null($id)) {
                                $errors[] = "このログインIDはすでに存在します。";
                            } else if ($tmp_user['id'] !== $id) {
                                $errors[] = "このログインIDはすでに存在します。";
                            }
                        } 
                    }
                }
            }
            
            if (isset($values['password'])) {
                if (mb_strlen($values['password'], 'UTF-8') === 0) {
                    $errors[] = "パスワードは入力必須です。";
                } else {
                    if (!preg_match("/^[a-zA-Z0-9]+$/", $values['password'])) {
                        $errors[] = "パスワードは半角英数字です。";
                    } else if (mb_strlen($values['password'], 'UTF-8') < self::MIN_PASSWORD_LENGTH) {
                        $errors[] = "パスワードは".self::MIN_PASSWORD_LENGTH."文字以上です。";
                    } else if (mb_strlen($values['password'], 'UTF-8') > self::MAX_PASSWORD_LENGTH) {
                        $errors[] = "パスワードが長すぎます。";
                    } else if ($values['password'] !== $values['confirm_password']) {
                        $errors[] = "パスワードが一致しません。";
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
            
            if (isset($values['profile_comment'])) {
                if (mb_strlen($values['profile_comment'], 'UTF-8') > self::MAX_PROFILE_COMMENT_LENGTH) {
                    $errors[] = "本文は".self::MAX_PROFILE_COMMENT_LENGTH."文字以内です。";
                }
            }
            //posts
            if (isset($values['color'])) {
                if (!array_key_exists($values['color'], self::SELECT_COLOR_OPTIONS)) {
                    $errors[] = "文字色が不正です"; 
                }
            }
            
            if (isset($values['delete_password'])) {
                if (strlen($values['delete_password']) !== 0) {
                    if (!preg_match("/^[a-zA-Z0-9]+$/", $values['delete_password'])) {
                        $errors[] = " パスワードは半角英数字です。";
                    }
                    if (mb_strlen($values['delete_password'], 'UTF-8') < self::MIN_DELETE_PASSWORD_LENGTH) {
                        $errors[] = " パスワードは".self::MIN_DELETE_PASSWORD_LENGTH."文字以上です。";
                    }
                    if (mb_strlen($values['delete_password'], 'UTF-8') > self::MAX_DELETE_PASSWORD_LENGTH) {
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
        
        if (isset($values['profile_comment'])) {
            $values['profile_comment'] = trim(mb_convert_kana($values['profile_comment'], 's'));
        }
        //post
        if (isset($values['delete_password'])) {
            $values['delete_password'] = trim(mb_convert_kana($values['delete_password'], 's'));
        }
        
        if (isset($values['comment'])) {
            $values['comment'] = trim(mb_convert_kana($values['comment'], 's'));
        }
        
        
        return $values;
    }
}