<?php
    
    var_dump($_POST['name']);
    // エスケープの関数
    function h($s) {
        return htmlspecialchars($s, ENT_QUOTES, "UTF-8");
    }