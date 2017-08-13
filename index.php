<?php

//设置时区

date_default_timezone_set ( 'PRC' );
ob_start();
if (version_compare ( PHP_VERSION, '5.3.0', '<' ))

	die ( 'Your PHP Version is ' . PHP_VERSION . ', But WeiPHP require PHP > 5.3.0 !' );




//设置错误级别

//error_reporting(0);



define('BASE_PATH',__DIR__);



define('APP',__DIR__.'/app');



require __DIR__.'/luser/autoload.php';





$app = require_once __DIR__.'/luser/start.php';









$app->run();

