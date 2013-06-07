<?php

class U_Hash {

    protected static $_thumbSize = 8;

    public static function get($filename)
    {
        try {
            $meta = self::_check($filename);
        } catch (Exception $e) {
            return null;
        }

        $function = 'imagecreatefrom' . $meta['type'];
        $source = $function($filename);

        $thumb = imagecreatetruecolor(self::$_thumbSize, self::$_thumbSize);
        imagecopyresized(
            $thumb,
            $source,
            0,
            0,
            0,
            0,
            self::$_thumbSize,
            self::$_thumbSize,
            $meta[0],
            $meta[1]
        );

        imagefilter($thumb, IMG_FILTER_GRAYSCALE);

        $s = 0;
        for ($i = 0; $i<self::$_thumbSize; $i++) {
            for ($j = 0; $j<self::$_thumbSize; $j++) {
                $s += imagecolorat($thumb, $i, $j);
            }
        }

        $mediana = $s / pow(self::$_thumbSize, 2);

        $bytes = '';
        for ($i = 0; $i<self::$_thumbSize; $i++) {
            for ($j = 0; $j<self::$_thumbSize; $j++) {
                $bytes .= imagecolorat($thumb, $i, $j) < $mediana ? '1' : '0';
            }
        }
        var_dump(base_convert($bytes, 2, 16));
    }

    protected static function _check($filename)
    {
        if (!file_exists($filename)) {
            throw new Exception("File doesn't exists: " . $filename);
        }

        $meta = getimagesize($filename);
        if (!$meta || !isset($meta['mime']) || strpos($meta['mime'], 'image/') !== 0) {
            throw new Exception("File isn't image: " . $filename);
        }

        $meta['type'] = substr($meta['mime'], 6);
        if (!function_exists('imagecreatefrom' . $meta['type'])) {
            throw new Exception("GD doesn't have method for " . $meta['type']);
        }

        return $meta;
    }
}