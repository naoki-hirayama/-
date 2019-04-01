<?php

require_once('BaseRepository.php');

class PostRepository extends BaseRepository
{   
    public function create($values, $id = null)
    {   
        $values = $this->trimValues($values);
        if ($values['picture']['error'] === UPLOAD_ERR_OK) {
            
            $values['picture']['name'] = $this->reNameFileAndMoveUpLoadFile($values);
        } else {
            $values['picture']['name'] = null;
        }
        
        //パスワードが入力されない時の処理
        if (strlen($values['delete_password']) === 0) {
            $values['delete_password'] = null;
        }
        
        $values['user_id'] = $id;
        
        $sql = 'INSERT INTO posts (name,comment,color,delete_password,picture,user_id) VALUES (:name,:comment,:color,:delete_password,:picture,:user_id)';
        
        $statement = $this->database->prepare($sql);
        
        $statement->bindParam(':name', $values['name']);
        $statement->bindParam(':comment', $values['comment']);
        $statement->bindParam(':color', $values['color']);
        $statement->bindParam(':delete_password', $values['delete_password']);
        $statement->bindParam(':picture', $values['picture']['name']);
        $statement->bindParam(':user_id', $values['user_id']);
        
        $statement->execute();
    }
    
    public function delete($id)
    {   
        $post = $this->fetchById($id);
        
        $sql = 'DELETE FROM posts WHERE id = :id';
        
        $statement = $this->database->prepare($sql);
        
        $statement->bindParam(':id', $id);
        
        $statement->execute();
        
        // 投稿に画像がある時
        if (($post['picture']) !== null) {
            unlink("images/{$post['picture']}");
        }
    }
    
    public function fetchCountRecordsById()
    {   
        $sql = 'SELECT COUNT(id) AS CNT FROM posts';
        $statement = $this->database->query($sql);
        return $statement->fetchColumn();
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
    
    public function fetchById($id)
    {
        $sql = 'SELECT * FROM posts WHERE id = :id';
    
        $statement = $this->database->prepare($sql);
        
        $statement->bindParam(':id', $id);
        
        $statement->execute();
        
        return $statement->fetch(PDO::FETCH_ASSOC);
    }
    
}