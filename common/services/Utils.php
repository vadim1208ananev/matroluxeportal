<?php

namespace common\services;

class Utils
{

    const ALPHA_IN = [
        'А', 'а', 'Б', 'б', 'В', 'в',
        'Г', 'г', 'Д', 'д', 'Е', 'е',
        'Ё', 'ё', 'Ж', 'ж', 'З', 'з',
        'И', 'и', 'Й', 'й', 'К', 'к',
        'Л', 'л', 'М', 'м', 'Н', 'н',
        'О', 'о', 'П', 'п', 'Р', 'р',
        'С', 'с', 'Т', 'т', 'У', 'у',
        'Ф', 'ф', 'Х', 'х', 'Ц', 'ц',
        'Ч', 'ч', 'Ш', 'ш', 'Щ', 'щ',
        'Ъ', 'ъ', 'Ы', 'ы', 'Ь', 'ь',
        'Э', 'э', 'Ю', 'ю', 'Я', 'я',
        //ua
        'Ґ', 'ґ', 'Є', 'є', 'І', 'і',
        'Ї', 'ї',
    ];


    const ALPHA_OUT = [
        'A', 'a', 'B', 'b', 'V', 'v',
        'G', 'g', 'D', 'd', 'E', 'e',
        'Yo', 'yo', 'Zh', 'zh', 'Z', 'z',
        'I', 'i', 'Y', 'y', 'K', 'k',
        'L', 'l', 'M', 'm', 'N', 'n',
        'O', 'o', 'P', 'p', 'R', 'r',
        'S', 's', 'T', 't', 'U', 'u',
        'F', 'f', 'H', 'h', 'C', 'c',
        'Ch', 'ch', 'Sh', 'sh', 'Shch', 'shch',
        '', '', 'Y', 'y', '', '',
        'E', 'e', 'Yu', 'yu', 'Ya', 'ya',
        //ua
        'G', 'g', 'Ye', 'ye', 'I', 'i',
        'Yi', 'yi'
    ];

    public static function translitForUrl($str)
    {
        $str = trim($str);
        $str = str_replace(['_', '+', ' ', '-&amp;-'], '-', $str);
        $str = str_replace(['.', ',', '(', ')', '"'], '', $str);
        $str = self::translit($str);
        $str = mb_strtolower($str);
        return $str;
    }

    public static function translit($str)
    {
        return str_replace(self::ALPHA_IN, self::ALPHA_OUT, $str);
    }

    public static function removeNbsp($str)
    {
        return str_replace("\xc2\xa0", '', $str);
    }

}