<?php

function EchoFile($dir, $fileName, $attachment = true)
{
	set_time_limit(0);
	$filename = $dir . $fileName;
	$file = new File($filename);
	$type = $file->type();
	$handle = fopen($filename, "rb");
	if (false === $handle) {
		Header("HTTP/1.1 404 Not Found");
		exit();
	}
	header('Content-Type: ' . $file->mime());
	header('Content-Length: ' . $file->size());
	header("Accept-Ranges: bytes");
	header("Accept-Length: " . $file->size());
	if ($attachment) {
		//$temp = mb_convert_encoding($fileName, 'ISO8859-1', 'UTF-8');
		header("Content-Disposition: attachment; filename=" . $fileName); 
	}
	$contents = '';
	while (!feof($handle)) {  
	    $contents = fread($handle, 12288); 
	    echo $contents;  
	    @ob_flush();
	    flush();
	}
	fclose($handle);
}

