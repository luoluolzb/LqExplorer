<?php
/**
 * 简单文件管理器
 */
class FileManager
{
	private $dirName;

	/**
	 * 构造函数
	 */
	public function __construct($dirName)
	{
		$this->dirName = $dirName;
	}

	private function getFileInfo($fileName)
	{
		$fullName = $this->dirName . $fileName;
		$info = [
			'name' => $fileName,
			'path' => $fullName,
			'basename' => $this->dirName,
		];
		$file = new File($fullName);
		if (is_dir($fullName)) {
			$info['type'] = 'dir';
		} else {
			$pathinfo = pathinfo($fullName);
			$info['extension'] = $file->exten();
			$info['size'] = $file->size();
			$info['size_string'] = $file->descSize();
			$info['mime'] = $file->mime();
			$info['type'] = $file->type();
		}
		$info['perms'] = $file->perms();
		$info['perms_string'] = $file->descPerms();

		$info['mtime'] = filemtime($fullName);
		$info['mtime_string'] = $this->local_time_string($info['mtime']);

		$info['ctime'] = filectime($fullName);
		$info['ctime_string'] = $this->local_time_string($info['ctime']);

		$info['atime'] = fileatime($fullName);
		$info['atime_string'] = $this->local_time_string($info['atime']);
		return $info;
	}
	
	/**
	 * 获取当前目录下的文件列表
	 * @return array                文件列表
	 */
	public function getFileList()
	{
		$res_dir = [];
		$res_file = [];
		$handle = opendir($this->dirName);
		if (!$handle) {
			return $res_dir;
		}
		while($name = readdir($handle)){
			if ($name == '.' || $name == '..') {
				continue;
			}
			$info = $this->getFileInfo($name);
			if ($info['type'] == 'dir') {
				$res_dir[] = $info;
			} else {
				$res_file[] = $info;
			}
		}
		closedir($handle);
		return array_merge($res_dir, $res_file);
	}

	public function mkdir($dirName)
	{
		return mkdir(self::getFullName($dirName));
	}

	public function rename($name, $newName)
	{
		return rename(self::getFullName($name), self::getFullName($newName));
	}

	public function unzip($fileName, $toName = "")
	{
		$exp = explode('.', $fileName);
		$ext = end($exp);
		if(strtolower($ext) != 'zip'){
			return FALSE;
		}

		$fullName = self::getFullName($fileName);
		if(!file_exists($fullName)){
			return FALSE;
		}

		$zipArc = new ZipArchive();
		if (!$zipArc->open($fullName)) {
			return FALSE;
		}
		if (!$zipArc->extractTo($toName == "" ? $this->dirName : self::getFullName($toName))){ 
			$zipArc->close();
			return FALSE;
		}
		return $zipArc->close();
	}

	public function zip($fromName, $toName = "")
	{
		$toName = self::getFullName($toName);
		$zipArc = new ZipArchive();
		if (!$zipArc->open($toName, ZipArchive::CREATE)) {
			return FALSE;
		}
		foreach ($fromName as $i => $name) {
			self::_addFileToZip($zipArc, "", self::getFullName($name));
		}
		return $zipArc->close();
	}

	private function _addFileToZip(& $zipArc, $dir, $name)
	{
		if (is_file($name)) {
			$localName = ($dir == "" ? basename($name) : $dir.'/'.basename($name));
			$zipArc->addFile($name, $localName);
		} else {
			$dir = ($dir == "" ? basename($name) : $dir.'/'.basename($name));
			$handle = opendir($name);
			while($fileName = readdir($handle)){
				if($fileName == '.' || $fileName == '..'){
					continue;
				}
				self::_addFileToZip($zipArc, $dir, $name.'/'.$fileName);
			}
			closedir($handle);
		}
	}
	
	/**
	 * 删除当前目录下的一个文件或目录
	 * @param  string|array $name 文件名或文件列表
	 * @return bool         true成功, false失败
	 */
	public function delete($name)
	{
		if (is_string($name)) {
			return self::_delete($this->getFullName($name));
		} else if(is_array($name)) {
			$ret = true;
			foreach ($name as $i => $fileName) {
				$ret = $ret && self::_delete($this->getFullName($fileName));
			}
			return $ret;
		}
		return false;
	}

	/**
	 * 删除当前目录下的一个文件或目录
	 * @param  string $name 文件或目录名
	 * @return bool         true成功, false失败
	 */
	private function _delete($fullName)
	{
		if (is_file($fullName)) {
			return unlink($fullName);
		} else if (is_dir($fullName)) {
			$handle = opendir($fullName);
			while($name = readdir($handle)){
				if($name == '.' || $name == '..'){
					continue;
				}
				self::_delete($fullName.'/'.$name);
			}
			closedir($handle);
			return rmdir($fullName);
		}
		return false;
	}
	
	/**
	 * 获取当前目录下的一个文件完整路径
	 * @param  string $name 文件名
	 * @return string       完整文件名
	 */
	public function getFullName($name)
	{
		$fullName = $this->dirName . $name;
		return $fullName;
	}

	protected function local_time_string($time)
	{
		return strftime('%Y/%m/%d %H:%M', $time);
	}
}
