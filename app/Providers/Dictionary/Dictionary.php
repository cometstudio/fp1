<?php

namespace App\Providers\Dictionary;

class Dictionary
{
    protected $dictionary = [];

    public function __construct()
    {
        $this->dictionary = config('dictionary');
    }

    public function get($pointer = '', $value=0)
    {
        try{
            $variants = array_get($this->dictionary, $pointer);

            if(empty($variants)) throw new \Exception;

            $lastDigit = $this->getLastDigit($value);

            // 1
            if($lastDigit == 1){
                if(($value > 10) && ($value < 20)){
                    $index = 2;
                }else{
                    $index = 0;
                }
            // 2-4
            }elseif(($lastDigit >= 2) && ($lastDigit <= 4)){
                if(($value > 10) && ($value < 20)){
                    $index = 2;
                }else{
                    $index = 1;
                }
            }
            // 0, 5-9
            elseif(empty($lastDigit) || (($lastDigit >= 5) && ($lastDigit <= 9))) {
                $index = 2;
            }else throw new \Exception;

            if(empty($variants[$index])) throw new \Exception;

            return $variants[$index];
        }catch (\Exception $e){
            return null;
        }
    }

    protected function getLastDigit($value = 0)
    {
        $lastDigit = $value%10;

        return $lastDigit;
    }

    public static function transliterate($string = '')
    {
        $inputArr = [
            ' ', 'a','б','в','г','д','е','ё','ж','з','и','й','к','л','м','н','о','п','р','с','т','у','ф','х','ц','ч','ш','щ','ь','ы','ъ','э','ю','я'
        ];
        $replaceArr = [
            '_', 'a','b','v','g','d','e','yo','zh','z','i','y','k','l','m','n','o','p','r','s','t','u','f','h','ts','ch','sh','sh','','y','','e','yu','ya'
        ];

        $string = str_replace($inputArr, $replaceArr, $string);

        $inputArr = [
            'А','Б','В','Г','Д','Е','Ё','Ж','З','И','Й','К','Л','М','Н','О','П','Р','С','Т','У','Ф','Х','Ц','Ч','Ш','Щ','Ь','Ы','Ъ','Э','Ю','Я'
        ];
        $replaceArr = [
            'A','B','V','G','D','E','YO','ZH','Z','I','Y','K','L','M','N','O','P','R','S','T','U','F','H','TS','CH','SH','SH','','Y','','E','YU','YA'
        ];

        $string = str_replace($inputArr, $replaceArr, $string);

        return $string;
    }
}
