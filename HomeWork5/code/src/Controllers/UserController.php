<?php

namespace Geekbrains\Application1\Controllers;

use Geekbrains\Application1\Render;
use Geekbrains\Application1\Models\User;

class UserController
{

    public function actionAddUser()
    {
        $render = new Render();

        return $render->renderPage(
            'user-add.twig',
            [
                'title' => 'Добавление пользователя',
                'message' => 'Добавление пользователя'
            ]
        );
    }
    public function actionSave()
    {
        if (User::addUserToStorage()) {
            header('Location: /user');
        } else {
            $render = new Render();

            return $render->renderPage(
                'user-add.twig',
                [
                    'title' => 'Добавление пользователя',
                    'message' => 'Повторите ввод данных пользователя',
                    'alert' => 'Некорректный ввод данных. Пользователь не добавлен'
                ]
            );
        }
    }


    public function actionIndex()
    {
        $users = User::getAllUsersFromStorage();

        $render = new Render();

        return $render->renderPage(
            'user-index.twig',
            [
                'title' => 'Пользователи',
                'users' => $users,
                'message' => 'Список пользователей в хранилище'
            ]
        );
    }
}
