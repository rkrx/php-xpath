<?php
require __DIR__ . '/../vendor/autoload.php';

chdir(dirname(__DIR__));

spl_autoload_register(function ($className) {
	$filename = __DIR__ . "/{$className}.php";
	$filename = str_replace('\\', DIRECTORY_SEPARATOR, $filename);
	if(file_exists($filename)) {
		include $filename;
	}
});