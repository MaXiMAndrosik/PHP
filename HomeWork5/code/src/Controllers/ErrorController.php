<?php

namespace Geekbrains\Application1\Controllers;

use Geekbrains\Application1\Models\Phone;
use Geekbrains\Application1\Render;

class ErrorController
{
    public function actionIndex()
    {
        $render = new Render();
        return $render->renderPage('404.twig', [
            'title' => '404 not found'
        ]);
    }
}
