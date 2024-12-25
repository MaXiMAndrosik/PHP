<?php

namespace Geekbrains\Application1\Application;

use Exception;
use Geekbrains\Application1\Infrastructure\Config;
use Geekbrains\Application1\Infrastructure\Storage;
use Geekbrains\Application1\Infrastructure\MyLogger;
use Geekbrains\Application1\Application\Auth;
use Geekbrains\Application1\Controllers\UserController;
use Geekbrains\Application1\Domain\Models\User;


class Application
{

    private const APP_NAMESPACE = 'Geekbrains\Application1\Domain\Controllers\\';

    private string $controllerName;
    private string $methodName;

    public static Config $config;
    public static Storage $storage;
    public static MyLogger $logger;

    public static Auth $auth;


    public function __construct()
    {
        Application::$config = new Config();
        Application::$storage = new Storage();
        Application::$auth = new Auth();
        Application::$logger = new MyLogger();
    }

    public function run(): string
    {
        session_start();

        Application::$logger->getLoggerAlert($_COOKIE['PHPSESSID']);

        $routeArray = explode('/', $_SERVER['REQUEST_URI']);

        if (isset($routeArray[1]) && $routeArray[1] != '') {
            $controllerName = $routeArray[1];
        } else {
            $controllerName = "page";
        }

        $this->controllerName = Application::APP_NAMESPACE . ucfirst($controllerName) . "Controller";

        if (class_exists($this->controllerName)) {

            if (isset($routeArray[2]) && $routeArray[2] != '') {
                $methodName = $routeArray[2];
            } else {
                $methodName = "index";
            }

            $this->methodName = "action" . ucfirst($methodName);

            if (method_exists($this->controllerName, $this->methodName)) {
                $controllerInstance = new $this->controllerName();
                var_dump($this->methodName);

                if ($this->checkAccessToMethod($this->methodName)) {
                    return call_user_func_array(
                        [$controllerInstance, $this->methodName],
                        []
                    );
                } else {
                    header("HTTP/1.1 403 Forbidden");
                    Application::$logger->getLoggerError("Попытка доступа к методу" . $this->methodName);
                    throw new Exception("Доступ запрещен. Ошибка 403");
                }
            } else {
                header("HTTP/1.1 404 Not Found");
                Application::$logger->getLoggerError("Метод " .  $this->methodName . " не существует");
                throw new Exception("Метод " .  $this->methodName . " не существует");
            }
        } else {
            header("HTTP/1.1 404 Not Found");
            Application::$logger->getLoggerError("Класс $this->controllerName не существует");
            throw new Exception("Класс $this->controllerName не существует");
        }

    }

    private function checkAccessToMethod(string $methodName): bool {
        $userRole = Application::$auth->getUserRoles();
        $metodPermissions = Application::$auth->getActionsPermissions($methodName);
        if (!empty($metodPermissions)) {
            if (in_array($userRole, $metodPermissions)) {
                Application::$auth->setAccess($userRole, $methodName);
                return true;
            }
        }
        return false;
    }
}
