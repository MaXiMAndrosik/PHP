<?php

namespace Geekbrains\Application1\Application;

class Auth
{

    protected array $actionsPermissions = [
        'actionIndex' => ['admin', 'manager', 'customer','user'],
        'actionSigninform' => ['user'],
        'actionRegistrationform' => ['user'],
        'actionSave' => ['user'],
        'actionAuth' => ['user'],
        'actionUpdateuserform' => ['admin', 'manager', 'customer'],
        'actionUpdate' => ['admin', 'manager', 'customer'],
        'actionSignoff' => ['admin', 'manager', 'customer'],
        'actionDeleteuserform' => ['admin', 'manager', 'customer'],
        'actionDelete' => ['admin', 'manager', 'customer']
    ];

    protected array $actionsAccess = [
        'actionIndex' => ['admin', 'manager', 'customer'],
        'actionUpdateuserform' => ['admin', 'manager'],
        'actionUpdate' => ['admin', 'manager', 'customer'],
        'actionSignoff' => ['admin', 'manager', 'customer'],
        // 'actionDeleteuserform' => ['admin'],
        'actionDelete' => ['admin', 'manager', 'customer']
    ];

    private bool $user_access;

    public function __construct() {
        $this->user_access = false;
    }

    public function setAccess(string $userRole, string $methodName, bool $access = true): void {
        if ($access && isset($this->actionsAccess[$methodName])) {
            if (in_array($userRole, $this->actionsAccess[$methodName])) {
                $this->user_access = true;
            } 
        } else {
            $this->user_access = false;
        }
    }
    public function isAccsess(): bool {
        return $this->user_access;
    }
    // Хэширование пароля при регистрации
    public static function getPasswordHash(string $rawPassword): string {
        Application::$logger->getLoggerInfo('Запрос хеша пароля');
        return password_hash($rawPassword, PASSWORD_BCRYPT);
    }
    // АУТЕНТИФИКАЦИЯ ПОЛЬЗОВАТЕЛЯ
    public function proceedAuth(string $login, string $password): bool {
        $sql = "SELECT id_user, user_name, user_lastname, password_hash FROM users WHERE user_login = :login";

        $handler = Application::$storage->get()->prepare($sql);
        $handler->execute(['login' => $login]);
        $result = $handler->fetchAll();

        if (!empty($result) && password_verify($password, $result[0]['password_hash'])) {
            $_SESSION['user_name'] = $result[0]['user_name'];
            $_SESSION['id_user'] = $result[0]['id_user'];
            Application::$logger->getLoggerNotice('Аутентификация пользователя '
                . $result[0]['user_name'] . ' '
                . $result[0]['user_lastname']);

            return true;
        } else {
            Application::$logger->getLoggerError('Ошибка аутентификации пользователя ' . $login);
            return false;
        }
    }
    //-----------------------------------------------------------------------
    // ПОЛУЧЕНИЕ СПИСКА ПРАВ ПОЛЬЗОВАТЕЛЯ (по умолчанию 'user')
    public function getUserRoles(): string {
        if (isset($_SESSION['id_user'])) {
            $roleSql = "SELECT * FROM user_roles WHERE id_user = :id";
            $handler = Application::$storage->get()->prepare($roleSql);
            $handler->execute(['id' => $_SESSION['id_user']]);
            $result = $handler->fetch();
            $role = $result['roles'];
        } else {
            $role = 'user';
        }
        return $role;
    }
    //-----------------------------------------------------------------------
    // ПОЛУЧЕНИЕ СПИСКА РОЛЕЙ ПОЛЬЗОВАТЕЛЯ ДЛЯ ДАННОГО МЕТОДА
    public function getActionsPermissions(string $methodName): array {
        return isset($this->actionsPermissions[$methodName]) ?
            $this->actionsPermissions[$methodName] : [];
    }
}
