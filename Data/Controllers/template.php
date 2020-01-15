<?php

class template extends Mggflow\Controller{
    public function index(){
        $this->actionAction();
    }

    public function actionAction(){
        // when uri /ignored.../template/action
        $data = new Mggflow\ViewingData();

        $this->view('template_name',$data);
    }

}