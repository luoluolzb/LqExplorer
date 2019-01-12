<?php
/**
 * @description 字符串加密/解密类(原版来自网络)
 * @author      luoluolzb
 */

class llAuthcode
{
    private static $_securekey = '60f7AGH16C649f0e9f50737d57Ybb862';

	/**
	 * 字符串加密
	 */
	public static function encode($str)
	{
		return self::authcode($str, 'encode');
	}

	/**
	 * 字符串解密
	 */
	public static function decode($str)
	{
		return self::authcode($str, 'decode');
	}

    /** 加密/解密数据 
    * @param  String $str       原文或密文 
    * @param  String $operation ENCODE or DECODE 
    * @param  String $key       密匙
    * @return String            根据设置返回明文活密文 
    */  
    protected static function authcode($string, $operation = 'decode', $key = null)
    {
        $ckey_length = 4;   // 随机密钥长度 取值 0-32;  
        $key = isset($key) ? $key : self::$_securekey;
        $key = md5($key);  
        $keya = md5(substr($key, 0, 16));  
        $keyb = md5(substr($key, 16, 16));  
        $keyc = $ckey_length ? ($operation == 'decode' ? substr($string, 0, $ckey_length): substr(md5(microtime()), -$ckey_length)) : '';  
  
        $cryptkey = $keya.md5($keya.$keyc);  
        $key_length = strlen($cryptkey);  
  
        $string = $operation == 'decode' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', 0).substr(md5($string.$keyb), 0, 16).$string;  
        $string_length = strlen($string);  
  
        $result = '';  
        $box = range(0, 255);  
  
        $rndkey = array();  
        for($i = 0; $i <= 255; $i++) {  
            $rndkey[$i] = ord($cryptkey[$i % $key_length]);  
        }  
  
        for($j = $i = 0; $i < 256; $i++) {  
            $j = ($j + $box[$i] + $rndkey[$i]) % 256;  
            $tmp = $box[$i];  
            $box[$i] = $box[$j];  
            $box[$j] = $tmp;  
        }  
  
        for($a = $j = $i = 0; $i < $string_length; $i++) {  
            $a = ($a + 1) % 256;  
            $j = ($j + $box[$a]) % 256;  
            $tmp = $box[$a];  
            $box[$a] = $box[$j];  
            $box[$j] = $tmp;  
            $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));  
        }  
  
        if($operation == 'decode') {  
            if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16)) {  
                return substr($result, 26);  
            } else {  
                return '';  
            }  
        } else {  
            return $keyc.str_replace('=', '', base64_encode($result));  
        }
    }
}
