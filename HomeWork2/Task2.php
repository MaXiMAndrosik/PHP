<?php
// Реализовать функцию с тремя параметрами: function mathOperation($arg1, $arg2,
// $operation), где $arg1, $arg2 – значения аргументов, $operation – строка с
// названием операции. В зависимости от переданного значения операции выполнить
// одну из арифметических операций (использовать функции из пункта 3) и вернуть
// полученное значение (использовать switch).

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

function mathOperation($arg1, $arg2, $operation) {
    switch ($operation) {
        case "+":
            $result = getSum($arg1, $arg2);
            break;
        case "-":
            $result = getDif($arg1, $arg2);
            break;
        case "*":
            $result = getMult($arg1, $arg2);
            break;
        case "/":
            $result = getDiv($arg1, $arg2);
            break;
        }
        return $result;

}

$num1 = 5;
$num2 = 7;

echo("Даны числа а = ".$num1." и b = ".$num2."\n");
echo("Сумма чисел равна: ".mathOperation($num1, $num2, "+")."\n");
echo("Разность чисел равна: ".mathOperation($num1, $num2, "-")."\n");
echo("Произведение чисел равно: ".mathOperation($num1, $num2, "*")."\n");
echo("Деление чисел равно: ".mathOperation($num1, $num2, "/")."\n");