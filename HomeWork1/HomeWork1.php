<?php
// Задание 1 Собрать для себя окружение из Nginx + PHP-FPM и PHP CLI

        // прилагается history

// Задание 2 Выполните код в контейнере PHP CLI и объясните, что выведет данный код и
// почему

$a = 5;
$b = '05';

// Функция var_dump позволяет увидеть текущий тип переменной

// PHP 8.3.12 (cli) (built: Sep 24 2024 20:19:04) (ZTS Visual C++ 2019 x86)
// Copyright (c) The PHP Group
// Zend Engine v4.3.12, Copyright (c) Zend Technologies

// Операция проверки на равенство:
var_dump($a == $b); // bool(true) -> из-за неявного приведения типов

// Операция явного приведение типов:
var_dump((int)'012345'); // int(12345) -> строка приведена к int

// Операция проверки на равенство и соответствие типов:
var_dump((float)123.0 === (int)123.0); // bool(false) -> типы данных не равны

// Операция проверки на равенство:
var_dump(0 == 'hello, world'); // bool(false) -> данные отличны


// PHP 7.4.0 (cli) (built: Nov 27 2019 10:15:52) ( ZTS Visual C++ 2017 x86 )
// Copyright (c) The PHP Group
// Zend Engine v3.4.0, Copyright (c) Zend Technologies

// Операция проверки на равенство:
var_dump($a == $b); // bool(true) -> из-за неявного приведения типов

// Операция явного приведение типов:
var_dump((int)'012345'); // int(12345) -> строка приведена к int

// Операция проверки на равенство и соответствие типов:
var_dump((float)123.0 === (int)123.0); // bool(false) -> типы данных не равны

// Операция проверки на равенство:
var_dump(0 == 'hello, world'); // bool(true) -> до версии 8.0 см. https://www.php.net/manual/ru/types.comparisons.php

// Задание 3 Используя только две числовые переменные, поменяйте их значение
// местами. Например, если a = 1, b = 2, надо, чтобы получилось: b = 1, a = 2.
// Дополнительные переменные, функции и конструкции типа list()
// использовать нельзя.

$a = 1;
$b = 2;

echo  "Вариант 1\n";
$a+=+$b-$b=$a;
echo $a." ".$b."\n";

echo  "Вариант 2\n";
$a^=$b^=$a^=$b;
echo $a." ".$b."\n";

echo  "Вариант 3\n";
$a=$a+$b-$b=$a;
echo $a." ".$b."\n";

echo  "Вариант 4\n";
$a=[$b,$b=$a][0];                          
echo $a." ".$b."\n";

echo  "Вариант 5\n";
$a/=$b=($a*=$b)/$a;                        
echo $a." ".$b."\n";

echo  "Вариант 6\n";
eval("\$a=$b;\$b=$a;");                    
echo $a." ".$b."\n";

echo  "Вариант 7\n";
$a=$a+$b-($b=$a);
echo $a." ".$b."\n";