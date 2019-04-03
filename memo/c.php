<?php

class Base 
{
    protected $name = 'ホゲ';
    
    public function setName($name)
    {
        $this->name = $name;
    }
    public function getName()
    {
        return $this->name;
    }
    public function Id($id)
    {
        $i = $id;
        echo $i;
    }
}

class Hoge extends Base
{
    public function getName()
    {
        $name = parent::getName();
        return $name.'さん';
    }
    
    public function validateAlphaNumeric($string)
    {
        if (!preg_match("/^[a-zA-Z0-9]+$/", $string)) {
            return false;
        } else {
            return true;
        }
    }
}

$hoge = new Hoge();
$hoge->setName('hoge');
$hoge->Id(6);

$string = 'jjj';
var_dump(!preg_match("/^[a-zA-Z0-9]+$/", $string));
var_dump($hoge->validateAlphaNumeric($string));

// $base = new Base();
// echo $base->getName();
// var_dump($base->Id(10));
if (($s = 10) ===10) {
    echo "true";
} else {
    echo "false";
}