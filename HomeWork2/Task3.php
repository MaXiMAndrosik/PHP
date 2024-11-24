<?php
// Объявить массив, в котором в качестве ключей будут использоваться названия
// областей, а в качестве значений – массивы с названиями городов из
// соответствующей области. Вывести в цикле значения массива, чтобы результат был
// таким: Московская область: Москва, Зеленоград, Клин Ленинградская область:
// Санкт-Петербург, Всеволожск, Павловск, Кронштадт Рязанская область … (названия
// городов можно найти на maps.yandex.ru)

$russia = array(
    'Московская область' => array("Москва", "Зеленоград", "Клин"),
    'Ленинградская область' => array("Санкт-Петербург", "Всеволожск", "Павловск", "Кронштадт"),
    'Рязанская область' => array("Рязань", "Касимов", "Скопин", "Рыбное"),
    'Смоленская область' => array("Смоленск", "Вязьма", "Рославль", "Ярцево", "Сафоново")
    );

foreach ($russia as $key => $value) {
    echo ($key.": ");
    // foreach ($value as $item) {
    //     echo ($item.", ");
    // }
    // for ($i=0; $i < count($value); $i++) { 
    //     ($i < count($value) - 1) ? print($value[$i].", ") : print($value[$i]."\n");
    // }
    print_r(implode(', ', $value));
}

// // Example 1
// $pizza  = "piece1 piece2 piece3 piece4 piece5 piece6";
// $pieces = explode(" ", $pizza);
// echo $pieces[0]; // piece1
// echo $pieces[1]; // piece2

// // Example 2
// $data = "foo:*:1023:1000::/home/foo:/bin/sh";
// list($user, $pass, $uid, $gid, $gecos, $home, $shell) = explode(":", $data);
// echo $user; // foo
// echo $pass; // *

// $array = array('имя', 'почта', 'телефон');
// $comma_separated = implode(",", $array);
// echo $comma_separated; // имя,почта,телефон
// // Пустая строка при использовании пустого массива:
// var_dump(implode('hello', array())); // string(0) ""