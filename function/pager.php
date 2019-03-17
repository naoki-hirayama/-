<?php
class Pager
{
    private $total_records;
    private $per_page_records;
    private $max_pager_range;
    private $current_page;
    
    function __construct($total_records,$max_pager_range,$per_page_records) {
        $this->total_records = $total_records;
        $this->max_pager_range = $max_pager_range;
        $this->per_page_records = $per_page_records;
    }
    
    public function getTotalPages() {
        return (int)ceil($this->total_records / $this->per_page_records);
    }
    //現在のページ番号を設定
    public function setCurrentPage($page) {
        if ($page > $this->getTotalPages()) {
            $this->current_page = $this->getTotalPages();
        } else if ($page <= 0) {
            $this->current_page = 1; 
        } else {
            $this->current_page = (int)$page;
        }
            return $this->current_page;
    }
    
    public function getTotalRecords() {
        return $this->total_records;
    }
    
    public function getPerPageRecords() {
        return $this->per_page_records;
    }
    
    public function getCurrentPage() {
        return $this->current_page;
    }
    
    public function setBothRanges() {
        $both_ranges = [];
        if (($this->max_pager_range % 2) === 1) {
            $both_ranges['left'] =((int)ceil($this->max_pager_range - 1) / 2); 
            $both_ranges['right'] = ((int)floor($this->max_pager_range - 1) / 2);
            return $both_ranges; 
        } else {
            $both_ranges['left'] = (int)ceil($this->max_pager_range / 2);
            $both_ranges['right'] = ((int)floor($this->max_pager_range - 1) / 2);
            return $both_ranges;
        }
    }
    
    public function getPageNumbers() {
        $page_numbers = [];
        if ($this->current_page <= $this->setBothRanges()['left']) {
            for ($i = 1; $i <= $this->max_pager_range; $i++) {
                $page_numbers[] = $i;
            }
        }
        
        if (($this->current_page > $this->setBothRanges()['left']) && ($this->current_page < $this->getTotalPages() - $this->setBothRanges()['right'])) {
            for ($i = $this->current_page - $this->setBothRanges()['left']; $i <= $this->current_page + $this->setBothRanges()['right']; $i++) {
                if ($i >= 1) {
                    $page_numbers[] = $i;
                }
            }
        }
        
        if ($this->current_page >= $this->getTotalPages() - $this->setBothRanges()['right']) {
            for ($i = $this->getTotalPages() - $this->max_pager_range + 1; $i <= $this->getTotalPages(); $i++) {
                if ($i >= 1) {
                    $page_numbers[] = $i;
                }
            }
        }
        return $page_numbers;
    }

    public function getStartPage() {
        if (($this->current_page > 1) && ($this->current_page <= $this->getTotalPages())) {
            return ($this->current_page * $this->per_page_records) - $this->per_page_records;
        } else {
            return 0;
        }
    }
}
