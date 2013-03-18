<?php
ob_start();
session_name('taolihupan');
session_start();

if (!isset($_POST['sub'])) {
	$url = 'http://' . $_SERVER['HTTP_HOST'].'/taolihupan' . dirname($_SERVER['PHP_SELF']);
	if((substr($url, -1) == '/') OR (substr($url, -1) == '\\')) {
		$url =substr($url, 0, -1);
	}
	$url .= '/index.php';
		
	header("Location: $url");
}

require_once('../mysql_connect.php');

$errors = array();
$break = false;

if(!empty($_POST['n'])) {
	$n = escape_data($_POST['n']);
} else {
	$errors[] = '<p>请填写学号。</p>';
}

if(!empty($_POST['p'])) {
	$p = escape_data($_POST['p']);
} else {
	$errors[] = '<p>请填写密码。</p>';
}

if(empty($errors)) {
	
	$query = "SELECT stu_num FROM students WHERE (stu_num='$n' AND stop='1')";
	$result = mysql_query($query) or trigger_error("Query: $query \n<br />MySQL Error: " . mysql_error());
	if(@mysql_num_rows($result) != 1) {
	
		$query = "SELECT stu_num FROM students WHERE (stu_num='$n' AND password=MD5('$p')) AND active IS NULL";
		$result = mysql_query($query) or trigger_error("Query: $query \n<br />MySQL Error: " . mysql_error());
		if(@mysql_num_rows($result) == 1) {
		
			$row = mysql_fetch_array($result, MYSQL_NUM);
			$_SESSION['studentNumber'] = $row[0];
			mysql_free_result($result);
			
			$url = 'http://' . $_SERVER['HTTP_HOST'].'/taolihupan' . dirname($_SERVER['PHP_SELF']);
			if((substr($url, -1) == '/') OR (substr($url, -1) == '\\')) {
				$url =substr($url, 0, -1);
			}
			$url .= '/add.php';
			
			ob_end_clean();
			header("Location: $url");
			exit();
			
		} else {
			$errors[] = '<p>学号和密码不匹配，<br />或者该账户还没有激活。</p>';
			$errors[] = '<p><a href="index.php">&lt;&lt;返回首页</a></p>';
			$break = true;
		}
		
	} else {
		$errors[] = '<p>该账户由于未遵守本站<br />的《使用协议》而被注销！</p>';
		$errors[] = '<p><a href="index.php">&lt;&lt;返回首页</a></p>';
		$break = true;
	}
}

?>

<!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.0//EN" "http://www.wapforum.org/DTD/xhtml-mobile10.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="application/xhtml+xml; charset=UTF-8"/>
	<title> 桃李湖畔 </title>
	<link rel="stylesheet" href="../css/m.css" type="text/css"/>
</head>
<body>
	<h1>桃李湖畔</h1>
	<div id="w">
		<h2>登录</h2>
		<?php
		
		if (!empty($errors)) {
			foreach($errors as $msg) {
				echo "$msg\n";
			}
		}
		
		if (!$break) {
		?>
			<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
				<p>学号：<input type="text" id="n" name="n" /></p>
				<p>密码：<input type="password" id="p" name="p" /></p>
				<input type="submit" value="登录" id="go" />
				<input type="hidden" name="sub" value="true" />
			</form>
			<p>未注册的同学，请用计算机访问<br />http://taolihupan.com进行注册。</p>
		<?php
		}
		?>
		
	</div>
	<p class="z">&copy; 2010 桃李湖畔</p>
</body>
</html>
<?php
mysql_close();
?>