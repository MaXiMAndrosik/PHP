<?php

function validateDate(string $date): bool {
    $dateBlocks = explode("-", $date);
    if (count($dateBlocks) < 3 || strlen($dateBlocks[2]) == 0 || strlen($dateBlocks[1]) == 0 || strlen($dateBlocks[0]) == 0) {
        return false;
    }

    if ($dateBlocks[2] > date('Y')) {
        return false;
    } elseif ($dateBlocks[2] == date('Y') && $dateBlocks[1] > date('m')) {
        return false;
    } elseif ($dateBlocks[2] > date('Y') && $dateBlocks[1] == date('m') && $dateBlocks[0] > date('d')) {
        return false;
    } else {
        if ($dateBlocks[2] < 1900 || $dateBlocks[1] > 12 || $dateBlocks[0] > 31 || strlen($dateBlocks[1]) < 2 || strlen($dateBlocks[0]) < 2) {
            return false;
        }
        if ($dateBlocks[2] % 4 == 0 && $dateBlocks[1] == 2 && $dateBlocks[0] > 29) {
            return false;
        }
        if ($dateBlocks[2] % 4 != 0 && $dateBlocks[1] == 2 && $dateBlocks[0] > 28) {
            return false;
        }
        if (($dateBlocks[1] == 4 || $dateBlocks[1] == 6 || $dateBlocks[1] == 9 || $dateBlocks[1] == 11) && $dateBlocks[0] > 30) {
            return false;
        }
    }
    return true;
}

function validateInputName(string $input): bool {

    $arrStr = mb_split(' ', $input);
    $lenArray = count($arrStr);
    $lenInput = strlen($input);
    $count = 0;
    foreach ($arrStr as $value) {
        if ($value === '') {
            $count++;
        }
    }

    if ($input == null || $lenArray == $count || $lenInput > 20) {
        return false;
    }

    if (!preg_match("/[-A-Za-zА-Яа-я]/", $input)) {
        return false;
    }

    return true;
}

function validateName(string $input, string $address): bool {

    $fileHandler = fopen($address, 'r');
    if ($fileHandler) {
        while (!feof($fileHandler)) {
            $buffer = fgets($fileHandler, 256);
            $bufferArray = mb_split(',', $buffer);
            if ($input === $bufferArray[0]) {
                return true;
            }
        }
    } 
    return false;
}
