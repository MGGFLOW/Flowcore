<?php
function classPathCreate($class_name){
    $dirs = ['Data/Classes','Classes'];

    foreach ($dirs as $dir){
        $vars = [$dir.'/'.$class_name.'/'.$class_name.'.php', $dir.'/'.$class_name.'.php'];
        foreach ($vars as $path){
            if(file_exists($path)){
                return $path;
            }
        }
    }

    return false;
}

spl_autoload_register(function ($class_name) {
    $path = classPathCreate($class_name);
    if($path!==false){
        include $path;
    }
});