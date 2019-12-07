<?php

class test extends Controller{
    public function index(){
        echo '<br>Congratulations!!!!<br>';
        var_dump($this);
    }

    public function actionInfo(){
        phpinfo();
    }

}