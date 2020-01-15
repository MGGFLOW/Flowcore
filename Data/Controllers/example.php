<?php

class example extends Mggflow\Controller{
    public function index(){
        $this->actionHello();
    }

    public function actionHello(){
        $data = new Mggflow\ViewingData();
        $data->message = 'Hello World!';

        $this->view('example',$data);
    }

    public function actionInfo(){
        phpinfo();
    }
}