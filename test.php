<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);


$testUrl ="https://stackoverflow.com/questions/3080146/post-data-to-a-url-in-php";

$baseUrl = "https://naptudong.com/chargingws/v2";

echo file_get_contents($baseUrl);
