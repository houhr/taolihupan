<?php	//激活页面

require_once('./includes/config.inc.php');
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xml:lang="zh-cn" lang="zh-cn">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>激活账户 - 桃李湖畔</title>
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
if(isset($_GET['n'])) {
	$n = $_GET['n'];
} else {
	$n = 0;
}

if(isset($_GET['a'])) {
	$a = substr($_GET['a'], 0 ,32);
} else {
	$a = 0;
}

if(($n >= 0) && (strlen($a) == 32)) {
	require_once('mysql_connect.php');
	$query ="SELECT * FROM students WHERE (stu_num='$n' AND active is NULL)";
	$result = mysql_query($query) or trigger_error("Query: $query \n<br />MySQL Error: " . mysql_error());
	$total = mysql_num_rows($result);
	if($total>0){
		echo '<div id="content"><h1>恭喜!</h1><p>账户激活成功！马上<a href="http://'.$_SERVER['HTTP_HOST'].'/taolihupan'.'/">登录</a>，开始你的桃李湖畔之旅吧！</p></div>';
	}else{
		$query = "UPDATE students SET active=NULL WHERE (stu_num='$n' AND active='" . escape_data($a) . "') LIMIT 1";
		$result = mysql_query($query) or trigger_error("Query: $query \n<br />MySQL Error: " . mysql_error());
		if(mysql_affected_rows()!=0) {
			//为学生拷贝默认头像
			$file = './images/default.jpg';
			$newfile = './images/students/'.$n.'.jpg';
			copy($file, $newfile);
			$file = './images/default_b.jpg';
			$newfile = './images/students/'.$n.'_b.jpg';
			copy($file, $newfile);
			
			echo '<div id="content"><h1>恭喜!</h1><p>账户激活成功！马上<a href="http://'.$_SERVER['HTTP_HOST'].'/taolihupan'.'/">登录</a>，开始你的桃李湖畔之旅吧！</p></div>';
		} else {
			echo '<div id="content"><h1>无法激活你的账户 :(</h1><p>请复制邮件中的链接，并粘贴到地址栏中后按回车键再试一次，<br />如依然无法激活，请发邮件至admin@taolihupan.com寻求帮助。</p></div>';
		}
	}
	mysql_close();
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

include('./includes/footer.html');

?>