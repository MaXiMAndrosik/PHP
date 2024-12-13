<?php

namespace Geekbrains\Application1\Models;

class User
{

    private ?string $userName;
    private ?int $userBirthday;

    private static string $storageAddress = '/storage/birthdays.txt';

    public function __construct(string $name = null, int $birthday = null)
    {
        $this->userName = $name;
        $this->userBirthday = $birthday;
    }

    public function setName(string $userName): void
    {
        $this->userName = $userName;
    }

    public function getUserName(): string
    {
        return $this->userName;
    }

    public function getUserBirthday(): int
    {
        return $this->userBirthday;
    }

    public function setBirthdayFromString(string $birthdayString): void
    {
        $this->userBirthday = strtotime($birthdayString);
    }

    public static function getAllUsersFromStorage(): array|false
    {
        $address = $_SERVER['DOCUMENT_ROOT'] . User::$storageAddress;

        if (file_exists($address) && is_readable($address)) {
            $file = fopen($address, "r");

            $users = [];

            while (!feof($file)) {
                $userString = fgets($file);
                $userArray = explode(",", $userString);

                if (count($userArray) < 2) {
                    continue;
                }

                $user = new User(
                    $userArray[0],
                );
                $user->setBirthdayFromString($userArray[1]);

                $users[] = $user;
            }

            fclose($file);

            return $users;
        } else {
            return false;
        }
    }

    public static function addUserToStorage(): bool
    {
        $address = $_SERVER['DOCUMENT_ROOT'] . User::$storageAddress;

        $requestArray = $_REQUEST;
        //  или получить через $_GET //$_GET['name'] //$_GET['birthday'];

        if (User::validateInputName($requestArray['name']) && strtotime($requestArray['birthday']) < time()) {
            $requestArray['birthday'] = date('d-m-Y', strtotime($requestArray['birthday']));
            $requestArrayString = implode(', ', $requestArray);
            if (file_exists($address) && is_writable($address)) {
                file_put_contents($address, $requestArrayString . PHP_EOL, FILE_APPEND | LOCK_EX);
                return true;
            }
        }
        return false;
    }

    private static function validateInputName(string $input): bool
    {

        $arrStr = mb_split(' ', $input);
        $lenArray = count($arrStr);
        $lenInput = strlen($input);
        $count = 0;
        foreach ($arrStr as $value) {
            if ($value === '') {
                $count++;
            }
        }

        if ($input == null || $lenArray == $count || $lenInput > 50) {
            return false;
        }

        if (!preg_match("/[-A-Za-zА-Яа-я]/", $input)) {
            return false;
        }

        return true;
    }
}
