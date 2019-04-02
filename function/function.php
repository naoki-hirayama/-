<?php
// エスケープの関数
function h($s)
{
    return htmlspecialchars($s, ENT_QUOTES, "UTF-8");
}

function dd($s)
{
    var_dump($s);
}

