<?php
namespace Core\Controller;

use Core\Template\Template;
use Core\Registry\Registry;

class Controller
{
    public static function loadMethod($controller_name, $method, $params)
    {
        $full_controller_name = APP . CONTROLLERS . ucfirst($controller_name);
        $full_controller_name = str_replace('/', '\\', $full_controller_name);
        $controller = new $full_controller_name();
        if(method_exists($controller, "initstatic"))
        {
           $controller::initstatic();
        }
        if(method_exists($controller, "init"))
        {
           $controller->init();
        }
        Registry::set('cur_controller', $controller);
        call_user_func_array(array($controller, $method), $params);
        return $controller;
    }
    public function setView($view)
    {
        Template::assignView($view);
    }
    public function setViewData($key, $data)
    {
        Template::assignData($key, $data);
    }
}
