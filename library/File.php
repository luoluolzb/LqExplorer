<?php
/**
 * @description 文件类
 * @author      luoluolzb
 */

class File
{
	/**
	 * 后缀对应的mime类型
	 * @var array
	 */
	protected static $mime_list = [
	    //applications
	    'ai'  => 'application/postscript',
	    'eps'  => 'application/postscript',
	    'exe'  => 'application/octet-stream',
	    'doc'  => 'application/vnd.ms-word',
	    'xls'  => 'application/vnd.ms-excel',
	    'ppt'  => 'application/vnd.ms-powerpoint',
	    'pps'  => 'application/vnd.ms-powerpoint',
	    'pdf'  => 'application/pdf',
	    'xml'  => 'application/xml',
	    'odt'  => 'application/vnd.oasis.opendocument.text',
	    'swf'  => 'application/x-shockwave-flash',
	    
	    //archives
	    'gz'  => 'application/x-gzip',
	    'tgz'  => 'application/x-gzip',
	    'bz'  => 'application/x-bzip2',
	    'bz2'  => 'application/x-bzip2',
	    'tbz'  => 'application/x-bzip2',
	    'zip'  => 'application/zip',
	    'rar'  => 'application/x-rar',
	    'tar'  => 'application/x-tar',
	    '7z'  => 'application/x-7z-compressed',
	    
	    //texts
	    'txt'  => 'text/plain',
	    'php'  => 'text/x-php',
	    'html' => 'text/html',
	    'htm'  => 'text/html',
	    'js'  => 'text/javascript',
	    'css'  => 'text/css',
	    'rtf'  => 'text/rtf',
	    'rtfd' => 'text/rtfd',
	    'py'  => 'text/x-python',
	    'java' => 'text/x-java-source',
	    'rb'  => 'text/x-ruby',
	    'sh'  => 'text/x-shellscript',
	    'pl'  => 'text/x-perl',
	    'sql'  => 'text/x-sql',
	    
	    //images
	    'bmp'  => 'image/x-ms-bmp',
	    'jpg'  => 'image/jpeg',
	    'jpeg' => 'image/jpeg',
	    'gif'  => 'image/gif',
	    'png'  => 'image/png',
	    'tif'  => 'image/tiff',
	    'tiff' => 'image/tiff',
	    'tga'  => 'image/x-targa',
	    'psd'  => 'image/vnd.adobe.photoshop',
	    
	    //audio
	    'mp3'  => 'audio/mpeg',
	    'mid'  => 'audio/midi',
	    'ogg'  => 'audio/ogg',
	    'mp4a' => 'audio/mp4',
	    'wav'  => 'audio/wav',
	    'wma'  => 'audio/x-ms-wma',
	    
	    //video
	    'avi'  => 'video/x-msvideo',
	    'dv'   => 'video/x-dv',
	    'mp4'  => 'video/mp4',
	    'mpeg' => 'video/mpeg',
	    'mpg'  => 'video/mpeg',
	    'mov'  => 'video/quicktime',
	    'wm'   => 'video/x-ms-wmv',
	    'flv'  => 'video/x-flv',
	    'mkv'  => 'video/x-matroska'
	 ];

	/**
	 * 保存mime获取环境结果
	 * @var string
	 */
	protected static $_mime_detect = null;

	/**
	 * 文件信息
	 * @var array
	 */
	protected $file;

	/**
	 * 文件mime
	 * @var array
	 */
	protected $mime;

	/**
	 * 构造函数
	 * @param $file string 文件路径
	 */
	public function __construct($file)
	{
		$this->file = $file;
		$this->mime = null;
	}

	/**
	 * 获取文件mime类型
	 * @param  string $file 文件路径
	 * @return string
	 */
	public function mime()
	{
		$file = &$this->file;
		$fmime = $this->_mime_detect();
		switch ($fmime) {
			case 'finfo':
				$finfo = finfo_open(FILEINFO_MIME);
				if ($finfo) 
				$type = @finfo_file($finfo, $file);
				break;

			case 'mime_content_type':
				$type = mime_content_type($file);
				break;

			case 'linux':
				$type = exec('file -ib '. escapeshellarg($file));
				break;

			case 'bsd':
				$type = exec('file -Ib '. escapeshellarg($file));
				break;

			default:
				$pinfo = pathinfo($file);
				$ext = isset($pinfo['extension']) ? strtolower($pinfo['extension']) : '';
				$type = isset(self::$mime_list[$ext]) ? self::$mime_list[$ext] : 'unkown';
				break;
		}

		$type = explode(';', $type);
		//需要加上这段，因为如果使用mime_content_type函数来获取一个不存在的$path时会返回'application/octet-stream'
		if ($fmime != 'internal' && $type[0] == 'application/octet-stream') {
			$pinfo = pathinfo($file); 
			$ext = isset($pinfo['extension']) ? strtolower($pinfo['extension']) : '';
			if (!empty($ext) && !empty(self::$mime_list[$ext])) {
				$type[0] = self::$mime_list[$ext];
			}
		}
		return $this->mime = $type[0];
	}

	/**
	 * mime环境检测
	 * @return string
	 */
	private function _mime_detect()
	{
		if (isset(self::$_mime_detect)) {
			return self::$_mime_detect;
		}
		if (class_exists('finfo')) {
			return self::$_mime_detect = 'finfo';
		} else if (function_exists('mime_content_type')) {
			return self::$_mime_detect = 'mime_content_type';
		} else if ( function_exists('exec')) {
			$result = exec('file -ib '. escapeshellarg(__FILE__));
			if (0 === strpos($result, 'text/x-php') || 0 === strpos($result, 'text/x-c++')) {
				return self::$_mime_detect = 'linux';
			}
			$result = exec('file -Ib '. escapeshellarg(__FILE__));
			if (0 === strpos($result, 'text/x-php') || 0 === strpos($result, 'text/x-c++')) {
				return self::$_mime_detect = 'bsd';
			}
		}
		return self::$_mime_detect = 'internal';
	}

	/**
	 * 获取文件类型
	 * @param  stirng $mime  文件mime
	 * @return string
	 */
	public function type()
	{
		if (is_null($this->mime)) {
			$this->mime();
		}
		if ($this->mime == 'unkown') {
			return $this->exten();
		} else {
			$temp = explode('/',  $this->mime);
			return $this->type = $temp[0];
		}
	}

	/**
	 * 获取文件扩展名(不包括'.')
	 * @return string
	 */
	public function exten()
	{
		$dot = strrpos($this->file, '.');
		return substr($this->file, $dot + 1);
	}

	/**
	 * 获取文件大小(字节)
	 * @return string
	 */
	public function size()
	{
		return filesize($this->file);
	}

	/**
	 * 获取文件大小的描述
	 * @return string
	 */
	public function descSize($mark = ' ')
	{
		$size = filesize($this->file);
		if($size < 1024){
			$num = $size;
			$unit = 'B';
		} else if($size < 1048576){
			$num = $size/1024;
			$unit = 'KB';
		} else if($size < 1073741824) {
			$num = $size/1048576;
			$unit = 'MB';
		} else {
			$num = $size/1073741824;
			$unit = 'G';
		}
		if (is_integer($num)) {
			return "{$num}{$mark}{$unit}";
		} else {
			return sprintf('%.2f%s%s', $num, $mark, $unit);
		}
	}

	/**
	 * 获取文件的权限
	 * @return int
	 */
	public function perms()
	{
		return fileperms($this->file);
	}

	/**
	 * 获取文件权限的描述
	 * @return string
	 */
	public function descPerms()
	{
		$perms = fileperms($this->file);
		if (($perms & 0xC000) == 0xC000) {
		    // Socket
		    $info = 's';
		} elseif (($perms & 0xA000) == 0xA000) {
		    // Symbolic Link
		    $info = 'l';
		} elseif (($perms & 0x8000) == 0x8000) {
		    // Regular
		    $info = '-';
		} elseif (($perms & 0x6000) == 0x6000) {
		    // Block special
		    $info = 'b';
		} elseif (($perms & 0x4000) == 0x4000) {
		    // Directory
		    $info = 'd';
		} elseif (($perms & 0x2000) == 0x2000) {
		    // Character special
		    $info = 'c';
		} elseif (($perms & 0x1000) == 0x1000) {
		    // FIFO pipe
		    $info = 'p';
		} else {
		    // Unknown
		    $info = 'u';
		}

		// Owner
		$info .= (($perms & 0x0100) ? 'r' : '-');
		$info .= (($perms & 0x0080) ? 'w' : '-');
		$info .= (($perms & 0x0040) ?
		            (($perms & 0x0800) ? 's' : 'x' ) :
		            (($perms & 0x0800) ? 'S' : '-'));

		// Group
		$info .= (($perms & 0x0020) ? 'r' : '-');
		$info .= (($perms & 0x0010) ? 'w' : '-');
		$info .= (($perms & 0x0008) ?
		            (($perms & 0x0400) ? 's' : 'x' ) :
		            (($perms & 0x0400) ? 'S' : '-'));

		// World
		$info .= (($perms & 0x0004) ? 'r' : '-');
		$info .= (($perms & 0x0002) ? 'w' : '-');
		$info .= (($perms & 0x0001) ?
		            (($perms & 0x0200) ? 't' : 'x' ) :
		            (($perms & 0x0200) ? 'T' : '-'));

		return $info;
	}
}
