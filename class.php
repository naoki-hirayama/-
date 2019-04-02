<?php

class Product
{
    protected $price;

    // 価格取得
    public function getPrice()
    {
        return $this->price;
    }

    // 価格設定
    public function setPrice($price)
    {
        $this->price = $price;
    }
}

class FoodProduct extends Product
{
    private $expire = 15;

    // 残りの賞味期限を取得
    public function getExpire()
    {
        return $this->expire;
    }

    // 残りの賞味期限を1減らす
    public function decrementExpire()
    {
        $this->expire--;
        return $this->getExpire();
    }

    // 価格取得のオーバーライド
    public function getPrice()
    {
        $price = $this->price;        
        if ($this->expire <= 10) {
            // 残り賞味期限が10日以下になったら半額
            $price = $price / 2;
        }
        return $price;
    }

}

$prd = new FoodProduct();
// 価格を100に設定
$prd->setPrice(100);

// 賞味期限残日数が0になるまで繰り返し
$expire = $prd->getExpire();
while ($expire > 0) {
    echo '賞味期限残：' . $expire . '日 価格：' . $prd->getPrice();
    $expire = $prd->decrementExpire();
}
