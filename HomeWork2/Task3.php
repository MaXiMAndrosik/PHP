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
    foreach ($value as $item) {
        echo ($item.", ");
    }
    echo chr(8).chr(8)." "; //backspace!!!
}