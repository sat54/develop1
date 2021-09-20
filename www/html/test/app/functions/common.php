<?php

/**
 * デバッグ用
 */
function pr($value){
    echo "<pre>";
    print_r($value);
    echo "</pre>";
}

/**
 * タグのエスケープ
 */
function h($str, $encode = 'UTF-8') {
    if(is_array($str)){
        return array_map('h',$str);
    } else {
        return htmlspecialchars($str, ENT_QUOTES, $encode);
    }
}
