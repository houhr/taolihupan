<?php

ob_start();
session_name('taolihupan');
session_start();
require_once('./includes/config.inc.php');
require_once('./includes/util.inc.php');
require_once("mail.php");
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
	<script src="http://www.google.com/jsapi?key=ABQIAAAABb9KZHHJTyEmQTbgH3nubRRSPIdEuYaah-coEaLR0SZEGdFe2hTeBcJYqi0oeZr7WTKYOG5oTf2zIA" type="text/javascript"></script>
</head>
<body>
	<div id="wrapper">
		<div id="navi">
			<a href="http://<?php echo $_SERVER['HTTP_HOST'].'/taolihupan';?>/"><div id="logo"></div></a>
		</div>
		<div id="content">

<?php
if(isset($_POST['toEmail']) || isset($_GET['forget'])) {
	echo '<h1>重设密码</h1>';
	$send = false;
	if(isset($_POST['toEmail'])) {
		if(empty($_POST['toEmail'])) {
			echo '<p>请填写邮箱。</p>';
		} else {
			require_once('mysql_connect.php');
			$query = "SELECT stu_num FROM students WHERE email='" . escape_data($_POST['toEmail']) . "'";
			$result = mysql_query($query) or trigger_error("Query: $query \n<br />MySQL Error: " . mysql_error());
			if(mysql_num_rows($result) == 1) {
				$p = substr(md5(uniqid(rand(), 1)), 3, 6);
				$query = "UPDATE students SET password=md5('$p') WHERE email='" . escape_data($_POST['toEmail']) . "'";
				$result = mysql_query($query) or trigger_error("Query: $query \n<br />MySQL Error: " . mysql_error());
				if(mysql_affected_rows() == 1) {
					$body = "你的新密码为'$p'，请用此密码登录桃李湖畔，并重新设置密码。";
					
					$smtpserver = "smtp.qq.com";//SMTP服务器
					$smtpserverport =25;//SMTP服务器端口
					$smtpusermail = "admin@taolihupan.com";//SMTP服务器的用户邮箱
					$smtpemailto = $_POST['toEmail'];//发送给谁
					$smtpuser = "";//SMTP服务器的用户帐号
					$smtppass = "";//SMTP服务器的用户密码
					$mailsubject = '重设密码-桃李湖畔';//邮件主题
					$mailbody = $body;//邮件内容
					$mailtype = "HTML";//邮件格式（HTML/TXT）,TXT为文本邮件
					
					$smtp = new smtp($smtpserver,$smtpserverport,true,$smtpuser,$smtppass);//这里面的一个true是表示使用身份验证,否则不使用身份验证.
					$smtp->sendmail($smtpemailto, $smtpusermail, $mailsubject, $mailbody, $mailtype);
					
					//mail($_POST['toEmail'], '重设密码-桃李湖畔', $body, 'From: admin@taolihupan.com');
					echo '<p>密码已重设，新密码已经发至你的邮箱 :)</p>';
					$send = true;
				} else {
					echo '<p>你的密码暂时不能修改，请发邮件至admin@taolihupan.com联系管理员。</p>';
				}
			} else {
				echo '<p>该邮箱没有注册过。</p>';
			}
		}
	}
	
	if(!$send) {
		echo '
		<form action="' . $_SERVER['PHP_SELF'] .'" method="post">
			<p>你的邮箱：<input type="text" id="toEmail" name="toEmail" />
			<input type="hidden" name="forget" value="password" />
			<input type="submit" value="重设密码" /></p>
		</form></div>
		';
	} else {
		echo '</div>';
	}
}else if(isset($_POST['submitted'])) {
	echo '<h1>登录</h1>';
	require_once('mysql_connect.php');
	
	if(!empty($_POST['studentNumber'])) {
		$n = escape_data($_POST['studentNumber']);
	} else {
		$n = false;
		echo '<p>请填写学号。</p>';
	}
	if(!empty($_POST['password'])) {
		$p = escape_data($_POST['password']);
	} else {
		$p = false;
		echo '<p>请填写密码。</p>';
	}
	if(isset($_POST['autologin']) && ($_POST['autologin'] == 'yes')) {
		$autologin = true;
	} else {
		$autologin = false;
	}
	
	$stopFlag = false;
	
	if($n && $p) {
		
		$query = "SELECT stu_num FROM students WHERE (stu_num='$n' AND stop='1')";
		$result = mysql_query($query) or trigger_error("Query: $query \n<br />MySQL Error: " . mysql_error());
		if(@mysql_num_rows($result) != 1) {
		
			$query = "SELECT stu_num FROM students WHERE (stu_num='$n' AND password=MD5('$p')) AND active IS NULL";
			$result = mysql_query($query) or trigger_error("Query: $query \n<br />MySQL Error: " . mysql_error());
			if(@mysql_num_rows($result) == 1) {
				$row = mysql_fetch_array($result, MYSQL_NUM);
				mysql_free_result($result);
				mysql_close();
				if($autologin) {
					setcookie('taolihupan_autologin', numToCookie($row[0]), time()+864000);	//设置自动登录的cookie
					$_SESSION['studentNumber'] = $row[0];
				} else {
					$_SESSION['studentNumber'] = $row[0];	/****************************************************/
				}
				
				$url = 'http://' . $_SERVER['HTTP_HOST'].'/taolihupan';
				if((substr($url, -1) == '/') OR (substr($url, -1) == '\\')) {
					$url =substr($url, 0, -1);
				}
				$url .= '/home.php';
				
				ob_end_clean();
				header("Location: $url");
				exit();
			} else {
				echo '<p>学号和密码不匹配，或者该账户还没有激活。</p>';
			}
			
		} else {
			echo '<p>该账户由于未遵守本站的《使用协议》而被注销！</p></div>';
			$stopFlag = true;
		}
	}
	
	if (!$stopFlag) echo '
	<form action="' . $_SERVER['PHP_SELF'] . '" method="post">
		<p>学 号：<input type="text" id="studentNumber" name="studentNumber" maxlength="8" />
		<a href="http://'.$_SERVER['HTTP_HOST'].'/taolihupan'.'/newcomer.php" tabindex="-1">无号新生？</a></p>
		<p>密 码：<input type="password" id="password" name="password"/>
		<a href="http://'. $_SERVER['HTTP_HOST'].'/taolihupan' .'/login.php?forget=password" tabindex="-1">忘了密码？</a></p>
		<p><input type="checkbox" name="autologin" id="autologin" value="yes"/><label for="autologin">记住我</label></p>
		<p><input type="submit" value="登录" class="send"/></p>
		<p>首次登录前请先<a href="http://'. $_SERVER['HTTP_HOST'].'/taolihupan' .'/register.php">注册</a>。</p>
		<input type="hidden" name="submitted" value="true" />
	</form></div>
	';
	
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