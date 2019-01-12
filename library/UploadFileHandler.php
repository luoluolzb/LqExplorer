<?php

/**
 * 文件上传处理器类
* @author luoluo <luoluolzb@163.com>
 */
class UploadFileHandler{
	private $finfo;

	public function __construct(){
		$this->finfo = FALSE;
	}

	/**
	 * 加载一个上传文件
	 * @param  string $fileField 文件的input字段
	 * @return bool              加载成功与否
	 */
	public function load($fileField){
		if($_FILES[$fileField]['error'] != 0){
			return FALSE;
		}
		$this->finfo = $_FILES[$fileField];
		$this->finfo['tmp_name'] = mb_convert_encoding($this->finfo['tmp_name'], 'GBK', 'UTF-8');
		$this->finfo['name'] = mb_convert_encoding($this->finfo['name'], 'GBK', 'UTF-8');
		return TRUE;
	}

	/**
	 * 验证上传文件
	 * @param  integer $minSize  文件大小下限
	 * @param  integer $maxSize  文件大小上限
	 * @param  string $mime_type 文件MIME类型
	 * @return bool              验证成功与否
	 */
	public function validate($minSize, $maxSize, $mime_type){
		if(! $this->finfo){
			return FALSE;
		}
		
		$size = & $this->finfo['size'];
		if($size < $minSize || $size >= $maxSize){
			return FALSE;
		}
		if($this->finfo['type'] != $mime_type){
			return FALSE;
		}
		return TRUE;
	}

	/**
	 * 保存上传文件
	 * @param  string $dirName  保存路径
	 * @param  string $fileName 保存文件名
	 * @return bool             保存成功与否
	 */
	public function save($dirName = "", $fileName = ""){
		if(! $this->finfo){
			return FALSE;
		}

		if($fileName == ""){
			$fileName = & $this->finfo['name'];
		}
		if($dirName != ""){
			$dirName .= "/";
		}
		return move_uploaded_file($this->finfo['tmp_name'], $dirName.$fileName);
	}
}

?>