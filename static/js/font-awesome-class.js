
/**
 * 获取文件类型对应的fontawesome样式类
 */
function getAwesomeClass(extn)
{
	switch(extn) {
		case 'dir':
			return 'fa fa-folder';
			break;

		case 'xls':
		case 'xlsx':
			return 'fa fa-file-excel-o';
			break;

		case 'pdf':
			return 'fa fa-file-pdf-o';
			break;

		case 'mp3':
		case 'rm':
		case 'ogg':
		case 'mid':
		case 'wav':
			return 'fa fa-file-sound-o';
			break;

		case 'doc':
		case 'docx':
			return 'fa fa-file-word-o';
			break;

		case 'zip':
		case 'rar':
		case '7z':
			return 'fa fa-file-archive-o';
			break;

		case 'jpg':
		case 'png':
		case 'gif':
		case 'ico':
		case 'bmp':
			return 'fa fa-file-image-o';
			break;

		case 'txt':
		case 'html':
		case 'htm':
			return 'fa fa-file-text-o';
			break;

		case 'avi':
		case 'wma':
		case 'rmvb':
		case 'mp4':
		case '3gp':
		case 'flash':
			return 'fa fa-file-movie-o';
			break;

		case 'ppt':
		case 'pptx':
			return 'fa fa-file-powerpoint-o';
			break;

		case 'c':
		case 'cpp':
		case 'js':
		case 'php':
		case 'py':
			return 'fa fa-file-code-o';
			break;

		case 'pdf':
			return 'fa fa-file-pdf-o';
			break;

		default:
			return 'fa fa-file-o';
			break;
	}
}
