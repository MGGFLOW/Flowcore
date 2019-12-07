<?php

class test extends Controller{
    public function index(){
        echo '<br>Congratulations!!!!<br>';
        $this->vk_api->setToken('tokennn');
        var_dump($this);
    }

    public function actionhow(){
        var_dump('zzzzz');
    }
}