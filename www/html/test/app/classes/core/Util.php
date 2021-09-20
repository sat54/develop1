<?php
/**
 * ユーティリティ
 */
class Util
{
    /**
     * ランダム文字列生成 (英数字)
     * $length: 生成する文字数
     */
    static function get_rand_str($length = 8)
    {
        static $chars = 'abcdefghijklmnopqrstuvwxyz0123456789';
        $str = '';
        for ($i = 0; $i < $length; ++$i) {
            $str .= $chars[mt_rand(0, 35)];
        }
        return $str;
    }

    /**
     * ディレクトリごとファイルを削除
     */
    static function delete_directory($dir)
    {
        if(empty($dir)){
            return false;
        }
        if(stristr($dir,"../")){
            throw new Exception();
        }

        $result = false;
        if ($handle = opendir("$dir")){
            $result = true;
            while ((($file=readdir($handle))!==false) && ($result)){
                if ($file!='.' && $file!='..'){
                    if (is_dir("$dir/$file")){
                        $result = self::delete_directory("$dir/$file");
                    } else {
                        $result = unlink("$dir/$file");
                    }
                }
            }
            closedir($handle);
            if ($result){
                $result = rmdir($dir);
            }
        }
        return $result;
    }

    /**
     * 指定した時刻より作成時間が古いファイルを消す
     */
    static function delete_old_files($dir, $expire = '', $exclude_files = [])
    {
        if (empty($expire)) {
            $expire = time();
        }
        $files = scandir($dir);
        foreach($files as $file){
            $path = $dir . $file;
            if (!is_file($path)) continue;
            if (in_array($file, $exclude_files)) continue;
            $created = filemtime($path);
            if($created < $expire){
                unlink($path);
            }
        }
        return true;
    }

    /**
     * ディレクトリサイズを返す(bytes)
     */
    static function get_dir_size($path)
    {
        $dir_size = 0;
        $handle = opendir($path);
        while ($file = readdir($handle)) {
            if ($file != '..' && $file != '.' and !is_dir($path.'/'.$file)) {
                $dir_size += filesize($path.'/'.$file);
            } else if (is_dir($path.'/'.$file) and $file != '..' and $file != '.') {
                $dir_size += dir_size($path.'/'.$file);
            }
        }
        return $dir_size;
    }

}
