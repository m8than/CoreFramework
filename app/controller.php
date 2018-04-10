<?php

class Controller
{
    private $_template_object;
    
    private $_controller_name = "";
    public function __construct()
    {
        
    }
    public static function load_method($controller_name, $method, $params)
    {
        $controller_name = ucfirst($controller_name);
        require_once ROOT . APP . CONTROLLERS . $controller_name . '.php';
        $controller = new $controller_name();
        if(method_exists($controller_name, "initstatic"))
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