<?php 

class Base 
{
    protected $add;
    
    public function add($n)
    {
        return 1 + $n;
    }
    
    public function add2($m)
    {
        return 1 + $m;
    }
}

class Add extends Base
{
    protected $hoge;
    
    public function add($id)
    {   
         parent::add($id);
         echo 1 + $this->add($id);
    }
}

$base = new Base();
var_dump($base->add(2));

$add = new Add();
var_dump($add->add(5));

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