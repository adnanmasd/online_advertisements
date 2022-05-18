<?php

class View_image {

    public function __construct() {
        if (!isset($_GET['path'])) {
            header("Location: " . "/ad_site/404.php");
        } else {
            $this->thumbnailImage($_GET['path'], ($_GET['w'] ? $_GET['w'] : 300), ($_GET['h'] ? $_GET['h'] : 300));
        }
    }

    private function thumbnailImage($imagePath, $w, $h) {
        if (preg_match('/[.](jpe?g)$/', $_SERVER['DOCUMENT_ROOT'] . $imagePath)) {
            $image = imagecreatefromjpeg($_SERVER['DOCUMENT_ROOT'] . $imagePath);
        }
        list($width, $height) = getimagesize($_SERVER['DOCUMENT_ROOT'] . $imagePath);
        $thumbImage = imagecreatetruecolor($w, $h);
        imagecopyresized($thumbImage, $image, 0, 0, 0, 0, $w, $h, $width, $height);
        imagedestroy($image);
        ob_end_clean();
        header('Content-Type: image/jpeg');
        imagejpeg($thumbImage);
        imagedestroy($thumbImage);
    }

}

new View_image();

