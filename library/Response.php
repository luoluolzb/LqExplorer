<?php

/**
* 类功能：用指定格式返回数据
* @author luoluo <luoluolzb@163.com>
* 支持的格式：json, jsonp, xml
**/
class Response
{
	/**
	 * 响应数据给客户端
	 * @param  integer $errcode 状态码
	 * @param  array  $data     返回的数据
	 * @param  string $type     返回数据格式josn/xml/jsonp
	 * @param  string $callback jsonp头部函数名
	 * @return no return
	 */
	public static function responseData($errcode, $data = array(), $type = 'json', $callback = ''){
		switch ($type){
			case 'json':
				self::response_json($errcode, $data);
			break;

			case 'jsonp':
				self::response_jsonp($errcode, $data, $callback);
			break;

			case 'xml':
				self::response_xml($errcode, $data);
			break;
			
			default:
				self::response_json($errcode, $data);
			break;
		}
		exit();
	}

	/**
	 * 响应json数据给客户端
	 * @param  integer $errcode 状态码
	 * @param  array  $data     返回的数据
	 * @return no return
	 * 
	 * json格式：
	 * {
	 * 	'errcode': (integer)
	 * 	'data': {
	 * 		...
	 * 	}
	 * }
	 */
	public static function response_json($errcode, $data = array()){
		$res = array(
			'errcode' => $errcode,
			'data' => $data
		);
		header('Content-Type: application/json');
		echo json_encode($res);
	}

	/**
	 * 响应jsonp数据给客户端
	 * @param  integer $errcode 状态码
	 * @param  array  $data     返回的数据
	 * @param  string $callback jsonp头部函数名
	 * @return no return
	 */
	public static function response_jsonp($errcode, $data = array(), $callback){
		$res = array(
			'errcode' => $errcode,
			'data' => $data
		);
		header('Content-Type: text/jsonp');
		echo $callback.'('.json_encode($res).')';
	}

	/**
	 * 响应xml数据给客户端
	 * @param  integer $errcode 状态码
	 * @param  array  $data     返回的数据
	 * @return no return
	 * 
	 * xml格式：
	 * <?xml version='1.0' encoding='utf-8'?>
	 * <xml>
	 * <errcode>{$errcode}</errcode>
	 * <data>
	 * 	...
	 * </data>
	 * </xml>
	 */
	public static function response_xml($errcode, $data = array()){
		header('Content-Type: text/xml');
		echo "<?xml version='1.0' encoding='utf-8'?>\n";
		echo "<xml>\n";
		echo "<errcode>{$errcode}</errcode>\n";
		echo "<data>\n";
		self::_print_xml_code($data);
		echo "</data>\n";
		echo "</xml>";
	}

	private static function _print_xml_code($data){
		foreach ($data as $key => $value){
			$attr = "";
			if(is_numeric($key)){
				$attr = " id='{$key}'";
				$key = 'item';
			}

			echo "<{$key}{$attr}>";
			if(is_array($value)){
				echo "\n";
				self::_print_xml_code($value);
			}else{
				echo $value;
			}
			echo "</{$key}>\n";
		}
	}
}
?>