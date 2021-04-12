<?php
function autoloader($className) {
	$fileName = str_replace('\\', '/', $className) . '.php';

	$file = __DIR__ . '/../../' . $fileName;

	if(file_exists($file))
	{
		include $file;
		return true;
	}

	$file = __DIR__ . '/../../vendor/classes/' . $fileName;
	if(file_exists($file))
	{
		include $file;
		return true;
	}

	return false;

}

spl_autoload_register('autoloader');