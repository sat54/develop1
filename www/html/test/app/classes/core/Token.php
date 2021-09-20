<?php
/**
 * トークン
 */
class Token
{
    /**
     * CSRFトークンを作成
     */
    static function get()
    {
        //ver >= 5.3
        $bytes = openssl_random_pseudo_bytes(16);
        $token = bin2hex($bytes);

        //ver < 5.3
        $token = sha1(uniqid(mt_rand(),true));

        $_SESSION['token'] = $token;

        return $token;
    }

    /**
     * チェック
     */
    static function check($token = '')
    {
        if (! isset($token) || empty($token)) {
            return false;
        }
        if (! isset($_SESSION['token'])) {
            return false;
        }
        if ($token != $_SESSION['token']) {
            return false;
        }
        return true;
    }
}
