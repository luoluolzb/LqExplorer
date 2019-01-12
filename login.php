<?php
require __DIR__ . '/start.php';

if(User::IsLogined()){
	User::SavePassword();
	header("Location: index.php");
}

$view = View::instance();
$view->setTemplateDir(TPL_PATH);

$password = $errmsg = '';
if(isset($_REQUEST['password'])){
	$password = $_REQUEST['password'];
	if(User::CheckPassword($password)){
		User::SavePassword();
		header("Location: index.php");
	}else{
		$errmsg =  '密码错误';
	}
}
$view->assign('password', $password);
$view->assign('errmsg', $errmsg);
$view->display('login.tpl');
