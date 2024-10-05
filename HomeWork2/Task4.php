<?php
// Объявить массив, индексами которого являются буквы русского языка, а
// значениями – соответствующие латинские буквосочетания (‘а’=> ’a’, ‘б’ => ‘b’, ‘в’ =>
// ‘v’, ‘г’ => ‘g’, …, ‘э’ => ‘e’, ‘ю’ => ‘yu’, ‘я’ => ‘ya’). Написать функцию транслитерации
// строк.

$dictionary = array(
    'а' => "a",     'б' => "b",     'в' => "v",     'г' => "g",
    'д' => "d",     'е' => "e",     'ё' => "e",     'ж' => "zh",
    'з' => "z",     'и' => "i",     'й' => "i",     'к' => "k",
    'л' => "l",     'м' => "m",     'н' => "n",     'о' => "o",
    'п' => "p",     'р' => "r",     'с' => "s",     'т' => "t",
    'у' => "u",     'ф' => "f",     'х' => "",     'ц' => "c",
    'ч' => "ch",     'ш' => "sh",     'щ' => "sh",     'ь' => "",
    'ы' => "i",     'ъ' => "",     'э' => "e",     'ю' => "yu",
    'я' => "ya"
    );

function transcription($string) {
    global $dictionary;
    $strArr = preg_split('//u', $string, 0, PREG_SPLIT_NO_EMPTY); 
    $result_string = "";
    for ($counter = 0; $counter < count($strArr); $counter++) {
        if ((array_key_exists($strArr[$counter], $dictionary)) or (array_key_exists(mb_strtolower($strArr[$counter]), $dictionary))){
            if (array_key_exists($strArr[$counter], $dictionary)) {
                $result_string = $result_string.$dictionary[$strArr[$counter]];
            }
            elseif (array_key_exists(mb_strtolower($strArr[$counter]), $dictionary)) {
                $result_string = $result_string.mb_strtoupper($dictionary[mb_strtolower($strArr[$counter])]);
            }            
        } else {
            $result_string = $result_string.$strArr[$counter];
        }
    }
    return $result_string;
}


$string = "аб кпеывапрп *&^% !! AJHDAJSD dfvdf ыв фыафывФАФЫАФЫА";
echo $string."\n";
echo transcription($string);