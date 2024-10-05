<?php
// Объявить массив, индексами которого являются буквы русского языка, а
// значениями – соответствующие латинские буквосочетания (‘а’=> ’a’, ‘б’ => ‘b’, ‘в’ =>
// ‘v’, ‘г’ => ‘g’, …, ‘э’ => ‘e’, ‘ю’ => ‘yu’, ‘я’ => ‘ya’). Написать функцию транслитерации
// строк.
// header('Content-Type: text/html; charset=utf-8');

$dictionary = array(
    'а' => "a",     'б' => "b",     'в' => "v",     'г' => "g",
    'д' => "d",     'е' => "e",     'ё' => "e",     'ж' => "zh",
    'з' => "z",     'и' => "i",     'й' => "i",     'к' => "k",
    'л' => "l",     'м' => "m",     'н' => "n",     'о' => "o",
    'п' => "p",     'р' => "r",     'с' => "s",     'т' => "t",
    'у' => "u",     'ф' => "f",     'х' => "",     'ц' => "",
    'ч' => "ch",     'ш' => "sh",     'щ' => "sh",     'ь' => "",
    'ъ' => "",     'э' => "e",     'ю' => "yu",     'я' => "ya",
    " " => " " );

function transcription($string) {
    global $dictionary;
    $result_string = "";
    for ($counter = 0; $counter < strlen($string); $counter++) {
        echo($string[$counter]);
        // echo($string[$counter] === "а");
        // foreach ($dictionary as $key => $value) {
        //     if ($key == $string[$counter]) {
        //         echo($value);
        //     }
        //     else {
        //         echo($counter.$string[$counter].$key.$value."\n");

        //     }
        //     $key = $string[$counter] ? $result_string.$value : NULL ;
        // }
    }
}


$string = "абв sds";
echo($string."\n");
transcription($string);

