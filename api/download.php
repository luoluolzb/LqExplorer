<?php
require __DIR__ . '/../start.php';
require __DIR__ . '/echofile.php';

if(!User::IsLogined()){
	Response::responseData(100);
}

$args = RequestArg::validate(
	array('dir', 'fileName'),
	array(),
	array(
		'errcode' => 1
	)
);
if(!is_array($args)){
	Header("HTTP/1.1 404 Not Found");
} else{
	if (strtolower(substr(PHP_OS, 0, 3)) != 'win') {
		RequestArg::Utf8toGb($args);
	}
	EchoFile($args['dir'], $args['fileName']);
}
