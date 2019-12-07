<?php

$flow_path = 'Flow/App/Flow.php';
$controller_path = 'Flow/Controller/Controller.php';
include $flow_path;
include $controller_path;

$pages_file = 'Data/Config/pages.php';

$flow = new Flow();

$flow->run($pages_file);