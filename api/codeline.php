<?php
require __DIR__ . '/../start.php';

if(!User::IsLogined()){
	Response::responseData(100);
}

$args = RequestArg::validate(
	array('dir', 'fileList'),
	array(),
	array(
		'errcode' => 1
	)
);
if (!is_array($args)) {
	Response::responseData($args);
} else {
	if (strtolower(substr(PHP_OS, 0, 3)) != 'win') {
		RequestArg::Utf8toGb($args);
	}
	$sfm = new FileManager($args['dir']);
	$data = [];
	$codeLine = new CodeLineInfo();
	foreach ($args['fileList'] as $fileName) {
		$fullName = $sfm->getFullName($fileName);
		$info = $codeLine->Proccess($fullName);
		if (isset($info['code_count'])) {
			$codeCount = &$info['code_count'];
			foreach ($codeCount as $format => $count) {
				if (isset($data[$format])) {
					$data[$format] += $codeCount[$format];
				} else {
					$data[$format] = $codeCount[$format];
				}
			}
		}
	}
	RequestArg::GbtoUtf8($data);
	Response::responseData(0, $data);
}
