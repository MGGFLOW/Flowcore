<?php

class example extends Controller{
    public function index(){
        $this->actionHello();
    }

    public function actionHello(){
        echo 'Hello World';
    }

    public function actionInfo(){
        phpinfo();
    }
}