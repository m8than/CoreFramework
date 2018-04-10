<?php
class General
{
    public static function contains($needle, $haystack)
    {
        return strpos($haystack, $needle) !== false;
    }
    public static function reg_contains($needle, $haystack)
    {
        preg_match_all($needle, $haystack, $matches);
        return (bool)count($matches[0]);
    }
    public static function generateFormToken()
    {
        $token = md5(uniqid(rand(), true));
        $_SESSION['FORMTOKEN'][] = $token.sha1($_SERVER['REMOTE_ADDR']);
        return $token;
    }
    public static function verifyFormToken()
    {
        if(!isset($_REQUEST['FORMTOKEN'], $_SESSION['FORMTOKEN'])) return false;
        $return = (in_array($_REQUEST['FORMTOKEN'].sha1($_SERVER['REMOTE_ADDR']), $_SESSION['FORMTOKEN']));
        unset($_SESSION['FORMTOKEN']);
        return $return;
    }
    public static function randString($length = 8)
    {
        return substr(md5(uniqid(mt_rand(), true)), 0, $length);
    }
    public static function toDateTime($unixTimestamp)
    {
        return date("Y-m-d H:i:s", $unixTimestamp);
    }
}