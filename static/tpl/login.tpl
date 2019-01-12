<!DOCTYPE html>
<html lang="zh-CN">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>登录页面</title>
	<link href="static/bootstrap/css/bootstrap.min.css" rel="stylesheet">
	<!--link href="bootstrap/css/bootstrap-theme.min.css" rel="stylesheet"-->
	<!--[if lt IE 9]>
	<script src="https://cdn.bootcss.com/html5shiv/3.7.3/html5shiv.min.js"></script>
	<script src="https://cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
	<![endif]-->
	<link href="static/css/login.css" rel="stylesheet">
</head>
<body>
	<div class="container">
		<div class="row">
			<div class="col-md-4 col-md-offset-4 col-xs-10 col-xs-offset-1">
				<h3>登录密码</h3>
				<form action="login.php" method="post">
					<input type="password" class="form-control" placeholder="请输入密码" name="password" autofocus="autofocus" value="<?php echo $vars['password']; ?>" />
					<input type="submit" class="btn btn-primary btn-block" value="登录">
					<p class="error"><?php echo $vars['errmsg']; ?></p>
				</form>
			</div>
		</div>
	</div>
</body>
</html>