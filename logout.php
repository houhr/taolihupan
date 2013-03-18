<?php
ob_start();
session_name('taolihupan');
session_start();
require_once('./includes/config.inc.php');
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xml:lang="zh-cn" lang="zh-cn">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>桃李湖畔</title>
	<link href="./css/other.css" rel="stylesheet" type="text/css" media="screen" />
	<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
	<link rel="icon" href="/favicon.ico" type="image/x-icon" />
	<link rel="bookmark" href="/favicon.ico" type="image/x-icon" />
</head>
<body>
	<div id="wrapper">
		<div id="navi">
			<a href="http://<?php echo $_SERVER['HTTP_HOST'].'/taolihupan';?>/"><div id="logo"></div></a>
		</div>

<?php
if(isset($_SESSION['studentNumber'])) {
	setcookie('taolihupan_autologin', '', time()-300, '/', '', 0);	//销毁自动登录的cookie
	$_SESSION = array();	//销毁变量
	session_destroy();		//销毁session自身
	setcookie(session_name(), '', time()-300, '/', '', 0);	//销毁Cookie
	
} else {
	$url = 'http://' . $_SERVER['HTTP_HOST'].'/taolihupan';
	if((substr($url, -1) == '/') OR (substr($url, -1) == '\\')) {
		$url =substr($url, 0, -1);
	}
	$url .= '/index.php';
	
	ob_end_clean();
	header("Location: $url");
	exit();
}

echo '<div id="content"><h1>随时欢迎你回来 :)</h1>';
echo '<a href="http://' . $_SERVER['HTTP_HOST'].'/taolihupan' . '">重新登录!</a></div>';
include('./includes/footer.html');

?>