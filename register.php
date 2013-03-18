<?php

ob_start();
session_name('taolihupan');
session_start();
require_once('./includes/config.inc.php');
require_once("mail.php");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xml:lang="zh-cn" lang="zh-cn">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>注册 - 桃李湖畔</title>
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
		<h1>欢迎注册</h1>

<?php

if(isset($_POST['submitted'])) {	//如果提交
	
	require_once('mysql_connect.php');
	
	$errors = array();	//初始化错误记录数组
	
	if(empty($_POST['regStudentNumber'])) {	//检查用户名
		$errors[] = '<p>请填写学号。</p>';
	} else if(preg_match('/^[0-9]{8}$/', stripslashes(trim($_POST['regStudentNumber'])))) {
		$stuNum = escape_data($_POST['regStudentNumber']);
	} else {
		$errors[] = '<p>学号格式错误。</p>';
	}
	
	if(empty($_POST['regEmail'])) {	//检查邮箱
		$errors[] = '<p>请填写邮箱。</p>';
	} else if(preg_match('/^[[:alnum:]][a-z0-9_\.\-]*@[a-z0-9\.\-]+\.[a-z]{2,4}$/', stripslashes(trim($_POST['regEmail'])))) {
		$email = escape_data($_POST['regEmail']);
	} else {
		$errors[] = '<p>邮箱格式错误。</p>';
	}
	
	if(empty($_POST['regPassword'])) {	//检查邮箱
		$errors[] = '<p>请填写密码。</p>';
	} else {
		$password = escape_data($_POST['regPassword']);
	}
	
	if(!isset($_POST['agreement'])){
		$errors[] = '<p>请同意本站的使用协议。</p>';
	}
	
	if(empty($errors)) {	//全部通过
		
		$query = "SELECT stu_num FROM students WHERE stu_num='$stuNum' ";
		$result = mysql_query($query) or trigger_error("Query: $query \n<br />MySQL Error: " . mysql_error());
		if(mysql_num_rows($result)==0) {
		
			$query = "SELECT stu_num FROM students WHERE email='$email' ";
			$result = mysql_query($query) or trigger_error("Query: $query \n<br />MySQL Error: " . mysql_error());
			if(mysql_num_rows($result)==0) {
			
				$active = md5(uniqid(rand(), true));
				
				$query = "INSERT INTO students (stu_num, email, password, active, registration_date) VALUES ('$stuNum', '$email', MD5('$password'), '$active', NOW())";
				$result = mysql_query($query) or trigger_error("Query: $query \n<br />MySQL Error: " . mysql_error());
				if(mysql_affected_rows()==1) {
					$body = "同学你好！欢迎加入桃李湖畔社区！请点击（或复制）链接来激活你的账户：\n\n";
					$body .= "http://" . $_SERVER['HTTP_HOST'].'/taolihupan' . "/activate.php?n=" . $_POST['regStudentNumber'] . "&a=$active";
					
					$smtpserver = "smtp.qq.com";//SMTP服务器
					$smtpserverport =25;//SMTP服务器端口
					$smtpusermail = "admin@taolihupan.com";//SMTP服务器的用户邮箱
					$smtpemailto = $_POST['regEmail'];//发送给谁
					$smtpuser = "";//SMTP服务器的用户帐号
					$smtppass = "";//SMTP服务器的用户密码
					$mailsubject = '激活账户-桃李湖畔';//邮件主题
					$mailbody = $body;//邮件内容
					$mailtype = "HTML";//邮件格式（HTML/TXT）,TXT为文本邮件
					
					$smtp = new smtp($smtpserver,$smtpserverport,true,$smtpuser,$smtppass);//这里面的一个true是表示使用身份验证,否则不使用身份验证.
					$smtp->sendmail($smtpemailto, $smtpusermail, $mailsubject, $mailbody, $mailtype);
					
					//mail($_POST['regEmail'], '激活账户-桃李湖畔', $body, "From: auto@taolihupan.com");
					
					echo '<p>感谢你的注册！一封激活邮件已发往你的邮箱，请点击邮件中的链接来激活你的账户。</p>';
					echo '<p>提醒：如果收件箱中没有找到激活邮件，看看垃圾箱，激活邮件很调皮的啦~\(≧▽≦)/~</p></div>';
					
					include('./includes/footer.html');
					exit();
				} else {
					echo '非常抱歉，发生了一个系统错误！';
					echo mysql_error() . '<br /> . Query: '. $query;
					echo '</div>';
					include('./includes/footer.html');
					exit();
				}
			} else {
				echo '<p>该邮箱已注册！</p>';
			}
		} else {
			echo '<p>该学号已注册！</p>';
		}
	
	} else {	//报告错误
		foreach($errors as $msg) {
			echo "$msg\n";
		}
	}
	
	mysql_close();
}// End of if(isset($_POST['submitted'])) IF

?>
	
	<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
		<p>学号：<input type="text" name="regStudentNumber" value="<?php if(isset($_POST['regStudentNumber'])) echo $_POST['regStudentNumber']; ?>" /></p>
		<p>邮箱：<input type="text" name="regEmail" maxlength="40" value="<?php if(isset($_POST['regEmail'])) echo $_POST['regEmail']; ?>" /></p>
		<p>密码：<input type="password" name="regPassword" maxlength="20" value="" /></p>
		<p><label><input type="checkbox" name="agreement" id="agreement" />我已阅读并同意《<a href="/taolihupan/agreement.php" target="_blank">使用协议</a>》。</label></p>
		<p><input type="submit" value="完成注册" class="send"/></p>
		<input type="hidden" name="submitted" value="true" />
	</form>
	
</div>
<?php
include('./includes/footer.html');
?>