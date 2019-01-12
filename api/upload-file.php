<?php
require __DIR__ . '/../start.php';

if(!User::IsLogined()){
	Response::responseData(100);
}

$args = RequestArg::validate(
	array('saveDir'),
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
	$ufm = new UploadFileHandler();
	if ($ufm->load('file') && $ufm->save($args['saveDir'])) {
		Response::responseData(0);
	} else {
		Response::responseData(2);
	}
}
