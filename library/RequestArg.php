<?php

/**
* 参数验证器
* 验证客户端传递的数据
* @author luoluo <luoluolzb@163.com>
*/
class RequestArg
{
	/**  
	* 参数验证
	* 
	* @access public
	* @param array $nec_args 必要参数名
	* @param array $opt_args 可选参数名
	* @param array $val_datas 参数验证信息
	* $val_datas格式:
	* array(
	* 	'errcode' => $errcode, //必须参数不存时返回码, 返回码不能取0
	* 	array(
	* 		'name' => '$name',
	* 		'type' => 'not_none' | string' | 'number' | 'email' | 'enum'
	* 		'range' => array(0, 10) | array(1, 20)| array() | array(enum1, enum2, enum3)
	* 		'errcode' =>  //参数验证不通过时返回码, 返回码不能取0
	* 	),
	* 	...
	* )
	* @return array | integer
	*/
	public static function validate($nec_args, $opt_args, $val_datas)
	{
		$nameIndex = array();
		foreach ($val_datas as $key => $value){
			$nameIndex[$value['name']] = $key;
		}

		$ret = array();
		foreach($nec_args as $k => $key){
			if(isset($_REQUEST[$key])){
				$value = $_REQUEST[$key];
				if(isset($nameIndex[$key])){
					$errcode = self::validate_one($value, $val_datas[$nameIndex[$key]]);
					if($errcode != 0){
						return $errcode;
					}
				}
				$ret[$key] = $value;
			}
			else{
				return $val_datas['errcode'];
			}
		}

		foreach($opt_args as $k => $key){
			if(isset($_REQUEST[$key])){
				$value = $_REQUEST[$key];
				if(isset($nameIndex[$key])){
					$errcode = self::validate_one($value, $val_datas[$nameIndex[$key]]);
					if($errcode != 0){
						return $errcode;
					}
				}
				$ret[$key] = $value;
			}
		}

		return $ret;
	}

	/**
	* 验证一个参数
	* 
	* @access public
	* @param mixed $value 参数值
	* @param array $val_date 验证信息
	* $val_data格式:
	* array(
	* 	'name' => '$name',
	* 	'type' => 'not_none' | string' | 'number' | 'email' | 'enum'
	* 	'range' => array(0, 10) | array(1, 20)| array() | array(enum1, enum2, enum3)
	* 	'errcode' =>  //参数验证不通过时返回码, 返回码不能取0
	* )
	*/
	public static function validate_one($value, $val_data){
		@$range = $val_data['range'];
		$errcode = $val_data['errcode'];

		switch ($val_data['type']){
			case 'not_none':
				if($value == ''){
					return $errcode;
				}
			break;

			case 'string':
				if(!self::validate_string($value, $range['0'], $range['1'])){
					return $errcode;
				}
			break;

			case 'number':
				if(!self::validate_number($value, $range['0'], $range['1'])){
					return $errcode;
				}
			break;

			case 'email':
				if(!self::validate_email($value)){
					return $errcode;
				}
			break;

			case 'enum':
				if(!self::validate_enum($value, $range)){
					return $errcode;
				}
			break;

			default:
				return 0;
			break;
		}

		return 0;
	}

	/**
	* 验证全数字
	* 长度验证：minN <= len <= maxN
	*/
	public static function validate_number($string, $minLen, $maxLen){
		$len = strlen($string);
		return is_numeric($string) && $minLen <= $len && $len <= $maxLen;
	}

	/**
	* 验证字符串
	* 字符串不能包含转义符
	* 长度验证：minN <= len <= maxN
	*/
	public static function validate_string($string, $minLen, $maxLen){
		$len = strlen($string);
		return $minLen <= $len && $len <= $maxLen;
	}

	/**
	* 验证邮箱
	*/
	public static function validate_email($string){
		return preg_match('/(\w+\.)*\w+@(\w+\.)+[A-Za-z]+/', $string);
	}

	/**
	* 验证枚举变量
	*/
	public static function validate_enum($string, $range){
		foreach ($range as $i => $value){
			if($string == $range){
				return true;
			}
		}
		return false;
	}

	/**
	 * 把参数转换从Utf8成GB编码
	 */
	public static function Utf8toGB(&$args){
		foreach ($args as &$value) {
			if(is_array($value)){
				self::Utf8toGB($value);
			}
			else{
				$value = mb_convert_encoding($value, 'GBK', 'UTF-8');
			}
		}
	}

	/**
	 * 把参数转换从GB成Utf8编码
	 */
	public static function GbtoUtf8(&$args){
		foreach ($args as &$value) {
			if(is_array($value)){
				self::GbtoUtf8($value);
			} else{
				$value = mb_convert_encoding($value, 'UTF-8', 'GBK');
			}
		}
	}
}
