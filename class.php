<?php 

class Base 
{
    protected $hoge = 'base';
    
    public function hoge($id)
    {
        return '1'.$id;
    }
}

class Hoge extends Base
{
    protected $jjhoge = 'hoge2';
    
    public function hoge($id)
    {   
         $m = parent::hoge($id);
         
         echo $id.$m;
    }
}

$hoge = new Hoge();
var_dump($hoge->hoge(2));


// $base = new Base();
// echo $base->base();

// public function delete($id)
//     {   
//         $post = $this->fetchById($id);
//         parent::delete($id);
//         // 投稿に画像がある時
//         if (isset($post['picture'])) {
//             unlink("images/{$post['picture']}");
//         }
//     }