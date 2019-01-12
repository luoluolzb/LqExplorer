
//var rootDir;
//var dir;
var $tbody, $showDir;
var fileList = null;
var selectedIndex;

function getFullName(fileName){
	return dir + fileName + '/';
}

function getSelected()
{
	var $nodes = $('table>tbody>tr:has(:checkbox:checked)>td>.filename');
	var list = [];
	$nodes.each(function(){
		list.push($(this).text());
	});
	return list;
}

function getSelectedIndex()
{
	var $nodes = $('table>tbody>tr:has(:checkbox:checked)');
	var Index = [];
	$nodes.each(function(){
		Index.push($(this).data('index'));
	});
	return Index;
}

function RefreshList()
{
	$.ajax({
		'typr': 'POST',
		'url': 'api/get-filelist.php',
		'dataType': 'json',
		'data': {
			'dir': dir,
		},
		'success': function(ret){
			if(ret.errcode != 0){
				ll_Modal.Show('列表获取失败！');
				return ;
			}
			fileList = ret.data;
			$tbody.empty();
			for(var i = 0, len = fileList.length; i < len; ++ i){
				var info = fileList[i];    
				if(info.type == 'dir'){
					$tr = $('<tr class="file-info" data-index="'+ i + '">\
						<td>\
							<span class="'+ getAwesomeClass('dir') +'"></span>\
							<span class="filename">' + info.name + '</span>\
						</td>\
						<td>--</td>\
						<td><input class="checkbox" type="checkbox" /></td>\
					</tr>');
				} else{
					$tr = $('<tr class="file-info" data-index="'+ i + '">\
						<td>\
							<span class="'+ getAwesomeClass(info.extension) +'"></span>\
							<span class="filename" target="_blank">' + info.name + '</span>\
						</td>\
						<td>' + info.size_string + '</td>\
						<td><input class="checkbox" type="checkbox" /></td>\
					</tr>');
				}
				$tbody.append($tr);
			}
		},
		'error': function(xmlHttpRequest, textStarus, errorThrown){
			/*console.log(textStarus);
			console.log(errorThrown);*/
			ll_Modal.Show('列表获取失败！');
		}
	});

	if(dir == rootDir){
		$showDir.val('');
	}else{
		var str = dir.substr(rootDir.length);
		$showDir.val(str);
	}
	$('.checkbox-toggle').trigger('cancel');
}

function DeleteFile(fileList)
{
	var listHtml = '';
	if(typeof fileList == 'string'){
		listHtml = fileList;
	}
	else{
		for (var i = 0, n = fileList.length; i < n; ++ i) {
			listHtml += ' ' + fileList[i];
			if(i < n - 1){
				listHtml += '<br/>';
			}
		}
	}
	ll_Modal.Dialog({
		'title': '删除文件',
		'content': listHtml,
		'yesFun': function(){
			$.ajax({
				'typr': 'POST',
				'url': 'api/delete-file.php',
				'dataType': 'json',
				'data': {
					'dir': dir,
					'fileList': fileList
				},
				'success': function(ret){
					if(ret.errcode != 0){
						//console.log(ret);
						ll_Modal.Show('删除失败！');
					}
					else{
						if(typeof fileList != 'string'){
							ll_Modal.Show('删除成功');
						}
						MenuOption('refresh');
					}
				},
				'error': function(xmlHttpRequest, textStarus, errorThrown){
					/*console.log(textStarus);
					console.log(errorThrown);*/
					ll_Modal.Show('删除失败！');
				}  
			});
		}
	});
}

function RenameFile($name)
{
	var fileName = $name;
	ll_Modal.Input({
		'title': '文件重命名',
		'inputText': fileName,
		'yesFun': function(){
			$.ajax({
				'typr': 'POST',
				'url': 'api/rename.php',
				'dataType': 'json',
				'data': {
					'dir': dir,
					'fileName': fileName,
					'newName': ll_Modal.GetInput(),
				},
				'success': function(ret){
					if(ret.errcode != 0){
						ll_Modal.Show('重命名失败！');
					}
					else{
						MenuOption('refresh');
					}
				},
				'error': function(xmlHttpRequest, textStarus, errorThrown){
					/*console.log(textStarus);
					console.log(errorThrown);*/
					ll_Modal.Show('重命名失败！');
				}
			});
		},
	});
}

function CreateDir()
{
	ll_Modal.Input({
		'title': '新建文件夹',
		'yesFun': function() {
			$.ajax({
				'typr': 'POST',
				'url': 'api/create-dir.php',
				'dataType': 'json',
				'data': {
					'dir': dir,
					'newdir': ll_Modal.GetInput(),
				},
				'success': function(ret){
					if(ret.errcode != 0){
						ll_Modal.Show('新建文件夹失败！');
					}
					else{
						MenuOption('refresh');
					}
				},
				'error': function(xmlHttpRequest, textStarus, errorThrown){
					/*console.log(textStarus);
					console.log(errorThrown);*/
					ll_Modal.Show('新建文件夹失败！');
				}
			});
		},
	});
}

function GetCodeLine(list)
{
	$.ajax({
		'typr': 'POST',
		'url': 'api/codeline.php',
		'dataType': 'json',
		'data': {
			'dir': dir,
			'fileList': list,
		},
		'success': function(ret){
			if(ret.errcode != 0){
				ll_Modal.Show('统计失败');
			} else {
				if (ret.data.length == 0) {
					ll_Modal.Show('这里没有代码。', '统计结果');
				} else {
					var html = '<table class="table">';
					html += '<tr><th>格式</th><th>行数</th></tr>';
					for (var format in ret.data) {
						html += '<tr>';
						html += '<td>'+ format + '</td>';
						html += '<td>'+ ret.data[format] + '</td>';
						html += '</tr>';
					}
					html += '</table>';
					ll_Modal.Show(html, '统计结果');
				}
			}
		},
		'error': function(xmlHttpRequest, textStarus, errorThrown){
			//console.log(textStarus);
			//console.log(errorThrown);
			ll_Modal.Show('统计失败！');
		}
	});
}

function ZipFile(list)
{
	ll_Modal.Input({
		'title': '压缩文件',
		'inputText': list.length > 1 ? 'newfile.zip' : list[0] + '.zip',
		'yesFun': function(){
			$.ajax({
				'typr': 'POST',
				'url': 'api/zipfile.php',
				'dataType': 'json',
				'data': {
					'dir': dir,
					'fileList': list,
					'toName': ll_Modal.GetInput(),
				},
				'success': function(ret){
					if(ret.errcode != 0){
						ll_Modal.Show('压缩文件失败！');
					}
					else{
						MenuOption('refresh');
					}
				},
				'error': function(xmlHttpRequest, textStarus, errorThrown){
					/*console.log(textStarus);
					console.log(errorThrown);*/
					ll_Modal.Show('压缩文件失败！');
				}
			});
		},
	});
}

function UnzipFile(name)
{
	$.ajax({
		'typr': 'POST',
		'url': 'api/unzipfile.php',
		'dataType': 'json',
		'data': {
			'dir': dir,
			'fileName': name,
		},
		'success': function(ret){
			if(ret.errcode != 0){
				ll_Modal.Show('解压失败');
			}
			else{
				MenuOption('refresh');
			}
		},
		'error': function(xmlHttpRequest, textStarus, errorThrown){
			/*console.log(textStarus);
			console.log(errorThrown);*/
			ll_Modal.Show('解压失败');
		}
	});
}

function ViewFileInfo(file)
{
	$html = $('<table class="table table-hover table-responsive">\
		<tbody>\
			\
		</tbody>\
	</table>');
	$html.append($('\
		<tr>\
			<td>文件名</td>\
			<td>'+ file.name +'</td>\
		</tr>\
		<tr>\
			<td>位置</td>\
			<td>'+ file.basename +'</td>\
		</tr>\
	'));
	if (file.type != 'dir') {
		var type = file.mime != 'unkown' ? file.mime : file.extension;
		$html.append($('\
			<tr>\
				<td>类型</td>\
				<td>'+ type +'</td>\
			</tr>\
			<tr>\
				<td>权限</td>\
				<td>'+ file.perms_string +'</td>\
			</tr>\
			<tr>\
				<td>大小</td>\
				<td>'+ file.size_string +'</td>\
			</tr>\
		'));
	} else {
		$html.append($('\
		<tr>\
			<td>类型</td>\
			<td>文件夹</td>\
		</tr>\
		<tr>\
			<td>权限</td>\
			<td>'+ file.perms_string +'</td>\
		</tr>\
		'));
	}
	$html.append($('\
		<tr>\
			<td>创建时间</td>\
			<td>'+ file.ctime_string +'</td>\
		</tr>\
		<tr>\
			<td>修改时间</td>\
			<td>'+ file.mtime_string +'</td>\
		</tr>\
		<tr>\
			<td>最后访问</td>\
			<td>'+ file.atime_string +'</td>\
		</tr>\
	'));
	ll_Modal.Show($html, '文件属性');
}

function PreviewFile(name)
{
	window.open('api/preview.php?dir=' + dir + '&fileName=' + name);
}

function DownLoadFile(name)
{
	window.location.href = 'api/download.php?dir=' + dir + '&fileName=' + name;
}

function UploadFile()
{
	$('#uploadForm>input[name="file"]').trigger('click');
}

function ShareFile(name)
{
	$.ajax({
		'typr': 'POST',
		'url': 'api/share-token.php',
		'dataType': 'json',
		'data': {
			'dir': dir,
			'fileName': name,
		},
		'success': function(ret){
			if(ret.errcode != 0){
				ll_Modal.Show('分享失败！');
			}
			var href = location.href;
			var pos = href.lastIndexOf('/');
			var link = href.substr(0, pos + 1) + 'api/share.php?token=' + ret.data.token;
			var html = '<p>复制链接分享：<br/>';
			html += '<a target="_blank" href="'+ link +'" style="word-wrap:break-word;">'+ link +'</a></p>';
			ll_Modal.Show(html, '分享文件');
		},
		'error': function(xmlHttpRequest, textStarus, errorThrown){
			/*console.log(textStarus);
			console.log(errorThrown);*/
			ll_Modal.Show('分享失败！');
		}
	});
}

function MenuOption(option, data)
{
	switch(option){
		case 'changedir':
			dir = getFullName(data);
			RefreshList();
		break;

		case 'backdir':
			if(dir != rootDir){
				pos = dir.substr(0, dir.length - 1).lastIndexOf('/');
				dir = dir.substr(0, pos + 1);
				if(dir.length < rootDir.length){
					dir = rootDir;
				}
				RefreshList();
			}
		break;

		case 'backroot':
			if(dir != rootDir){
				dir = rootDir;
				RefreshList();
			}
		break;

		case 'refresh':
			RefreshList();
		break;

		case 'deleteone':
			DeleteFile(data);
		break;

		case 'deleteselected':
			var list = getSelected();
			if(list.length){
				DeleteFile(list);
			}
		break;

		case 'zipfile':
			var list = getSelected();
			if(list.length){
				ZipFile(list);
			}
		break;

		case 'unzipfile':
			var list = getSelected();
			if(list.length == 1){
				UnzipFile(list[0]);
			}
		break;

		case 'rename':
			var list = getSelected();
			if(list.length == 1){
				RenameFile(list[0]);
			}
		break;

		case 'newdir':
			CreateDir();
		break;
		
		case 'viewinfo':
			var indexs = getSelectedIndex();
			if(indexs.length == 1){
				ViewFileInfo(fileList[indexs[0]]);
			}
			break;

		case 'codeline':
			var list = getSelected();
			if(list.length){
				GetCodeLine(list);
			}
		break;

		case 'upload':
			UploadFile();
		break;
	}
	$('.checkbox-toggle').trigger('cancel');
}

function OpenFileMenu(item)
{
	var file = fileList[selectedIndex];
	switch(item) {
		case 'preview':
			PreviewFile(file.name);
			break;

		case 'viewinfo':
			ViewFileInfo(file);
			break;

		case 'download':
			DownLoadFile(file.name);
			break;

		case 'delete':
			DeleteFile(file.name);
			break;
		
		case 'share':
			ShareFile(file.name);
			break;

		case 'rename':
			RenameFile(file.name);
			break;

		case 'zip':
			ZipFile([file.name]);
			break;

		case 'unzip':
			UnzipFile(file.name);
			break;

		case 'codeline':
			GetCodeLine([file.name]);
			break;

		default:
			break;
	}
}

$(function(){
	$tbody = $('table>tbody');
	$showDir = $('#showDir');

	$('#changeDir').click(function(){
		dir = rootDir + $showDir.val();
		MenuOption('refresh');
	});

	$(document).on('keydown', '#dirShowBar input', function(){
		if (event.keyCode == 13){
			$('#changeDir').trigger('click');
		}
	});

	$('.checkbox-toggle').click(function(){
		var checked = $(this).prop('checked');
		if(checked){
			$('.checkbox').prop('checked', true);
		}else{
			$('.checkbox').prop('checked', false);
		}
	}).on('cancel', function(){
		$('.checkbox').prop('checked', false);
	});

	$(document).on('click', '.checkbox:not(.checkbox-toggle)', function(){
		var all_checked = true;
		$('.checkbox:not(.checkbox-toggle)').each(function(){
			if(!$(this).prop('checked')){
				all_checked = false;
			}
		});
		if($('.checkbox-toggle').prop('checked') != all_checked){
			$('.checkbox-toggle').prop('checked', all_checked);
		}
	}).on('click', '.file-info>td:not(:last-child)', function() {
		var $tr = $(this).parent('tr');
		var file = fileList[$tr.data('index')];
		if (file.type == 'dir') {
			MenuOption('changedir', file.name);
		} else {
			selectedIndex = $tr.data('index');
			$('#file-menu').modal('show');
		}
	});

	$('#MenuToggle').click(function(){
		var count = getSelected().length;
		if(count == 0){
			$(this).parent().find('.selected').addClass('disabled');
		}else{
			$(this).parent().find('.selected').removeClass('disabled');
		}
	});

	//文件菜单
	$('#filemenu-list').on('click', 'li', function() {
		$('#file-menu').modal('hide');
		var item = $(this).data('item');
		setTimeout(function() {
			OpenFileMenu(item);
		}, 300);
	});

	//文件上传事件
	$('#uploadForm>input[name="file"]').change(function() {
		var formData = new FormData();
		formData.append('file', $(this).get(0).files[0]);
		formData.append('saveDir', dir);
		$.ajax({
			url: "api/upload-file.php",
			type: "POST",
			processData: false,
			contentType: false,
			data: formData,
			success: function(ret) {
				//console.log(ret);
				if(ret.errcode != 0){
					ll_Modal.Show('上传失败！');
				}else{
					RefreshList();
				}
			},
			error: function (xmlHttpRequest, textStarus, errorThrown) {
				/*console.log(textStarus);
				console.log(errorThrown);*/
				ll_Modal.Show('上传失败！');
			}
		});
	});

	RefreshList();
});
