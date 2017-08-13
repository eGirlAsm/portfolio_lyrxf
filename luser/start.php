<?php



	define('LUSER_START',microtime(true));
	
	Session::_init();

	require __DIR__.'/../app/routes.php';

	

	$app = new Application();
	
	$app->bindInstallPaths(require __DIR__.'/paths.php');

	return $app;
