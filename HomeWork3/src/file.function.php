<?php

// function readAllFunction(string $address) : string {
function readAllFunction(array $config): string {
    $address = $config['storage']['address'];

    if (file_exists($address) && is_readable($address)) {
        $file = fopen($address, "rb");

        $contents = '';

        while (!feof($file)) {
            $contents .= fread($file, 100);
        }

        fclose($file);
        return $contents;
    } else {
        return handleError("Файл не существует");
    }
}

// function addFunction(string $address) : string {
function addFunction(array $config): string {
    $address = $config['storage']['address'];

    $name = readline("Введите имя (не более 20 символов): ");
    if (!validateInputName($name)) {
        return handleError("Вы не правильно ввели имя");
    }

    $date = readline("Введите дату рождения в формате ДД-ММ-ГГГГ: ");
    if (!validateDate($date)) {
        return handleError("Указан неверный формат даты или дата не существует");
    }

    $data = $name . ", " . $date . "\r\n";

    $fileHandler = fopen($address, 'a');

    if (fwrite($fileHandler, $data)) {
        return "Запись $data добавлена в файл $address";
    } else {
        return handleError("Произошла ошибка записи. Данные не сохранены");
    }

    fclose($fileHandler);
}

// function clearFunction(string $address) : string {
function clearFunction(array $config): string {
    $address = $config['storage']['address'];

    if (file_exists($address) && is_readable($address)) {
        $file = fopen($address, "w");

        fwrite($file, '');

        fclose($file);
        return "Файл очищен";
    } else {
        return handleError("Файл не существует");
    }
}

function helpFunction() {
    return handleHelp();
}

function readConfig(string $configAddress): array|false {
    return parse_ini_file($configAddress, true);
}

function readProfilesDirectory(array $config): string {
    $profilesDirectoryAddress = $config['profiles']['address'];

    if (!is_dir($profilesDirectoryAddress)) {
        mkdir($profilesDirectoryAddress);
    }

    $files = scandir($profilesDirectoryAddress);

    $result = "";

    if (count($files) > 2) {
        foreach ($files as $file) {
            if (in_array($file, ['.', '..']))
                continue;

            $result .= $file . "\r\n";
        }
    } else {
        $result .= "Директория пуста \r\n";
    }

    return $result;
}

function readProfile(array $config): string {
    $profilesDirectoryAddress = $config['profiles']['address'];

    if (!isset($_SERVER['argv'][2])) {
        return handleError("Не указан файл профиля");
    }

    $profileFileName = $profilesDirectoryAddress . $_SERVER['argv'][2] . ".json";

    if (!file_exists($profileFileName)) {
        return handleError("Файл $profileFileName не существует");
    }

    $contentJson = file_get_contents($profileFileName);
    $contentArray = json_decode($contentJson, true);

    $info = "Имя: " . $contentArray['name'] . "\r\n";
    $info .= "Фамилия: " . $contentArray['lastname'] . "\r\n";

    return $info;
}

function delFunction(array $config): string {
    $address = $config['storage']['address'];

    $name = readline("Введите имя для удаления записи: ");
    if (!validateInputName($name)) {
        return handleError("Вы не правильно ввели имя");
    }
    if (validateName($name, $address)) {
        $file = file($address);
        for ($i = 0; $i < sizeof($file); $i++) {
            $buffer = mb_split(',', $file[$i]);
            if ($name === $buffer[0]) unset($file[$i]);
        }
        $fp = fopen($address, "w");
        fputs($fp, implode("", $file));
        fclose($fp);
        return "Запись успешно удалена";

    } else {
        return handleError("Такого имени не существует");
    }
}

function readBirthday(array $config): string {
    $address = $config['storage']['address'];
    $today = date('d-m-Y');
    // $today = date('30-07-2022');
    $todayArray = explode('-', $today);
    $result = '';

    if (file_exists($address) && is_readable($address)) {
        $file = file($address);
        for ($i = 0; $i < sizeof($file); $i++) {
            $buffer = explode(', ', $file[$i]);
            $bufferArray = explode('-', $buffer[1]);
            if ($bufferArray[1] == $todayArray[1] && $bufferArray[0] == $todayArray[0]) {
                $result .= "\033[95m"."Сегодня отмечает день рождение: \r\n";
                $result .= $buffer[0].' '.$buffer[1]."\r\n\033[97m";
            }
        }
        if (strlen($result) > 1) {
            return $result;
        }
        for ($i = 0; $i < sizeof($file); $i++) {
            $buffer = explode(', ', $file[$i]);
            $bufferArray = explode('-', $buffer[1]);
            if ($bufferArray[1] == $todayArray[1] && $bufferArray[0] > $todayArray[0]) {
                $result .= "Скоро отмечает день рождение: \r\n";
                $result .= $buffer[0].' '.$buffer[1]."\r\n";
            }
        }
        if (strlen($result) > 1) {
            return $result;
        }
        return "В этом месяце дней рождений нет.\r\n";
    } else {
        return handleError("Файл не существует");
    }
}
