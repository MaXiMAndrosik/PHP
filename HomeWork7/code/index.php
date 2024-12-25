<?php

$memory_start = memory_get_usage();

require_once('./vendor/autoload.php');

use Geekbrains\Application1\Application\Application;
use Geekbrains\Application1\Application\Render;

try{
    $app = new Application();
    echo $app->run();
}
catch(Exception $e){
    echo Render::renderExceptionPage($e);
}

$memory_end = memory_get_usage();

echo "<h3>Потреблено " . number_format(($memory_end - $memory_start)/1024, 2, ', ', ' ') . " кб памяти</h3>";

// echo password_hash('admin123', PASSWORD_BCRYPT);
echo '<pre>';



var_dump($_COOKIE);
var_dump($_SESSION);
var_dump($_POST);


