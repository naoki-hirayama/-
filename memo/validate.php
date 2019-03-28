<?php
    public function validate($_post, $_files = 0);
    {   
        foreach($_post as $key => $value) {
            $user[$key] = $value;
        }
        
        if ($_files !== 0) {
            foreach($_files as $key => $value) {
                $file[$key] = $value;
            }
        }
        
        $errors = [];
        
        $name = trim(mb_convert_kana($user['name'], 's'));
        if (mb_strlen($name, 'UTF-8') === 0) {
            $errors[] = "名前は入力必須です。";
        } else if (mb_strlen($name, 'UTF-8') > 10) {
            $errors[] = "名前は１０文字以内です。";
        }
        
        $login_id = trim(mb_convert_kana($user['login_id'], 's'));
        if (mb_strlen($login_id, 'UTF-8') === 0) {
            $errors[] = "ログインIDは入力必須です。";
        } else if (!preg_match("/^[a-zA-Z0-9]+$/", $login_id)) {
            $errors[] = "ログインIDは半角英数字です。";
        } else if (mb_strlen($login_id, 'UTF-8') < 4) {
            $errors[] = "ログインIDは4文字以上です。";
        } else if (mb_strlen($login_id, 'UTF-8') > 15) {
            $errors[] = "ログインIDは15文字以内です。";
        } else {
            $tmp_user = $this->getUserDetailByUserLoginId($login_id);
            if ($tmp_user !== false) { 
                $errors[] = "このログインIDはすでに存在します。";
            }
        }
        
        $password = trim(mb_convert_kana($user['password'], 's'));
        if (mb_strlen($_password, 'UTF-8') === 0) {
            $errors[] = "パスワードは入力必須です。";
        } else if (!preg_match("/^[a-zA-Z0-9]+$/", $password)) {
            $errors[] = "パスワードは半角英数字です。";
        } else if (mb_strlen($password, 'UTF-8') < 4) {
            $errors[] = "パスワードは４文字以上です。";
        } else if (mb_strlen($password, 'UTF-8') > 30) {
            $errors[] = "パスワードが長すぎます。";
        } else if ($password !== $confirm_password) {
            $errors[] = "パスワードが一致しません。";
        }
        if ($_files === 0) {
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
    
   