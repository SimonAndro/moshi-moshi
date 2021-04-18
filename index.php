<?php
try {
	include __DIR__ . '/app/includes/autoload.php';
	include __DIR__ . '/app/includes/utils.php';
	require 'vendor/autoload.php';

	define("APP_BASE_PATH", __DIR__);

	getAppConfig('site-title');

	$request = $_SERVER['REQUEST_URI'];
    
    if(empty($request) or $request =="/" or $request=="/?i=1"){ // specific to infinity free webhost
        header("location: home");
    } 
	$route = getRoute($request);

	$entryPoint = new \Ninja\EntryPoint($route, $_SERVER['REQUEST_METHOD'], new \app\appRoutes());
 
	$entryPoint->run();
}
catch (PDOException $e) {

	$title = 'An error has occurred';

	$output = 'Database error: ' . $e->getMessage() . ' in ' .
	$e->getFile() . ':' . $e->getLine();


	include  __DIR__ . '/app/views/error/error.html.php';
}
