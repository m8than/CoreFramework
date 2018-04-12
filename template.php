<?php

class Template
{
    private static $_viewfile;
    private static $_viewdata = array();
    private static $_checked = array();
    
    public static function assignView($view)
    {
        if(!file_exists(ROOT . APP . VIEWS . $view))
        {
            Registry::add('warnings', 'View ' . $view . ' doesn\'t exist');
            return false;
        }
        self::$_viewfile = $view;
    }
    public static function assignData($key, $data)
    {
        self::$_viewdata[$key] = $data;
    }
    public static function dynamic_replacements($data)
    {
        //non php functions or overrides
        $toreplace = array();
        
        preg_match_all('/<?php(.*?)?>/', $data, $dynamic_parts1);
        preg_match_all('/{{(.*?)}}/', $data, $dynamic_parts2);
        $dynamic_parts = array_merge($dynamic_parts1, $dynamic_parts2);
        foreach($dynamic_parts[3] as $dynamic_part)
        {            
            preg_match_all('/(include|require) (\'|")(.*?)(\'|");/', $dynamic_part, $includes);
            for($i=0;$i<count($includes[0]);$i++)
            {
                $src = file_get_contents(ROOT . APP . VIEWS . $includes[3][$i]);                
                $toreplace[$includes[0][$i]] = $includes[1][$i] . ' Template::getCache("'.$includes[3][$i].'");';                
                self::dynamic_replacements($src);
            }
        }
        return strtr($data, $toreplace);
    }
    public static function replacements($data)
    {
        $data = preg_replace("/{{\s*(if\s*\(.*\))\s*}}/i", '{{$1:}}', $data);
        $data = preg_replace("/{{\s*\/if\s*}}/i", '{{endif;}}', $data);
        
        $data = preg_replace("/{{\s*(elseif\s*\(.*\))\s*}}/i", '{{$1:}}', $data);
        $data = preg_replace("/{{\s*else\s*}}/i", '{{else:}}', $data);
        
        $data = preg_replace("/{{\s*(foreach\s*\(.*\))\s*}}/i", '{{$1:}}', $data);
        $data = preg_replace("/{{\s*\/foreach\s*}}/i", '{{endforeach;}}', $data);
        
        $data = preg_replace("/{{\s*(switch\s*\(.*\))\s*}}/i", '{{$1:}}', $data);
        $data = preg_replace("/{{\s*\/switch\s*}}/i", '{{endswitch;}}', $data);
        
        $data = preg_replace("/{{\s*(case\s*.*)\s*}}/i", '{{$1:}}', $data);
        $data = preg_replace("/{{\s*\/case\s*}}/i", '{{break;}}', $data);
        
        $data = preg_replace("/{{\s*(for\s*\(.*\))\s*}}/i", '{{$1:}}', $data);
        $data = preg_replace("/{{\s*\/for\s*}}/i", '{{endfor;}}', $data);
        
        $data = preg_replace("/{{\s*(while\s*\(.*\))\s*}}/i", '{{$1:}}', $data);
        $data = preg_replace("/{{\s*\/while\s*}}/i", '{{endwhile;}}', $data);
                
        $data = preg_replace("/{{\s*(\\$[a-zA-Z\[\]\'\_\$]*)\s*}}/i", '{{=$1}}', $data);
        
        $data = str_replace("{{=", "<?=", $data);
        $data = str_replace("{{", "<?php ", $data);
        $data = str_replace("}}", "?>", $data);
                
        return $data;
    }
    public static function getCachePath($tpl_path)
    {
        $config = Registry::get('config');
        $cur_route = Registry::get('cur_route');
        $view_hash = hash('sha1', $tpl_path);
        return sprintf( ROOT . '%s/%s/%s_%s.php', $config['template_cache_dir'], $cur_route['controller'], $cur_route['method'], $view_hash);
    }
    public static function getCache($tpl_path = false)
    {        
        $tpl_path = $tpl_path ? $tpl_path : self::$_viewfile;
        $cache_path = self::getCachePath($tpl_path);
        
        if(isset(self::$_checked[$tpl_path]))
        {
            return $cache_path;
        }
        self::$_checked[$tpl_path] = true;
        
        
        $write_cache = true;
        if(is_file($cache_path))
        {
            $mtime_cache = filemtime($cache_path);
            $mtime_view = filemtime(ROOT . APP . VIEWS . $tpl_path);
            
            if($mtime_view <= $mtime_cache)
            {
                $write_cache = false;
            }
        }
        
        if($write_cache)
        {
            $data_finaltemplate = file_get_contents(ROOT . APP . VIEWS . $tpl_path);
            $data_finaltemplate = self::dynamic_replacements($data_finaltemplate);
            $data_finaltemplate = self::replacements($data_finaltemplate);
            $data_finaltemplate = '<?php if(!defined(\'CORE_SECURE\')) { die(); } ?>'.$data_finaltemplate;
            if (!is_dir(dirname($cache_path)))
            {
                mkdir(dirname($cache_path), 777, true);
            }
            file_put_contents($cache_path, $data_finaltemplate);
        }
        
        return $cache_path;
    }
    public static function output()
    {
        if(!isset(self::$_viewfile)) return;
        foreach(self::$_viewdata as $key => $value)
        {
            ${$key} = $value;
        }
        require self::getCache();
    }        
}
