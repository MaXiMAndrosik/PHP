<?php

namespace Geekbrains\Application1\Infrastructure;

use \Monolog\Level;
use \Monolog\Logger;
use \Monolog\Handler\RotatingFileHandler;
use \Monolog\Handler\StreamHandler;
use \Monolog\Handler\FirePHPHandler;
use \Monolog\Formatter\JsonFormatter;
use Geekbrains\Application1\Application\Application;

class MyLogger {

    public Logger $logger;

    public function __construct() {
        $this->logger = new Logger('application');
        // Сообщения уровня Info и выше записываются в файл в стандартном формате
        // файлы перезаписываются
        $this->logger->pushHandler(new RotatingFileHandler(
                $_SERVER['DOCUMENT_ROOT'] . '/../log/'
                . Application::$config->get()['log']['INFO_LOGS_FILE'], 7,
                Level::Info
        ));
        // Сообщения уровня Error и выше записываются в файл в json-формате
        // $jsonHandler = new StreamHandler($_SERVER['DOCUMENT_ROOT'] . "/log/"
        //         . Application::$config->get()['log']['ERROR_LOGS_FILE'], Logger::ERROR);
        // $jsonHandler->setFormatter(new JsonFormatter());
        // $this->logger->pushHandler($jsonHandler);

        $this->logger->pushHandler(new StreamHandler(
            $_SERVER['DOCUMENT_ROOT'] . '/../log/'
            . Application::$config->get()['log']['ERROR_LOGS_FILE'], 
            Level::Error
        ));

        $this->logger->pushHandler(new StreamHandler(
            $_SERVER['DOCUMENT_ROOT'] . '/../log/'
            . Application::$config->get()['log']['SESSION_LOGS_FILE'], 
            Level::Alert
        ));

        $this->logger->pushHandler(new FirePHPHandler());
    }

    public function getLoggerInfo(string $message = 'INFO message'): void {
        $this->logger->info($message);
    }
    
    public function getLoggerError(string $message = 'ERROR message'): void {
        $this->logger->error($message);
    }

    public function getLoggerNotice(string $message = 'NOTICE message'): void {
        $this->logger->notice($message);
    }

    public function getLoggerAlert(string $message = 'ALERT message'): void {
        $this->logger->alert($message);
    }
}
