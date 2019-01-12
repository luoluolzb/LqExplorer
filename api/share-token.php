<?php
require __DIR__ . '/../start.php';

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
	Response::responseData($args, array());
} else {
	if (strtolower(substr(PHP_OS, 0, 3)) != 'win') {
		RequestArg::Utf8toGb($args);
	}
	$filename = $args['dir'] . $args['fileName'];
	Response::responseData(0, [
		'token' => urlencode(llAuthcode::encode($filename)),
	]);
}
