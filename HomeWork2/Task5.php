<?php
// С помощью рекурсии организовать функцию возведения числа в степень.
// Формат: function power($val, $pow), где $val – заданное число, $pow – степень.

function power($val, $pow) {
    if ($pow == 0 || $pow == 1) {
        if ($pow == 0) {
            return 1;
        }
        if ($pow == 1) {
            return $val;
        }
    } else {
        return power($val, $pow - 1) * $val;
    }

}

$val = 7;
$pow = 23;

echo $val ** $pow."\n";

echo power($val, $pow);
