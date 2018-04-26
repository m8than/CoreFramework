<?php
namespace App\Models;

use Core\Model\Model;
use Core\Registry\Registry;
use Core\Helpers\General;

class User extends Model
{
    public function __construct($id = 0, $table = '', $column = 'id')
    {
        parent::__construct($id, $table, $column);
    }
    public static function isLoggedIn()
    {
        return isset($_SESSION['user_id']);
    }
    public static function usernameToId($usernameoremail)
    {
        $db = Registry::get('db');
        return $db->selectCol('Users','username = :username OR email = :username', array(':username'=>$usernameoremail) , 'id');
    }
    public static function login($username, $password)
    {
        $id = self::username2id($username);
        if($id)
        {
            $user = User::fetch($id);
            
            if($user->checkPassword($password))
            {
                $_SESSION['last_login_ip'] = $user->get('login_ip');
                $_SESSION['last_login_timestamp'] = $user->get('login_timestamp');
                $_SESSION['ajax_key'] = General::randString(10);
                $_SESSION['user_id'] = $id;
            
                $user->set('login_ip', $_SERVER['REMOTE_ADDR']);
                $user->set('login_timestamp', $_SERVER['REQUEST_TIME']);
                $user->save();
                return $id;
            }
        }
        return false;
    }
    public function checkPassword($password)
    {
        return password_verify($password, $this->get('password'));
    }
    public function register($username, $password, $email, $fullname)
    {        
        $user = User::create();
        
        $user->set('username', $username);
        $user->set('password', password_hash($password, PASSWORD_DEFAULT));
        $user->set('email', $email);
        $user->set('fullname', $fullname);
        $user->set('reg_ip', $_SERVER['REMOTE_ADDR']);
        $user->set('reg_timestamp', $_SERVER['REQUEST_TIME']);
        $user->set('login_ip', $_SERVER['REMOTE_ADDR']);
        $user->set('login_timestamp', $_SERVER['REQUEST_TIME']);
        $id = $user->save();
        return $id;
    }
}
