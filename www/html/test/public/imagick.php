<?php

//テスト出力用コード
function imagickTest(){

    if (!extension_loaded('imagick')){
        echo 'imagick not installed :(';
        exit;
    }


    $canvas = new Imagick();

    $canvas->newImage(256, 128, "light green");

    //外側の黒枠線
    $canvas->borderImage('black', 1, 1);

    //文字記入
    $draw = new ImagickDraw();
    $draw->setFontSize(16);
    $draw->setGravity(Imagick::GRAVITY_CENTER);
    $draw->annotation(0, 0, 'imagick inside :)');
    $canvas->drawImage($draw);

    $canvas->setImageFormat('jpg');

    //画像を出力
    header("Content-Type: image/jpg");
    echo $canvas;

    $canvas->destroy();
}

//実行
imagickTest();

?>