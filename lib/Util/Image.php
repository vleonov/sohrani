<?php

class U_Image
{
    static protected $_sizes = array(
        'photo' => array(
            'thumbs' => array(300, 160, 70),
            'prevws' => array(1024,800, 85),
        ),
        'album' => array(
            'bulks' => array(70, 70, 100),
        ),
    );

    static public function makeThumbs($filename)
    {
        if (!file_exists($filename)) {
            throw new Exception("File doesn't exists: " . $filename);
        }

        $meta = getimagesize($filename);
        $exif = array();

        switch (U_Misc::is($meta[2])) {
            case IMAGETYPE_JPEG:
            case IMAGETYPE_JPEG2000:
                $gdSrc = imagecreatefromjpeg($filename);
                $exif = exif_read_data($filename, 'IFD0');
                break;
            case IMAGETYPE_PNG:
                $gdSrc = imagecreatefrompng($filename);
                break;
            default :
                throw new Exception("File is not image: " . $filename);
        }

        $w0 = $meta[0];
        $h0 = $meta[1];

        $orientation = U_Misc::is($exif['Orientation'], 1);
        switch ($orientation) {
            case 3:
                $gdSrc = imagerotate($gdSrc, 180, 0);
                break;
            case 6:
                $gdSrc = imagerotate($gdSrc, -90, 0);
                $w0 = $meta[1];
                $h0 = $meta[0];
                break;
            case 8:
                $gdSrc = imagerotate($gdSrc, 90, 0);
                $w0 = $meta[1];
                $h0 = $meta[0];
                break;
        }

        $result = array();

        foreach (self::$_sizes['photo'] as $dir=>$sizes) {
            $dw = $w0 / $sizes[0];
            $h1 = round($h0 / $dw);

            $dh = $h0 / $sizes[1];
            $w1 = round($w0 / $dh);

            if ($h1 > $sizes[1]) {
                $h1 = $sizes[1];
            } else {
                $w1 = $sizes[0];
            }

            $gdDst = imagecreatetruecolor($w1, $h1);
            imagecopyresampled(
                $gdDst, $gdSrc,
                0, 0,
                0, 0,
                $w1, $h1,
                $w0, $h0
            );

            $dirs = explode('/', str_replace(WWW_DIR . '/', '', $filename));
            array_shift($dirs);
            $th_filename = WWW_DIR . '/' . $dir . '/' . implode('/', $dirs);
            U_Misc::mkdir(dirname($th_filename));

            imagejpeg($gdDst, $th_filename, $sizes[2]);
            imagedestroy($gdDst);

            $result[] = $th_filename;
        }

        imagedestroy($gdSrc);

        return $result;
    }

    public static function makeBulks($album_id)
    {
        $imgPerW = 3;
        $imgPerH = 3;

        $mAlbum = new M_Album($album_id);
        $lChildren = $mAlbum->getChildren();

        $childrenId = array();
        foreach ($lChildren as $mAlbum) {
            $childrenId[] = $mAlbum->id;
        }

        $lPhotos = new L_Photos(
            array('album_id' => $childrenId),
            array('rate DESC', 'RANDOM()'),
            $imgPerW * $imgPerH
        );

        foreach (self::$_sizes['album'] as $dir=>$sizes) {

            $width = $imgPerW * $sizes[0] + $imgPerW - 1;
            $height = $imgPerH * $sizes[1] + $imgPerH - 1;

            $gdDst = imagecreatetruecolor($width, $height);
            imagefill($gdDst, 0, 0, imagecolorallocate($gdDst, 240, 230, 140));

            $x1 = $y1 = 0;

            foreach ($lPhotos as $mPhoto) {
                $mAlbum = new M_Album($mPhoto->album_id);
                $lAlbums = $mAlbum->getParents();

                $dirs = array();
                foreach ($lAlbums as $mParent) {
                    $dirs[] = $mParent->name;
                }
                $filedir = WWW_DIR . '/photos/' . implode('/', $dirs) . '/';

                $filename = $filedir . $mPhoto->name;

                if (!file_exists($filename)) {
                    throw new Exception("File doesn't exists: " . $filename);
                }

                $meta = getimagesize($filename);
                $exif = array();

                switch (U_Misc::is($meta[2])) {
                    case IMAGETYPE_JPEG:
                    case IMAGETYPE_JPEG2000:
                        $gdSrc = imagecreatefromjpeg($filename);
                        $exif = exif_read_data($filename, 'IFD0');
                        break;
                    case IMAGETYPE_PNG:
                        $gdSrc = imagecreatefrompng($filename);
                        break;
                    default :
                        throw new Exception("File is not image: " . $filename);
                }

                $w0 = $meta[0];
                $h0 = $meta[1];

                $orientation = U_Misc::is($exif['Orientation'], 1);
                switch ($orientation) {
                    case 3:
                        $gdSrc = imagerotate($gdSrc, 180, 0);
                        break;
                    case 6:
                        $gdSrc = imagerotate($gdSrc, -90, 0);
                        $w0 = $meta[1];
                        $h0 = $meta[0];
                        break;
                    case 8:
                        $gdSrc = imagerotate($gdSrc, 90, 0);
                        $w0 = $meta[1];
                        $h0 = $meta[0];
                        break;
                }

                if ($w0 > $h0) {
                    $x0 = ($w0-$h0) / 2;
                    $y0 = 0;
                    $w0 = $h0;
                } else {
                    $y0 = ($h0 - $w0) / 2;
                    $x0 = 0;
                    $h0 = $w0;
                }

                imagecopyresampled(
                    $gdDst,
                    $gdSrc,
                    $x1,
                    $y1,
                    $x0,
                    $y0,
                    $sizes[0],
                    $sizes[1],
                    $w0,
                    $h0
                );

                imagedestroy($gdSrc);

                $x1 += $sizes[0] + 1;
                if ($x1 >= $width) {
                    $y1 += $sizes[1] + 1;
                    $x1 = 0;
                }
            }

            $blk_filename = WWW_DIR . '/' . $dir . '/' . $album_id . '.jpg';
            U_Misc::mkdir(dirname($blk_filename));

            imagejpeg($gdDst, $blk_filename, $sizes[2]);

            imagedestroy($gdDst);
        }
    }
}