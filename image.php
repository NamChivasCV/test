<?php
header('Content-type: image/jpeg');
$filename = isset($_GET['u']) ? $_GET['u'] : "";
$resize_width = isset($_GET['w']) ? $_GET['w'] : 300;
if (empty($filename)) {
    die("");
}
list($width, $height) = getimagesize($filename);
if ($width <= $resize_width) {
    $new_width = $width;
    $new_height = $height;
} else {
    $percent_scale = $resize_width / $width;
    $new_width = $width * $percent_scale;
    $new_height = $height * $percent_scale;
}
$image_p = imagecreatetruecolor($new_width, $new_height);
$image = imagecreatefromjpeg($filename);
imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
imagejpeg($image_p, null, 100);
