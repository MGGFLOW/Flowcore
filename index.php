<?php

$flow_path = 'Flow/App/Flow.php';
$controller_path = 'Flow/Controller/Controller.php';
$view_path = 'Flow/View/View.php';

include $flow_path;
include $controller_path;
include $view_path;

$pages_file = 'Data/Config/pages.php';

$flow = new Mggflow\Flow();

$flow->run($pages_file);