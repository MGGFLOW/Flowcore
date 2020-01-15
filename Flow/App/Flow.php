<?php
namespace Mggflow;

class Flow{

    protected function getUriPath(){
        return urldecode(
            parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)
        );
    }

    private function parseUri($ignore){
        $uri = new class{
            public $controller;
            public $action = 'index';
            public $instructions = [];
        };

        $path = $this->getUriPath();

        $pos = strpos($path,$ignore);
        if($pos!==false)
            $pos += strlen($ignore);
        else $pos = 0;

        $path = trim(substr($path,$pos),'/');

        $path_components = explode('/',$path);

        foreach ($path_components as $ind=>$component){
            if(!empty($component)){
                if($ind==0) $uri->controller = $component;
                if($ind==1) $uri->action = $component;
                if($ind>1) $uri->instructions[] = $component;
            }
        }

        return $uri;
    }


    protected function getPages($pages_file){
        return include $pages_file;
    }

    protected function validPages(&$pages){
        if(empty($pages['controllers_dir'])) return false;
        if(empty($pages['autoload'])) return false;
        if(empty($pages['pages'])) return false;
        if(empty($pages['default'])) $pages['default'] = array_key_first($pages['pages']);
        if(empty($pages['ignore'])) $pages['ignore'] = '/';

        return true;
    }

    protected function getControllerPath($dir,$name){
        return ltrim($dir.'/'.$name.'.php','/');
    }

    protected function includeController($dir,$controller){
        $controller_path = $this->getControllerPath($dir,$controller);
        if(file_exists($controller_path)){
            include $controller_path;
            return true;
        }


        return false;
    }

    protected function includeAutoload($autoload_file){
        if(!file_exists($autoload_file)) return false;

        include $autoload_file;

        return true;
    }

    protected function getControllerConfigFile(&$pages,$controller_name){
        if(empty($pages['pages'][$controller_name])) return false;

        return $pages['pages'][$controller_name];
    }

    protected function provideController(&$pages,&$controller_name){
        if(empty($controller_name) or !isset($pages['pages'][$controller_name])){
            $controller_name = $pages['default'];
        }
    }

    public function run($pages_file){
        $pages = $this->getPages($pages_file);
        if(!$this->validPages($pages)) return false;

        $uri = $this->parseUri($pages['ignore']);
        $this->provideController($pages,$uri->controller);
        if(!$this->includeController($pages['controllers_dir'],$uri->controller)) return false;

        if(!$this->includeAutoload($pages['autoload'])) return false;

        $config_file = $this->getControllerConfigFile($pages,$uri->controller);
        if($config_file==false) return false;

        $controller = new $uri->controller();

        if(method_exists($controller,'run'))
            return $controller->run($config_file,$uri->action,$uri->instructions);

        return false;
    }
}