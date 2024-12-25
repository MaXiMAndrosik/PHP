<?php

namespace Geekbrains\Application1\Domain\Models;

use Geekbrains\Application1\Application\Application;
use Geekbrains\Application1\Application\Auth;
// use Geekbrains\Application1\Infrastructure\Storage;

class User
{

    private ?int $idUser;
    private ?string $userName;
    private ?string $userLastName;
    private ?int $userBirthday;
    private ?string $userLogin;
    private ?string $password_hash;

    private static string $storageAddress = '/storage/birthdays.txt';

    public function __construct(int $id_user = null, string $name = null, string $lastName = null, int $birthday = null, $login = null, $password_hash = null)
    {
        $this->idUser = $id_user;
        $this->userName = $name;
        $this->userLastName = $lastName;
        $this->userBirthday = $birthday;
        $this->userLogin = $login;
        $this->password_hash = $password_hash;
    }
    //-----------------------------------------------------------------------
    // ГЕТТЕРЫ
    public function getUserId(): ?int {
        return $this->idUser;
    }
    public function getUserName(): string {
        return $this->userName;
    }
    public function getUserLastName(): string {
        return $this->userLastName;
    }
    public function getUserBirthday(): int {
        return $this->userBirthday;
    }
    //-----------------------------------------------------------------------
    // ПОЛУЧЕНИЕ ПОЛЬЗОВАТЕЛЯ ИЗ Storage ПО ID
    public static function getUserFromStorageById(int $id_user): User | false {
        $sql = "SELECT id_user, user_name, user_lastname, user_birthday_timestamp FROM users WHERE id_user = :id_user";

        $handler = Application::$storage->get()->prepare($sql);
        $handler->execute([
            'id_user' => $id_user
        ]);
        $result = $handler->fetch();
        $user = new User($result['id_user'], $result['user_name'], $result['user_lastname'], $result['user_birthday_timestamp']);
        return $user;
    }
    //-----------------------------------------------------------------------
    // ПОЛУЧЕНИЕ ВСЕХ ПОЛЬЗОВАТЕЛЕЙ ИЗ Storage
    public static function getAllUsersFromStorage(): array {
        $sql = "SELECT * FROM users";
        $handler = Application::$storage->get()->prepare($sql);
        $handler->execute();
        $result = $handler->fetchAll();

        $users = [];
        foreach ($result as $item) {
            $user = new User($item['id_user'], $item['user_name'], $item['user_lastname'], $item['user_birthday_timestamp']);
            $users[] = $user;
        }
        return $users;
    }
    //-----------------------------------------------------------------------
    // РЕГИСТРАЦИЯ ПОЛЬЗОВАТЕЛЯ
    // Валидация данных из формы user-add (user-update)
    public static function validateRequestData(): bool {
        if (
            isset($_POST['name']) && !empty($_POST['name']) &&
            isset($_POST['lastname']) && !empty($_POST['lastname']) &&
            isset($_POST['birthday']) && !empty($_POST['birthday']) &&
            preg_match('/^(\d{2}-\d{2}-\d{4})$/', $_POST['birthday']) &&
            isset($_SESSION['csrf_token']) && $_SESSION['csrf_token'] == $_POST['csrf_token']
        ) {
            return true;
        }
        return false;
    }
    // Преобразование даты рождения в Unix timestamp
    private function setBirthdayFromString(string $birthdayString): void {
        $this->userBirthday = strtotime($birthdayString);
    }
    // Заполняем свойства User'a по данным из формы user-add (user-update) из $_POST
    public function setParamsFromRequestData(): void {
        if (isset($_POST['id']) && !empty($_POST['id'])) {
            $this->idUser = htmlspecialchars($_POST['id']);
        }
        $this->userName = htmlspecialchars($_POST['name']);
        $this->userLastName = htmlspecialchars($_POST['lastname']);
        $this->setBirthdayFromString(htmlspecialchars($_POST['birthday']));
        if (isset($_POST['login']) && !empty($_POST['login'])) {
            $this->userLogin = htmlspecialchars($_POST['login']);
        }
        if (isset($_POST['password']) && !empty($_POST['password'])) {
            $this->password_hash = Auth::getPasswordHash(htmlspecialchars($_POST['password']));
        }
    }
    // Сохряняем User'a по данным из формы user-add в Storage
    public function saveToStorage() {
        $sql = "INSERT INTO users(user_name, user_lastname, user_birthday_timestamp, user_login, password_hash) VALUES (:user_name, :user_lastname, :user_birthday, :user_login, :password_hash)";

        $handler = Application::$storage->get()->prepare($sql);
        $handler->execute([
            'user_name' => $this->userName,
            'user_lastname' => $this->userLastName,
            'user_birthday' => $this->userBirthday,
            'user_login' => $this->userLogin,
            'password_hash' => $this->password_hash
        ]);
        $this->idUser = Application::$storage->get()->lastInsertId();
    }
    // Добавляем User'у права доступа в Storage по умолчанию 'customer'
    public function saveRightsToStorage() {
        $sql = "INSERT INTO user_roles(id_user, roles) VALUES (:id_user, :roles)";
        
        $handler = Application::$storage->get()->prepare($sql);
        $handler->execute([
            'id_user' => $this->idUser,
            'roles' => 'customer'
        ]);
    }
    //-----------------------------------------------------------------------
    // АУТЕНТИФИКАЦИЯ ПОЛЬЗОВАТЕЛЯ
    // Валидация данных из формы user-sign
    public static function validateLoginData(): bool {
        if (
            isset($_POST['login']) && !empty($_POST['login']) &&
            isset($_POST['password']) && !empty($_POST['password']) &&
            isset($_SESSION['csrf_token']) && $_SESSION['csrf_token'] == $_POST['csrf_token']
        ) {
            return true;
        }
        return false;
    }
    //-----------------------------------------------------------------------
    // РЕДАКТИРОВАНИЕ ДАННЫХ ПОЛЬЗОВАТЕЛЯ
    // Сохранение (обновление) данных о пользователе
    public function updateUser(): void {
        $sql = "UPDATE users SET user_name = :user_name, user_lastname = :user_lastname, user_birthday_timestamp = :user_birthday WHERE id_user = :id_user";

        $handler = Application::$storage->get()->prepare($sql);
        $handler->execute([
            'user_name' => $this->userName,
            'user_lastname' => $this->userLastName,
            'user_birthday' => $this->userBirthday,
            'id_user' => $this->idUser
        ]);
    }
    //-----------------------------------------------------------------------
    // УДАЛЕНИЕ ПОЛЬЗОВАТЕЛЯ
    public static function deleteFromStorage(int $user_id): void {
        $sql = "DELETE FROM users WHERE id_user = :id_user";

        $handler = Application::$storage->get()->prepare($sql);
        $handler->execute(['id_user' => $user_id]);

        $sql = "DELETE FROM user_roles WHERE id_user = :id_user";
        $handler = Application::$storage->get()->prepare($sql);
        $handler->execute(['id_user' => $user_id]);

    }
}
