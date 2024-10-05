<?php
// Реализовать основные 4 арифметические операции в виде функции с тремя
// параметрами – два параметра это числа, третий – операция. Обязательно
// использовать оператор return.

function getSum($a, $b) {
    return $a + $b;
}

function getDif($a, $b) {
    return $a - $b;
}

function getMult($a, $b) {
    return $a * $b;
}

function getDiv($a, $b) {
    $res = NULL;
    if ($b != 0) {
        $res = $a / $b;
    }
    else {
        echo "Делить на ноль нельзя!";
        }
    return $res;
}

$num1 = 5;
$num2 = 7;

echo("Даны числа а = ".$num1." и b = ".$num2."\n");
echo("Сумма чисел равна: ".getSum($num1, $num2)."\n");
echo("Разность чисел равна: ".getDif($num1, $num2)."\n");
echo("Произведение чисел равно: ".getMult($num1, $num2)."\n");
echo("Деление чисел равно: ".getDiv($num1, $num2)."\n");