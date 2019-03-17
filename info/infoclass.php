<?php
class Pager
//コントローラー
$pager = new Pager($per_pager_records, $max_pager_range, $total_pages);
$pager->setCurrentPage($page);
$sql = 'SELECT * FROM post ORDER BY created_at DESC LIMIT :start_page, :per_page_records';
$statement = $database->prepare($sql);
$statement->bindParam(':start_page', $pager->getStartPage(), PDO::PARAM_INT);
$statement->bindParam(':per_page_records', $pager->getPerPageRecords(), PDO::PARAM_INT);
// ビュー
<!--ページング処理-->
<?php if ($pager->getCurrentPage() > 1) : ?>
    <a href="?page=<?php echo $pager->getCurrentPage()-1; ?>">前へ</a>
<?php endif ?>

<?php foreach ($pager->getPageNumbers() as $i) : ?>
<?php if ($i !== $pager->getCurrentPage()) : ?>
<a href="?page=<?php echo $i; ?>">
    <?php echo $i; ?>
</a>
<?php endif ?>
<?php if ($i === $pager->getCurrentPage()) : ?>
<a>
    <?php echo $i; ?>
</a>
<?php endif ?>
<?php endforeach ?>
            
<?php if ($pager->getCurrentPage() < $pager->getTotalPage()) : ?>
    <a href="?page=<?php echo $pager->getCurrentPage()+1; ?>">次へ</a>
<?php endif ?>


<?php //例
 // GETでアクセスされた時 ex
    $stmt = $database->query('SELECT COUNT(id) AS CNT FROM post');
    $total_records = $stmt->fetchColumn();

    //変更したら表示できるページ幅が変わる 
    $max_pager_range = 10;
    $per_page_records = 3;
    $page_id = (int)$_GET['page'];
    
    $pager = new Pager( $max_pager_range,$per_pager_records,$page_id);
    echo $pager->echo_hogehoge();
    $pager->setPage(1);
    echo $pager->getPage();
    $range = $pager->getBothRanges();
    echo $range['left'];
    echo $range['right'];
    var_dump($pager->pages);exit;
    $pager->setCurrentsPage($page);
    $sql = 'SELECT * FROM post ORDER BY created_at DESC LIMIT :start_page, :per_page_records';
    $statement = $database->prepare($sql);
    $statement->bindParam(':start_page', $pager->getStartPage(), PDO::PARAM_INT);
    $statement->bindParam(':per_page_records', $pager->getPerPagerRecords(), PDO::PARAM_INT);
}

<?php
class Pager
{
    private $per_pager_records;
    private $max_pager_range;
    public $page;
    private $total_records;
    private $left_range;
    public $right_range;
    public $total_pages;
    
    function __construct($per_pager_records, $max_pager_range,$page,$total_records) {
        $this->per_pager_records = $per_pager_records;
        $this->max_pager_range = $max_pager_range;
        $this->page = $page;
        $this->total_records = $total_records;
    }
    
    public function getPage() {
        return $this->page;
    }
    
    public function setPage($page) {
        $this->page = $page;
        return;
    }
    
    private function hogehoge () {
        return 1;
    }
    
    public function echo_hogehoge() {
        $str = $this->hogehoge();
        return $str;
    }
    
    
    public function getTotalPages() {
      return ceil($this->total_records / $this->per_pager_records);
    }
    
    public function getBothRanges ($max_pager_range) {
        $range = [];
        
        $range['left'] = aaa;
        $range['right'] = bbb;
        
        return $range;
        if (($this->max_pager_range % 2) === 1) {
           $this->left_range =((int)ceil($this->max_pager_range - 1) / 2); 
           $this->right_range = ((int)floor($this->max_pager_range - 1) / 2);
        } else {
           $this->left_range = (int)ceil($this->max_pager_range / 2);
            $this->right_range = ((int)floor($this->max_pager_range - 1) / 2);
        }
    }
<?php
 // GETでアクセスされた時
    $stmt = $database->query('SELECT COUNT(id) AS CNT FROM post');
    $total_records = $stmt->fetchColumn();
    //変更したら表示できるページ幅が変わる 
    $max_pager_range = 10;
    $per_page_records = 3;
    $total_pages = (int)ceil($total_records / $per_page_records);
    
    if (($max_pager_range % 2) === 1) {
        $left_range =((int)ceil($max_pager_range - 1) / 2); 
        $right_range = ((int)floor($max_pager_range - 1) / 2);
    } else {
        $left_range = (int)ceil($max_pager_range / 2);
        $right_range = ((int)floor($max_pager_range - 1) / 2);
    }
    
    if ($_GET['page'] > $total_pages) {
        $page = $total_pages;
    } else if ($_GET['page'] <= 0) {
        $page = 1; 
    } else if ($_GET['page'] <= $total_pages) {
        $page = (int)$_GET['page'];
    } else {
        header('HTTP/1.1 404 Not Found'); 
        exit;
    }
    
    // ページング処理
    $page_numbers = [];
    if ($page <= $left_range) {
        for ($i = 1; $i <= $max_pager_range; $i++) {
            $page_numbers[] = $i;
        }
    }
    
    if (($page > $left_range) && ($page < $total_pages - $right_range)) {
        for ($i = $page - $left_range; $i <= $page + $right_range; $i++) {
            if ($i >= 1) {
                $page_numbers[] = $i;
            }
        }
    }
    
    if ($page >= $total_pages - $right_range) {
        for ($i = $total_pages - $max_pager_range + 1; $i <= $total_pages; $i++) {
            if ($i >= 1) {
                $page_numbers[] = $i;
            }
        }
    }
    
    // オフセット
    if (($page > 1) && ($page <= $total_pages)) {
        $start_page = ($page * $per_page_records) - $per_page_records;
    } else {
        $start_page = 0;
    }
    // postテーブルから3件のデータを取得する
    $sql = 'SELECT * FROM post ORDER BY created_at DESC LIMIT :start_page, :per_page_records';
    
    $statement = $database->prepare($sql);
    
    $statement->bindParam(':start_page', $start_page, PDO::PARAM_INT);
    $statement->bindParam(':per_page_records', $per_page_records, PDO::PARAM_INT);
    
    $statement->execute();
    
    $records = $statement->fetchAll();
    