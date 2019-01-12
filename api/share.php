<?php
require __DIR__ . '/../start.php';
require __DIR__ . '/echofile.php';

$args = RequestArg::validate(
	array('token'),
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
	$token = ($args['token']);
	$path = llAuthcode::decode($token);
	$info = pathinfo($path);
	EchoFile($info['dirname'] . DS, $info['basename']);
}
