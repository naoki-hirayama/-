<?php

class BaseRepository
{
    protected $database;
    protected $table_name;
    
    public function __construct($database)
    {
        $this->database = $database;
    }
    
    public function fetchByname()
    {
        
    }
    
    public function delete($id)
    {   
        $sql = "DELETE FROM `{$this->table_name}` WHERE id = :id";
        
        $statement = $this->database->prepare($sql);
        
        $statement->bindParam(':id', $id);
        
        $statement->execute();
    }
    
    public function fetchCount()
    {   
        $sql = "SELECT COUNT(*) AS CNT FROM `{$this->table_name}`";
        
        $statement = $this->database->query($sql);
        
        return $statement->fetchColumn();
    }
    
    public function fetchById($id)
    {
        $sql = "SELECT * FROM `{$this->table_name}` WHERE id = :id";
    
        $statement = $this->database->prepare($sql);
        
        $statement->bindParam(':id', $id);
        
        $statement->execute();
        
        return $statement->fetch(PDO::FETCH_ASSOC);
    }
    
    public function fetchByIds($ids)
    {   
        $sanitized_ids = [];
        foreach ($ids as $id) {
            $sanitized_ids[] = (int)$id;  
        }
        $sql = "SELECT * FROM `{$this->table_name}` WHERE id IN (".implode(',', $sanitized_ids).")";
        
        $statement = $this->database->prepare($sql);
        
        $statement->execute();
        
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function fetchByOffSetAndLimit($offset, $limit)
    {
        $sql = "SELECT * FROM `{$this->table_name}` ORDER BY created_at DESC LIMIT :offset, :limit";
        
        $statement = $this->database->prepare($sql);
        
        $statement->bindParam(':offset', $offset, PDO::PARAM_INT);
        $statement->bindParam(':limit', $limit, PDO::PARAM_INT);
        
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
    
    protected function validateAlphaNumeric($string)
    {
        if (!preg_match("/^[a-zA-Z0-9]+$/", $string)) {
            return false;
        } else {
            return true;
        }
    }
    
    protected function getStringLength($string)
    {
        return mb_strlen($string, 'UTF-8');
    }
    
    protected function trimString($string)
    {
        return trim(mb_convert_kana($string, 's'));
    }
}


