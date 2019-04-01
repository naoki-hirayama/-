<?php

class BaseRepository
{
    protected $database;
    protected $table_name;
    
    public function __construct($database, $table_name = 'posts')
    {
        $this->database = $database;
        $this->table_name = $table_name;
    }
    
    public function delete($id)
    {   
        $post = $this->fetchById($id);
        
        $sql = "DELETE FROM`{$this->table_name}`WHERE id = :id";
        
        $statement = $this->database->prepare($sql);
        
        $statement->bindParam(':id', $id);
        
        $statement->execute();
        
    }
    
    public function fetchCountRecordsById()
    {   
        $sql = "SELECT COUNT(id) AS CNT FROM`{$this->table_name}`";
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
        $sql = "SELECT * FROM`{$this->table_name}`WHERE id IN ({$ids})";
        
        $statement = $this->database->prepare($sql);
        
        $statement->execute();
        
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
}