<?php

require_once(__DIR__ . '/vendor/autoload.php');

use Geekbrains\Application1\Application;

$app = new Application();
echo $app->run();


// В этом разделе дается краткое введение в PHP API для Twig:
// require_once(__DIR__ . '/vendor/autoload.php');

// $loader = new \Twig\Loader\ArrayLoader([
//     'index' => 'Hello {{ name }}!',
// ]);
// $twig = new \Twig\Environment($loader);

// echo $twig->render('index', ['name' => 'Fabien']);

// Поскольку шаблоны обычно хранятся в файловой системе, Twig также поставляется с загрузчиком файловой системы:
// require_once(__DIR__ . '/vendor/autoload.php');

// $loader = new \Twig\Loader\FilesystemLoader(dirname(__DIR__) . '/code/src/Views/');
// $twig = new \Twig\Environment($loader, [
//     // 'cache' => '/cache',
// ]);

// echo $twig->render('index.html', [
//     'name' => 'Fabien',
//     'content' => 'twig шаблон в действии'
// ]);