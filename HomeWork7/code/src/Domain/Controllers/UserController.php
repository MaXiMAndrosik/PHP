<?php

namespace Geekbrains\Application1\Domain\Controllers;

use Geekbrains\Application1\Application\Application;
use Geekbrains\Application1\Application\Render;
use Geekbrains\Application1\Domain\Models\User;
use PDOException;

class UserController
{

    //-----------------------------------------------------------------------
    // ВЫВОД ДАННЫХ ОБО ВСЕХ ПОЛЬЗОВАТЕЛЯХ      /user/index
    public function actionIndex(string $message = 'Список пользователей в хранилище') {

        if (!isset($_SESSION['id_user'])) {
            header("HTTP/1.1 403 Forbidden");
            throw new \Exception("Доступ запрещен. Ошибка 403");
        }

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
    //-----------------------------------------------------------------------
    // РЕГИСТРАЦИЯ ПОЛЬЗОВАТЕЛЯ         /user/save/?name=Иван&lastname=Иванович&birthday=05-05-1991
    // Создание формы регистрации пользователя
    public function actionRegistrationForm(string $message = 'Добавление пользователя') {
        $render = new Render();
        if (!User::validateRequestData() && isset($_POST['name'])) {
            $name = $_POST['name'];
            $lastname = $_POST['lastname'];
            $birthday = $_POST['birthday'];
        } else {
            $name = null;
            $lastname = null;
            $birthday = null;
        }

        return $render->renderFormPage(
            'user-add.twig',
            [
                'title' => 'Регистрация',
                'message' => $message,
                'name' => $name,
                'lastname' => $lastname,
                'birthday' => $birthday,
            ]
        );
    }
    // Сохранение пользователя в Storage и его аутентификация
    // Выводит страницу со списком пользователей после успешной регистрации
    public function actionSave(): string {
        if (User::validateRequestData()) {
            $user = new User();
            $user->setParamsFromRequestData();
            try {
                $user->saveToStorage();
                $user->saveRightsToStorage();
                Application::$logger->getLoggerNotice('Зарегистрирован новый пользователь '
                    . $user->getUserName() . ' ' . $user->getUserLastName());
                Application::$auth->proceedAuth($_POST['login'], $_POST['password']);
            } catch (PDOException $e) {
                if ($e->getCode() == 23000) {
                    $message = 'Пользователь с таким Login уже существует! Введите другой логин. Попробуйте снова.';
                    return UserController::actionRegistrationForm($message);
                } else {
                    Application::$logger->getLoggerError("Ошибка при сохранении в хранилище " . $e->getCode());
                    throw new PDOException("Ошибка при сохранении в хранилище " . $e->getCode());
                }
            }

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
            Application::$logger->getLoggerNotice('При регистрации пользователя переданы некорректные данные');
            return UserController::actionRegistrationForm($message = 'Переданны некоректные данные');
            // throw new \Exception("Переданные данные некорректны");
        }
    }
    //-----------------------------------------------------------------------
    // АУТЕНТИФИКАЦИЯ ПОЛЬЗОВАТЕЛЯ         /user/auth/?login=Иван&password=Иванович
    // Создание формы аутентификации пользователя
    public function actionSignInForm(string $message = 'Вход в систему') {
        if (User::validateLoginData()) {
            $login = $_POST['login'];
        } else {
            $login = null;
        }

        $render = new Render();
        return $render->renderFormPage(
            'user-sign.twig',
            [
                'title' => 'Вход в систему',
                'login' => $login,
                'message' => $message
            ]
        );
    }
    // Проверка заполнения формы аутентификации и вход в систему
    // Application::$auth->proceedAuth($_POST['login'], $_POST['password']);
    // -> $_SESSION['user_name'] и $_SESSION['id_user']
    public function actionAuth(): string {

        if (isset($_POST['login']) && isset($_POST['password']) && User::validateLoginData()) {
            $result = Application::$auth->proceedAuth($_POST['login'], $_POST['password']);
        }

        if ($result) {
            header('Location: /');
        } else {
            return UserController::actionSignInForm('Неверные логин или пароль. Повторите вход в систему');
        }
    }
    //-----------------------------------------------------------------------
    // ВЫХОД ИЗ СИСТЕМЫ
    public function actionSignOff(): void {
        session_destroy();
        unset($_SESSION['user_name']);
        unset($_SESSION['csrf_token']);
        header("Location: /");
        die();
    }
    //-----------------------------------------------------------------------
    // РЕДАКТОРОВАНИЕ ДАННЫХ ПОЛЬЗОВАТЕЛЯ         /user/update/?id=42&name=Петр
    // Создание формы редактирования данных пользователя
    public function actionUpdateUserForm() {

        if (!User::getUserFromStorageById($_SESSION['id_user'])) {
            header("HTTP/1.1 404 Not Found");
            throw new \Exception("Пользователь не найден. Ошибка 404");
        }

        if (    !Application::$auth->isAccsess() ||
                ($_POST['id'] != $_SESSION['id_user'] && 
                !(Application::$auth->getUserRoles() == 'admin'))
            ) {
            header("HTTP/1.1 403 Forbidden");
            throw new \Exception("Доступ запрещен. Ошибка 403");
        }

        if (Application::$auth->isAccsess() && !isset($_POST['id']) && empty($_POST['id'])) {
            $user = User::getAllUsersFromStorage();
            $message = 'Выберите пользователя для редактирования:';
            $access_granted = true;
        } elseif (Application::$auth->isAccsess() && isset($_POST['id']) && !empty($_POST['id'])) {
            $user = User::getUserFromStorageById($_POST['id']);
            $message = 'Вы можете внести изменения:';
            $access_granted = false;
        } else {
            $user = User::getUserFromStorageById($_SESSION['id_user']);
            $message = 'Вы можете внести изменения:';
            $access_granted = false;
        }

        $render = new Render();
        return $render->renderFormPage(
            'user-update.twig',
            [
                'title' => 'Редактировать аккаунт',
                'message' => $message,
                'access_granted' => $access_granted,
                'user' => $user
            ]
        );
    }
    // Сохранение обновленных данных о пользователе в Storage
    public function actionUpdate(): string {

        if (!User::validateRequestData()) {
            header("HTTP/1.1 400 Bad Request");
            throw new \Exception("Не переданы необходимые данные. Ошибка 400");
        }

        if (User::getUserFromStorageById($_SESSION['id_user'])) {
            $user = new User();
            $user->setParamsFromRequestData();

            try {
                $user->updateUser();
                Application::$logger->getLoggerNotice('Пользователь '
                    . $user->getUserName() . ' ' . $user->getUserLastName() . ' изменил данные регистрации');
            } catch (PDOException $e) {
                Application::$logger->getLoggerError("Ошибка при сохранении в хранилище " . $e->getCode());
                throw new PDOException("Ошибка при сохранении в хранилище " . $e->getCode());
            }
        } else {
            header("HTTP/1.1 400 Bad Request");
            throw new \Exception("Пользователь не существует");
        }
        return UserController::actionIndex('Данные о пользователе ' . $user->getUserName() . ' ' . $user->getUserLastName() .' обновлены');
    }
    //-----------------------------------------------------------------------
    // УДАЛЕНИЕ ПОЛЬЗОВАТЕЛЯ        /user/delete/?id=42
    // Создание формы удаления данных пользователя
    public function actionDeleteUserForm() {

        if (Application::$auth->isAccsess() && !isset($_POST['id']) && empty($_POST['id'])) {
            $user = User::getAllUsersFromStorage();
        } else {
            $user = User::getUserFromStorageById($_SESSION['id_user']);
            User::deleteFromStorage($_SESSION['id_user']);
            UserController::actionSignOff();
        }

        $render = new Render();
        return $render->renderFormPage(
            'user-delete.twig',
            [
                'title' => 'Удалить аккаунт',
                'message' => 'Выберите пользователя для удаления:',
                'access_granted' => true,
                'user' => $user
            ]
        );
    }
    // Удаление пользователя из Storage
    public function actionDelete(): string {

        if (!isset($_POST['id']) || !is_numeric($_POST['id'])) {
            header("HTTP/1.1 400 Bad Request");
            throw new \Exception("Не переданы необходимые данные в запросе. Ошибка 400");
        }

        if (    !Application::$auth->isAccsess() ||
                ($_POST['id'] != $_SESSION['id_user'] && 
                !(Application::$auth->getUserRoles() == 'admin'))
            ) {
            header("HTTP/1.1 403 Forbidden");
            throw new \Exception("Доступ запрещен. Ошибка 403");
        }

        if (User::getUserFromStorageById($_POST['id'])) {
            $user = User::getUserFromStorageById($_POST['id']);
            $name = $user->getUserName();
            $lastname = $user->getUserLastName();
            // User::deleteFromStorage($_POST['id']);
            if ($_POST['id'] == $_SESSION['id_user']) {
                UserController::actionSignOff();
            }
        } else {
            throw new \Exception("Пользователь не существует");
        }

        return UserController::actionIndex('Пользователь ' . $name . ' ' . $lastname . ' успешно удален');;
    }
}
