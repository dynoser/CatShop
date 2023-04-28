<?php
namespace dynoser\catshop;

class ImagesRandomGen {
    public function __construct(
        $folder,
        $name_prefix = 'product', // Output folder
        $num_images = 10, // How many images will created
        $width = 200,
        $height = 200
    ) {
        for ($i = 1; $i <= $num_images; $i++) {
            // Create image
            $image = imagecreatetruecolor($width, $height);

            // Fill random
            $color = imagecolorallocate($image, rand(0, 255), rand(0, 255), rand(0, 255));
            imagefill($image, 0, 0, $color);

            // Make image file name
            $filename = $folder . DIRECTORY_SEPARATOR . $name_prefix . $i . '.jpg';

            // Save image
            imagejpeg($image, $filename, 100);

            imagedestroy($image);
        }
    }
}
