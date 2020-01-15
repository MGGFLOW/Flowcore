<?php
namespace Mggflow;

class View{
    public $data;
    public $template_path;

    public function setData($data){
        $this->data = $data;
    }

    public function setTemplatePath($path){
        $this->template_path = $path;
    }

    public function start(){
        include $this->template_path;

        return true;
    }

}

class ViewingData{
    public function __set($name,$value){
        $this->$name = $value;
    }

    public function __get($name){
        return 'UNDEFINED';
    }
}
