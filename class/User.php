<?php
/**
 * 
  * 用户管理类
 */
class User
{
	public static function IsLogined()
	{
		if(! NEED_PASS){
			return true;
		}
		else{
			return isset($_COOKIE['password']) && ($_COOKIE['password']) == md5(PASSWORD);
		}
	}

	public static function CheckPassword($password)
	{
		return $password == PASSWORD;
	}

	public static function SavePassword()
	{
		setcookie('password', md5(PASSWORD));
	}
}
