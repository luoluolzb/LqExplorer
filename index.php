<?php
require __DIR__ . '/start.php';

if(!User::IsLogined()){
	header("Location: login.php");
}

$view = View::instance();
$view->setTemplateDir(TPL_PATH);
$view->assign('root_path', ROOT_PATH);
$view->display('index.tpl');
