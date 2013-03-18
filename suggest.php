<?php

require_once('./includes/config.inc.php');
require_once("mail.php");
$page_title = '建议 - 桃李湖畔';
include('./includes/header.html');

if(!isset($_SESSION['studentNumber'])) {
	$url = 'http://' . $_SERVER['HTTP_HOST'].'/taolihupan';
	if((substr($url, -1) == '/') OR (substr($url, -1) == '\\')) {
		$url =substr($url, 0, -1);
	}
	$url .= '/index.php';
	
	ob_end_clean();
	header("Location: $url");
	exit();
}
?>


<div id="tag">
	<div class="floatLeft">
		<a href="student.php?id=<?php echo $_SESSION['studentNumber']?>" class="avatar floatLeft"><img src="http://<?php echo $_SERVER['HTTP_HOST'].'/taolihupan' . '/images/students/' . $_SESSION['studentNumber'] . '.jpg';?>" alt="<?php echo $_SESSION['studentNumber'];?>" /></a>
		<p class="floatLeft">嘿！<br /><?php echo $_SESSION['studentNumber'];?>号同学，<br />
		<?php
			date_default_timezone_set ('PRC');
			$time = date("H:i:s");
			if($time>="18:00:00" && $time<="23:59:59" || $time>="00:00:00" && $time<"07:00:00")
				echo '晚上好！';
			else if($time>="07:00:00" && $time<"11:00:00")
				echo '上午好！';
			else if($time>="11:00:00" && $time<"14:00:00")
				echo '中午好！';
			else if($time>="14:00:00" && $time<"18:00:00")
				echo '下午好！';
		?>
		</p>
	</div>
	<div id="weather" class="floatLeft">
		<?php
		$aim_file = "http://www.google.com/ig/api?weather=hohhot&hl=zh-cn";
		$content = file_get_contents($aim_file);	//需要php.ini中打开allow_url_open
		$content  = mb_convert_encoding($content, 'UTF-8', 'GB2312');
		$xml = simplexml_load_string($content);
		//if can't get the data
		if($xml->weather->forecast_information){
			$short = $xml->weather->forecast_conditions;
			echo '<p>冷暖自知：</p>';
			echo '<p>今日天气：' . $short[0]->condition['data'] . '，' . $short[0]->low['data'] . '℃~' . $short[0]->high['data'] . '℃</p>';
			echo '<p>明日天气：' . $short[1]->condition['data'] . '，' . $short[1]->low['data'] . '℃~' . $short[1]->high['data'] . '℃</p>';
		} else {
			echo '暂时无法更新天气信息';
		}
		?>
	</div>
</div>

<div id="content" class="floatLeft">
	<h1>建议&反馈</h1>
	<p>我们的成长离不开你的指引。</p>
	<div class="attention">
	<?php
		if(isset($_POST['submitted1'])) {
			if(isset($_POST['mes']) && $_POST['mes']!="") {
				$body = $_POST['mes'] . "\n\n来自：" .$_SESSION['studentNumber'];
				
				$smtpserver = "smtp.qq.com";//SMTP服务器
				$smtpserverport =25;//SMTP服务器端口
				$smtpusermail = "admin@taolihupan.com";//SMTP服务器的用户邮箱
				$smtpemailto = "admin@taolihupan.com";//发送给谁
				$smtpuser = "";//SMTP服务器的用户帐号
				$smtppass = "";//SMTP服务器的用户密码
				$mailsubject = '建议-桃李湖畔';//邮件主题
				$mailbody = $body;//邮件内容
				$mailtype = "HTML";//邮件格式（HTML/TXT）,TXT为文本邮件
				
				$smtp = new smtp($smtpserver,$smtpserverport,true,$smtpuser,$smtppass);//这里面的一个true是表示使用身份验证,否则不使用身份验证.
				$smtp->sendmail($smtpemailto, $smtpusermail, $mailsubject, $mailbody, $mailtype);
				
				
				
				//mail('admin@taolihupan.com', '建议-桃李湖畔', $body, 'From: auto@taolihupan.com');
				echo '<p>建议已发送，感谢你的热心参与 :)</p>';
			} else {
				echo '<p>请填写建议内容。</p>';
			}
		}
	?>
	</div>
	<form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
		<textarea rows="10" name="mes" id="mes"></textarea>
		<input type="submit" value="发送建议" class="send"/>
		<input type="hidden" name="submitted1" value="true" />
	</form>
</div>

<div id="sideBar" class="floatRight">
	<h1>或者，直接加入我们吧!</h1>
	<p>给你的创意一个练兵场、手艺一个大舞台。</p>
	<div class="attention">
	<?php
		if(isset($_POST['submitted2'])) {
			if(isset($_POST['sideMes']) && $_POST['sideMes']!="") {
				$body = $_POST['sideMes'] . "\n\n来自：" .$_SESSION['studentNumber'];
				
				$smtpserver = "smtp.qq.com";//SMTP服务器
				$smtpserverport =25;//SMTP服务器端口
				$smtpusermail = "admin@taolihupan.com";//SMTP服务器的用户邮箱
				$smtpemailto = "admin@taolihupan.com";//发送给谁
				$smtpuser = "";//SMTP服务器的用户帐号
				$smtppass = "";//SMTP服务器的用户密码
				$mailsubject = '加入团队-桃李湖畔';//邮件主题
				$mailbody = $body;//邮件内容
				$mailtype = "HTML";//邮件格式（HTML/TXT）,TXT为文本邮件
				
				$smtp = new smtp($smtpserver,$smtpserverport,true,$smtpuser,$smtppass);//这里面的一个true是表示使用身份验证,否则不使用身份验证.
				$smtp->sendmail($smtpemailto, $smtpusermail, $mailsubject, $mailbody, $mailtype);
				
				//mail('admin@taolihupan.com', '加入团队-桃李湖畔', $body, 'From: auto@taolihupan.com');
				echo '<p>简介已发送，我们很激动 :)</p>';
			} else {
				echo '<p>简单介绍下你自己吧。</p>';
			}
		}
	?>
	</div>
	<form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
		<textarea rows="10" name="sideMes" id="sideMes"></textarea>
		<input type="submit" value="发送简介" class="send"/>
		<input type="hidden" name="submitted2" value="true" />
	</form>
</div>

<?php
include('./includes/footer.html');
?>