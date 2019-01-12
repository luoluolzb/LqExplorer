<html lang="zh-CN">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>LqExplorer</title>
	<link href="static/bootstrap/css/bootstrap.min.css" rel="stylesheet">
	<!--[if lt IE 9]>
	<script src="https://cdn.bootcss.com/html5shiv/3.7.3/html5shiv.min.js"></script>
	<script src="https://cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
	<![endif]-->
	<link href="static/css/index.css" rel="stylesheet">
	<link href="static/font-awesome-4.7.0/css/font-awesome.min.css" rel="stylesheet">
</head>
<body>
	<div class="toolbar toolbar-top">
		<div id="dirShowBar" class="dirShowBar">
			<div class="input-group input-group">
				<span class="input-group-addon">
					<span class="fa fa-folder-open"></span> <?php echo ROOT_PATH ?>
				</span>
				<input type="text" class="form-control" id="showDir"/>
			</div>
		</div>
	</div>
	<div class="container" style="margin-top: 15px;">
		<table class="table table-condensed table-hover">
			<thead>
				<tr>
					<th>名称</th>
					<!-- <th>修改时间</th> -->
					<th>大小</th>
					<th><input class="checkbox checkbox-toggle" type="checkbox"/></th>
				</tr>
			</thead>
			<tbody>
			</tbody>
		</table>

		<div class="modal fade" id="file-menu">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title">文件操作</h4>
					</div>
					<div class="modal-body">
						<ul class="list-group text-center" id="filemenu-list">
							<li class="list-group-item" data-item="preview">
								<span class="fa fa-eye"></span> 打开
							</li>
							<li class="list-group-item" data-item="viewinfo">
								<span class="fa fa-info-circle"></span> 属性
							</li>
							<li class="list-group-item" data-item="download">
								<span class="fa fa-download"></span> 下载
							</li>
							<li class="list-group-item" data-item="share">
								<span class="fa fa-share-alt"></span> 分享文件
							</li>
							<li class="list-group-item" data-item="rename">
								<span class="fa fa-edit"></span> 重命名
							</li>
							<li class="list-group-item" data-item="zip">
								<span class="fa fa-file-zip-o"></span> 压缩文件
							</li>
							<li class="list-group-item" data-item="unzip">
			    				<span class="fa fa-share-square-o"></span> 解压到当前目录
							</li>
							<li class="list-group-item" data-item="codeline">
								<span class="fa fa-list-ol"></span> 统计代码行数
							</li>
							<li class="list-group-item" data-item="delete">
								<span class="fa fa-trash"></span> 删除
							</li>
						</ul>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="toolbar toolbar-bottom">
		<div class="MenuBar btn-group btn-group-justified">
			<div class="btn-group">
				<button class="btn btn-default" onclick="MenuOption('backdir');">
					<span class="fa fa-arrow-left"></span> 返回
				</button>
			</div>
			<div class="btn-group">
				<button class="btn btn-default" onclick="MenuOption('backroot');">
					<span class="fa fa-home"></span> 根目录
				</button>
			</div>
			<div class="btn-group">
				<button class="btn btn-default" onclick="MenuOption('refresh');">
					<span class="fa fa-refresh"></span> 刷新
				</button>
			</div>
			<div class="btn-group dropup">
			  <button class="btn btn-default dropdown-toggle" id="MenuToggle" type="button" data-toggle="dropdown">
				<span class="caret"></span> 菜单
			  </button>
			  <ul class="dropdown-menu dropdown-menu-right">
			    <li class="selected">
			    	<a onclick="MenuOption('rename');">
			    		<span class="fa fa-edit"></span> 重命名
			    	</a>
			    </li>
			    <li class="selected">
			    	<a onclick="MenuOption('deleteselected');">
			    		<span class="fa fa-trash"></span> 删除选中
			    	</a>
			    </li>
			    <li class="selected">
			    	<a onclick="MenuOption('unzipfile');">
			    		<span class="fa fa-share-square-o"></span> 解压到当前目录
			    	</a>
			    </li>
			    <li class="selected">
			    	<a onclick="MenuOption('zipfile');">
			    		<span class="fa fa-file-zip-o"></span> 压缩文件
			    	</a>
			    </li>
			    <li class="selected">
			    	<a onclick="MenuOption('codeline');">
			    		<span class="fa fa-list-ol"></span> 统计代码行数
			    	</a>
			    </li>
			    <li class="selected">
			    	<a onclick="MenuOption('viewinfo');">
			    		<span class="fa fa-info-circle"></span> 属性
			    	</a>
			    </li>
			    <li>
			    	<a onclick="MenuOption('newdir');">
			    		<span class="fa fa-plus"></span> 新建文件夹
			    	</a>
			    </li>
			    <li>
			    	<a onclick="MenuOption('upload');">
			    		<span class="fa fa-upload"></span> 上传文件
			    	</a>
			    </li>
			  </ul>
			</div>
		</div>
	</div>
		
	<div class="upload" style="display: none;">
		<form id="uploadForm" method="post" enctype="multipart/form-data">
			<input type="file" class="upload-file" name="file"/>
		</form>
	</div>

	<script src="static/js/jquery-1.12.4.min.js"></script>
	<script src="static/bootstrap/js/bootstrap.min.js"></script>
	<script src="static/js/font-awesome-class.js"></script>
	<script src="static/js/ll_modal.js"></script>
	<script>dir = rootDir = '<?php echo $vars['root_path']; ?>';</script>
	<script src="static/js/index.js"></script>
</body>
</html>