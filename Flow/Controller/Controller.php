<?php
namespace Mggflow;

class Controller{
    protected $config;
    protected $instructions;

    public function __get($name){
        if($this->installClass($name)){
            return $this->$name;
        }

        return NULL;
    }

    protected function loadConfig(&$config_file){
        $this->config = false;
        if(file_exists($config_file)){
            $this->config = include $config_file;
        }
    }

    protected function setProperty(&$for,$property,$value){
        $for->$property = $value;
    }

    protected function tuneClass(&$class,&$properties){
        foreach ($properties as $property=>$value){
            $this->setProperty($class,$property,$value);
        }
    }

    protected function installClass($name){
        if(isset($this->config['classes'][$name])){
            $this->$name = new $name;
            $this->tuneClass($this->$name,$this->config['classes'][$name]);
            return true;
        }

        return false;
    }

    protected function installSelfProperties(){
        if(isset($this->config['properties']) and is_array($this->config['properties'])){
            $this->tuneClass($this,$this->config['properties']);
        }
    }


    protected function setInstructions($instructions){
        $this->instructions = $instructions;
    }

    protected function genMethodName($action){
        $method_name = 'action'.$action;

        if(!method_exists($this,$method_name)){
            $method_name = 'index';
        }

        if(method_exists($this,$method_name)){
            return $method_name;
        }

        return false;
    }

    protected function tryInclude($path){
        if(file_exists($path)){
            include $path;

            return true;
        }

        return false;
    }

    protected function includeChain(){
        if(!empty($this->config['include'])){
            if(is_array($this->config['include'])){
                foreach ($this->config['include'] as $item){
                    $this->tryInclude($item);
                }
            }else{
                $this->tryInclude($this->config['include']);
            }
        }
    }

    public function run($config_file,$action,&$instructions){
        $this->loadConfig($config_file);
        if($this->config===false) return false;
        //var_dump('config');

        $this->installSelfProperties();
        $this->setInstructions($instructions);

        $method_name = $this->genMethodName($action);
        if($method_name===false) return false;
        $this->includeChain();


        return $this->$method_name();
    }

    protected function createTemplatePath($file){
        $dir = '';
        if(property_exists($this,'templates_dir')){
            $dir = $this->templates_dir;
        }

        return ltrim($dir.'/'.$file,'/');
    }

    protected function checkNameExtension($name){
        $ext = pathinfo($name,PATHINFO_EXTENSION );
        return $ext==='php';
    }

    protected function provideTemplateExtension($name){
        if($this->checkNameExtension($name)){
            return $name;
        }else{
            return $name.'.php';
        }
    }

    protected  function provideTemplate($name){
        $path = $this->provideTemplateExtension($name);
        //var_dump($path);
        if(!file_exists($path)){
            $path = $this->createTemplatePath($path);
            //var_dump($path);
            if(!file_exists($path)){
                return false;
            }
        }

        return $path;
    }

    public function view($template,$data){
        $path = $this->provideTemplate($template);
        //var_dump($path);
        if($path===false) return false;

        $view = new View();
        $view->setData($data);
        $view->setTemplatePath($path);

        return $view->start();
    }
}