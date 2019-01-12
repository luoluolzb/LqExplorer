<?php
/**
 * 代码行数统计
 */
class CodeLineInfo
{
	/**
	 * 支持的文件类型
	 * 
	 * @var array
	 */
	private $code_type = [
		'php', 'js', 'css', 'html', 'tpl', 'java', 'jsp',
	];

	/**
	 * 开始统计
	 * 
	 * @param string $fileName 文件或文件夹名
	 * @return [
	 *     'file' => string,
	 *     'type' => 'dir' | 'file',
	 *     'code_count' => [
	 *         'php' => ,
	 *         'js' => ,
	 *         ...
	 *     ],
	 *     'all_count' => integer,
	 * ]
	 */
	function Proccess($fileName){
		if(is_dir($fileName)){
			return $this->_ProcessDir($fileName);
		}else{
			return $this->_ProcessFile($fileName);
		}
	}

	private function _ProcessFile($fileName){
		$type = $this->_SupportExt($fileName);
		if(! isset($type)){
			return NULL;
		}

		$fileHandle = fopen($fileName, 'r');
		if(! $fileHandle){
			return NULL;
		}

		$lineCount = 0;
		while($lineText = fgets($fileHandle)){
			++ $lineCount;
		}
		fclose($fileHandle);
		return [
			'file' => $this->_GetFileName($fileName),
			'file_type' => 'file',
			'code_count' => [
				$type => $lineCount,
			],
			'all_count' => $lineCount,
		];
	}

	private function _ProcessDir($dirName){
		$dirHandle = opendir($dirName);
		if(! $dirHandle){
			return NULL;
		}

		$codeCount = [];
		$allCount = 0;
		while($fileName = readdir($dirHandle)){
			if($fileName == '.' || $fileName == '..'){
				continue;
			}

			$fullName = "{$dirName}/{$fileName}";
			$ret = $this->Proccess($fullName);
			if(isset($ret)){
				$codeCount_ = &$ret['code_count'];
				foreach ($codeCount_ as $key => $value) {
					if(! isset($codeCount[$key])){
						$codeCount[$key] = 0;
					}
					$codeCount[$key] += $value;
					$allCount += $value;
				}
			}
		}

		closedir($dirHandle);
		return [
			'file'=> $this->_GetFileName($dirName),
			'file_type' => 'dir',
			'code_count' => $codeCount,
			'all_count' => $allCount,
		];
	}

	private function _SupportExt($fileName){
		if(! file_exists($fileName)){
			return NULL;
		}
		$fileExt = $this->_GetFileExt($fileName);
		foreach ($this->code_type as $value) {
			if($value == $fileExt){
				return $value;
			}
		}
		return NULL;
	}

	private function _GetFileName($fileName){
		$exp = explode('\\', $fileName);
		$fileName = end($exp);

		$exp = explode('/', $fileName);
		$fileName = end($exp);
		return $fileName;
	}

	private function _GetFileExt($fileName){
		$exp = explode('.', $fileName);
		$ext = strtolower(end($exp));
		return $ext;
	}
}
