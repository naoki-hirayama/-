$ sudo service mysqld start

$ mysql -u root
TRUNCATE table users; 
use bbs
SELECT * FROM bbs.users;  

source ~/environment/kadai-ibg/memo/create_table_bbs.users.sql
<?php var_dump($post['password']);exit; ?>
$ git push origin master

 SELECT * FROM bbs.posts;
 SELECT * FROM bbs.users;
 DELETE FROM bbs.posts;
 
var_dump($count_records);exit;
 
 var_dump($_FILES['picture']);exit;
 
array (size=2)
  'max' => int 30
  'min' => int 4
/home/ec2-user/environment/kadai-ibg/function/function.php:36:
array (size=6)
  'name' => string 'テスト101101' (length=15)
  'comment' => string 'ww' (length=2)
  'MAX_FILE_SIZE' => string '1048576' (length=7)
  'color' => string 'black' (length=5)
  'password' => string '' (length=0)
  'submit' => string '投稿' (length=6)
/home/ec2-user/environment/kadai-ibg/function/function.php:36:
array (size=1)
  'picture' => 
    array (size=5)
      'name' => string '' (length=0)
      'type' => string '' (length=0)
      'tmp_name' => string '' (length=0)
      'error' => int 4
      'size' => int 0
### 課題①

* 投稿の登録と一覧表示までをやる。  
* 画面上部に投稿フォームがあり、その下に投稿の一覧が新しい順に表示される。
* 投稿に必要な項目は「名前」「本文」「投稿日時」 
* 「名前」は10文字以内＆入力必須
* 「本文」は100文字以内＆入力必須
* 「投稿日時」は自動で入るようにする
* エラー時はフォームとエラーメッセージだけが表示される画面に遷移する
* 投稿が成功したら「投稿しました」というメッセージと戻るリンクだけが表示される画面に遷移する
    * 戻るリンクをクリックするとTOP画面に戻る
    
### コーディングスタイルについて

* 変数名と関数名はスネークケース
* クラス名はアッパーキャメルケース
* メソッド名はローワーキャメルケース
* インデントは半角スペース4つ
* 関数／クラス／メソッド定義の波括弧は独立した行に記述する

* メッセージに色を付けられるようにする
    * 「黒」「赤」「青」「黄」「緑」
    * 必須でデフォルトは黒


* 削除機能を実装する
    * パスワード方式
    * 投稿時にパスワードを入力していた場合のみ、削除可能
    * 一覧の「削除」リンクをクリック→削除確認＆パスワード入力画面表示（削除対象の投稿を表示する）→パスワードを入力して「削除」を押すと削除
→削除完了画面
    * 4文字以上の半角英数字
    * パスワードの暗号化は特に必要なし
    
// [ function.php ]
<?php
    function add($a, $b) {
        return($a + $b);
    }
?>

// [ main.php ]
<?php
    require('function.php');

    $ret = add(10, 5);
    print("ret = $ret<br>\n");
?>

include_once文は、include文と同じく外部ファイルを読み込むときに使用しますが

、違いとして外部ファイルがすでに読み込まれているか、チェックを行います。

すでに外部ファイルが読み込まれている場合は、ファイルを読み込むことはありません。

そのため、同じファイルを誤って複数回読み込みんだり、

意図しない関数の再定義や値の初期化を防ぎたい場合に使用します。

https://www.sejuku.net/blog/23852
<?php
if ($_FILES['picture']['name'] != null) {
    $valid_picture_types = array(
        'png',
        'jpg',
        'gif',
    );

    $picture_type = substr($_FILES['picture']['name'], -3);
    $picture_type = strtolower($picture_type);

    if (!in_array($picture_type, $valid_picture_types)) {
        $errors[] = "画像が不正です";
    } else {
        // 画像処理
        $file = 'images/' . basename($_FILES['picture']['name']);
        // ファイルを一時フォルダから指定したディレクトリに移動
        move_uploaded_file($_FILES['picture']['tmp_name'], $file);
    }
}

if (!(($picture_type === 'png') || ($picture_type === 'jpg') || ($picture_type === 'gif') || ($picture_type === 'JPG') || ($_FILES['picture']['name'] == null))) {
    $errors[] = "画像が不正です";
} else {
    // 画像処理
    $file = 'images/' . basename($_FILES['picture']['name']);
    // ファイルを一時フォルダから指定したディレクトリに移動
    move_uploaded_file($_FILES['picture']['tmp_name'], $file);    
} 
?>

http://php.net/manual/ja/function.finfo-file.php
http://php.net/manual/ja/features.file-upload.errors.php

カンマの後スペース

* ユーザー登録＆ログイン・ログアウト機能を実装する
* ユーザー情報の編集画面は後の課題でやります。
* 項目は「ログインID」「パスワード」「名前」
    * 項目は全部必須
    * ログインIDはユニーク制約をつける
    * パスワードは確認用のフォームも用意すること（一致した場合のみ通すやつ）
    * パスワードは暗号化して保存する
* ログイン中の場合は投稿一覧画面上部に、ログイン中のユーザー名を表示する。
* この課題ではログインできるところまでできればOK

// SQL
        // SELECT * FROM table;
        // INSERT INTO table ()  values (), (), ..., ();
        // UPDATE table SET column = '', ... WHERE id = ?;
        // DELETE FROM table WHERE id = ?;
        // テーブルを追加
        // CREATE TABLE table;
        
        // テーブルを変更
        // ALTER TABLE table modify(change)
select p.*, u.name from post p left join user u on p.user_id = u.id order by created_at desc limit :start_page, :per_page_records;