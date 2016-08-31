<?php
//production server
// $path = $_SERVER['DOCUMENT_ROOT'] . '/../php/';
// $path = realpath($php_path) . '/';

//localhost
$path = realpath(__DIR__);
$path = $path . "/../../../php/";

include($path . 'process_ajax.php');
