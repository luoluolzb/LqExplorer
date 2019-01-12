<?php
require __DIR__ . '/../start.php';

if(!User::IsLogined()){
	Response::responseData(100);
}

$args = RequestArg::validate(
	array('dir'),
	array(),
	array(
		'errcode' => 1
	)
);
if(!is_array($args)){
	Response::responseData($args, array());
} else{
	if (strtolower(substr(PHP_OS, 0, 3)) != 'win') {
		RequestArg::Utf8toGb($args);
	}
	$sfm = new FileManager($args['dir']);
	$fileList = $sfm->getFileList();
	if (strtolower(substr(PHP_OS, 0, 3)) != 'win') {
		RequestArg::GbtoUtf8($fileList);
	}
	Response::responseData(0, $fileList);
}
