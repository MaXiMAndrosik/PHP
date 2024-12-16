<?php

namespace Geekbrains\Application1\Domain\Controllers;

use Geekbrains\Application1\Application\Render;
use Geekbrains\Application1\Domain\Models\User;

class UserController
{

    public function actionIndex(string $message = 'Список пользователей в хранилище')
    {
        $users = User::getAllUsersFromStorage();

        $render = new Render();

        return $render->renderPage(
            'user-index.twig',
            [
                'title' => 'Пользователи',
                'users' => $users,
                'message' => $message
            ]
        );
    }

    public function actionSave(): string
    {
        if (User::validateRequestData()) {
            $user = new User();
            $user->setParamsFromRequestData();
            $user->saveToStorage();

            $render = new Render();

            return $render->renderPage(
                'user-index.twig',
                [
                    'title' => 'Пользователь создан',
                    'message' => "Создан пользователь " . $user->getUserName() . " " . $user->getUserLastName(),
                    'users' => User::getAllUsersFromStorage()
                ]
            );
        } else {
            throw new \Exception("Переданные данные некорректны");
        }
    }

    public function actionUpdate(): string
    {
        if (!(isset($_GET['id']) && (isset($_GET['name']) || isset($_GET['lastname'])))) {
            header("HTTP/1.1 400 Bad Request");
            throw new \Exception("Не переданы необходимые данные в GET-запросе. Ошибка 400");
        }
        if (!(is_numeric($_GET['id']) && (!empty($_GET['name']) || !empty($_GET['lastname'])))) {
            header("HTTP/1.1 400 Bad Request");
            throw new \Exception("Не переданы необходимые данные в GET-запросе. Ошибка 400");
        }

        if (User::exists($_GET['id'])) {
            $user = new User();
            $user->setUserId($_GET['id']);

            $arrayData = [];

            if (isset($_GET['name']))
                $arrayData['user_name'] = $_GET['name'];

            if (isset($_GET['lastname'])) {
                $arrayData['user_lastname'] = $_GET['lastname'];
            }

            $user->updateUser($arrayData, $_GET['id']);
        } else {
            throw new \Exception("Пользователь не существует");
        }
        return UserController::actionIndex('Данные о пользователе обновлены');
    }

    public function actionDelete(): string
    {
        if (!isset($_GET['id'])) {
            header("HTTP/1.1 400 Bad Request");
            throw new \Exception("Не переданы необходимые данные в GET-запросе. Ошибка 400");
        }
        if (!is_numeric($_GET['id'])) {
            header("HTTP/1.1 400 Bad Request");
            throw new \Exception("Не переданы необходимые данные в GET-запросе. Ошибка 400");
        }

        if (User::exists($_GET['id'])) {
            User::deleteFromStorage($_GET['id']);
        } else {
            throw new \Exception("Пользователь не существует");
        }
        return UserController::actionIndex('Пользователь успешно удален');
    }

    public function actionAddUserForm()
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

    public function actionDelUserForm()
    {
        $render = new Render();

        return $render->renderPage(
            'user-del.twig',
            [
                'title' => 'Удаление пользователя',
                'message' => 'Выберите пользователя для удаления',
                'users' => User::getAllUsersFromStorage()
            ]
        );
    }

    public function actionUpdateUserForm()
    {
        $render = new Render();

        return $render->renderPage(
            'user-update.twig',
            [
                'title' => 'Редактирование пользователей',
                'message' => 'Выберите пользователя для редактирования',
                'users' => User::getAllUsersFromStorage()
            ]
        );
    }
}
