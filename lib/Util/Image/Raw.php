<?php

class U_Image_Raw
{
    public static function convert($filename)
    {
        var_dump(file_exists($filename));
        $Img = new Imagick('nef:' . $filename);

        var_dump($Img);

        exit();
    }
}