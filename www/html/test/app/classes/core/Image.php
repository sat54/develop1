<?php
/**
 * イメージ
 */
class Image {
    private $convert = '/usr/bin/convert';

    static function thumb($tmpfilename, $savefilename, $wmax, $hmax, $mode = 'gd', $quality = 75) {

        if (!file_exists($tmpfilename)) {
            throw new Exception('file not found');
        }

        if($mode == "gd"){
            $imgsize = GetImageSize($tmpfilename);
            $imgheight = $imgsize[1];
            $imgwidth = $imgsize[0];
            if(($imgheight > $hmax) || ($imgwidth > $wmax)){
                $wpar = $wmax / $imgwidth;
                $hpar = $hmax / $imgheight;
                if ($wpar < $hpar) {$mul = $wpar;} else {$mul = $hpar;}
                $newwidth = (int) ($imgwidth * $mul);
                $newheight = (int) ($imgheight * $mul);
            } else {
                $newwidth = $imgwidth;
                $newheight = $imgheight;
            }

            if ($imgsize[2] == 2) {
                $image = imagecreatefromjpeg($tmpfilename);
                $newimage = imagecreatetruecolor($newwidth, $newheight);
                imagecopyresampled($newimage,$image, 0, 0, 0, 0, $newwidth, $newheight,$imgwidth,$imgheight);
                imagejpeg($newimage,$savefilename, $quality);
            }
            if ($imgsize[2] == 3) {
                $image = imagecreatefrompng($tmpfilename);
                $newimage = imagecreatetruecolor($newwidth, $newheight);
                imagecopyresampled($newimage,$image, 0, 0, 0, 0, $newwidth, $newheight,$imgwidth,$imgheight);
                imagejpeg($newimage,$savefilename, $quality);
            }
        } else {
            $output = system("\"$convert\" $tmpfilename -resize {$wmax}x{$hmax} jpg:$savefilename");
        }

    }
}
