<?php

namespace Geekbrains\Application1\Domain\Controllers;

use Geekbrains\Application1\Application\Render;

class AboutController
{
    public function actionIndex() {
        $render = new Render();

        return $render->renderPage('about.twig', [
            'title' => 'О нас',
            'phone' => '$phone'
        ]);
    }
}