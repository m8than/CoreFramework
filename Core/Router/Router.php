<?php
namespace Core\Router;

use Core\Registry\Registry;
use Core\Controller\Controller;

class Router
{
    private static $_index = 0;
    private static $_routes = array();
    
    private static $_found_id;
    private static $_params = array();
    
    public static function add($request_method, $uri, $controller, $method)
    {
        //converts url to  for index
        self::$_routes[self::$_index] = array(
            "request_method" => $request_method,
            "uri" => $uri,
            "controller" => $controller,
            "method" => $method
        );
        self::$_index++;
    }
    public static function prepare(&$path_var)
    {
        $uri = isset($path_var) ? $path_var : '';
        $uri = ltrim($uri, '/');
	
        unset($path_var);
                
        Registry::set('uri', $uri);
        Registry::set('request_method', $_SERVER['REQUEST_METHOD']);
        Registry::set('get', $_GET);
        Registry::set('post', $_POST);
        
        
        $cur_uri_split = explode('/', Registry::get('uri'));
        for($i=self::$_index-1;$i>-1;$i--) //loop through route in reverse order
        {         
            $found = true;       
            $params = array();
            $route_split = explode('/', self::$_routes[$i]['uri']);
            
            //cur uri is shorter than route or request_method wrong
            if(count($cur_uri_split) < count($route_split) || self::$_routes[$i]['request_method'] != Registry::get('request_method'))
            {
                //skip route
                continue;
            }
            
            for($j=0;$j<count($route_split);$j++) //loop through url parts
            {
                if($route_split[$j] == $cur_uri_split[$j])
                {
                    //uripart = routepart
                    continue;
                }
                else if($input_validation = self::validateInput($route_split[$j], $cur_uri_split[$j]))
                {
                    self::$_params[] = $input_validation;
                    continue;
                }
                else
                {
                    $found = false;
                    break;
                }
            }
            
            if($found)
            {
                self::$_found_id = $i;
                break;
            }
        }        
    }
    public static function dispatch()
    {
        if(isset(self::$_found_id))
        {
            //Only progress if not skipped above
            Registry::set('cur_route', self::$_routes[self::$_found_id]);
            $redirectCheck = explode(':', self::$_routes[self::$_found_id]['method']);
            if($redirectCheck[0] == 'Redirect')
            {
                Router::redirect(Registry::get('config')['URL'] . '/' . $redirectCheck[1]);
            }
            return Controller::loadMethod(self::$_routes[self::$_found_id]['controller'], self::$_routes[self::$_found_id]['method'], self::$_params);
        }   
    }
    private static function validateInput($key, $subject)
    {
        switch($key)
        {
            case '{int}':
                return (int)$subject;
                break;
            case '{str}':
                if(ctype_alnum($subject))
                    return $subject;
                else
                    return false;
                break;
            case '{*}':
                return $subject;
                break;
        }
        return false;
    }
    public static function redirect($url)
    {
        header('Location: '.$url);
        die();
    }
    public static function redirectSelf()
    {
        header('Location: '.__URL__);
        die();
    }
}
