<?php
ob_start();
session_name('taolihupan');
session_start();

if(isset($_SESSION['studentNumber'])) {
	$_SESSION = array();	//销毁变量
	session_destroy();		//销毁session自身
	setcookie(session_name(), '', time()-300, '/', '', 0);	//销毁Cookie
	
}

$url = 'http://' . $_SERVER['HTTP_HOST'].'/taolihupan' . dirname($_SERVER['PHP_SELF']);
if((substr($url, -1) == '/') OR (substr($url, -1) == '\\')) {
	$url =substr($url, 0, -1);
}
$url .= '/index.php';

ob_end_clean();
header("Location: $url");
exit();

?>