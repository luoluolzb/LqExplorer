<?php
/**
 * 参数配置(根据需要修改)
 */
//开启错误显示
ini_set('display_errors', true);

//访问需要密码
define('NEED_PASS', false);

//访问密码
define('PASSWORD', 'password');  

//根目录
define('ROOT_PATH', $_SERVER['DOCUMENT_ROOT'] . '/');

/**
 * 目录定义
 */
define('DS', DIRECTORY_SEPARATOR);
define('WEB_PATH', __DIR__ . '/');
define('LIB_PATH', WEB_PATH . 'library/');
define('TPL_PATH', WEB_PATH . 'static/tpl/');
define('CLASS_PATH', WEB_PATH . 'class/');

/**
 * 时区设置
 * PRC: People's Republic of China
 */
date_default_timezone_set('PRC');

require LIB_PATH . 'Loader.php';
Loader::autoload('', LIB_PATH);
Loader::autoload('', CLASS_PATH);
