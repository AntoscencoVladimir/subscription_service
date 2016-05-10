<?php

class View
{

    private $dir_tmpl;

    public function __construct($dir_tmpl)
    {
        $this->dir_tmpl = $dir_tmpl;
    }

    public function render($file, $params = [], $return = false)
    {
        $template = $this->dir_tmpl . $file . ".php";
        extract($params);
        ob_start();
        include($template);
        if ($return) {
            return ob_get_clean();
        } else {
            echo ob_get_clean();
        }
    }
    
    public function renderAction($controller, $action, $params = [])
    {
        if (class_exists($controller)) {
            $controller = new $controller();
            if (method_exists($controller,$action .'Action')){
                $request = new Request();
                $data = call_user_func_array([$controller, $action . 'Action'], [$request, $params]);
                return $this->render($data['template'],$data['params'], true);
            }
        }
            
    }
}