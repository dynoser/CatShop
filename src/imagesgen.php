<?php
namespace dynoser\catshop;

if (!$conf) {
    die("This script is not intended to be called on its own");
}

require_once 'ImagesRandomGen.php';

// create folder for images
$images_folder = realpath('../www/'); 
if (!$images_folder) die("Check path in " . __FILE__);
$images_folder .= '/images';

if (!is_dir($images_folder) && !mkdir($images_folder)) {
    die("Can't create image folder: $images_folder");
}

new ImagesRandomGen($images_folder, 'product',10, 128, 128);
