<?php
// Написать функцию, которая вычисляет текущее время и возвращает его в
// формате с правильными склонениями, например:
// 22 часа 15 минут
// 21 час 43 минуты

function cuckoo_time ($data_string = "00:00:00") {
    ($data_string == "00:00:00") ? $data_string = date("H:i") : NULL ; // вывод текущего времени
    $time = explode(":", $data_string);
    $hour = $time[0];    //date("H");
    $minute = $time[1];  //date("i");
    if (($hour == 1) or  ($hour == 21)) {
        $hour = $hour." час";
    } elseif ((in_array($hour, range(2,4))) or (in_array($hour, range(22,23)))) {
        $hour = $hour." часа";
    } else {
        $hour = $hour." часов";
    }
    if (($minute % 10 == 1) and  ($minute != 11)) {
        $minute = $minute." минута";
    } elseif ((in_array($minute % 10, range(2,4))) and !(in_array($minute, range(12,14)))) {
        $minute = $minute." минуты";
    } else {
        $minute = $minute." минут";
    }
    return $hour." ".$minute."\n";
}

echo cuckoo_time ("22:15:23");
echo cuckoo_time ();
echo cuckoo_time ("21:43:23");