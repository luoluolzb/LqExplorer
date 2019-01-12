<?php
/**
 * @description 自动加载类(psr-4)
 * @author      luoluolzb
 */

class Loader
{
	/**
	 * 注册一个自动加载
	 */
	public static function autoload($namespace, $dir) {
		spl_autoload_register(function($className) use ($namespace, $dir){
			if ($namespace == '') {
				$file = str_replace('\\', DS, $dir . $className) . '.php';
				if (file_exists($file)) {
					require $file;
				}
			} else if (0 === strpos($className, $namespace)) {
				$file = str_replace('\\', DS, realpath($dir) . str_replace($namespace, '', $className)) . '.php';
				if (file_exists($file)) {
					require $file;
				}
			}
		});
	}

	/**
	 * 加载一个函数库
	 * @param  string $path
	 */
	public static function loadfile($path)
	{
		if (file_exists($path)) {
			require($path);
			return true;
		} else {
			return false;
		}
	}
}
