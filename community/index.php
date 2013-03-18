<?php

require_once('../includes/config.inc.php');
require_once("../mail.php");
$page_title = '社团 - 桃李湖畔';
include('../includes/header.html');

if(!isset($_SESSION['studentNumber'])) {
	$url = 'http://' . $_SERVER['HTTP_HOST'].'/taolihupan' . '/';
	
	ob_end_clean();
	header("Location: $url");
	exit();
}

?>

<div id="tag">
	<p>也许高中的你只顾得学习，<br />大学里该多点时间给兴趣，<br />白天进跆拳道社健健身，晚上去天文社看流星，这才叫多彩的大学生活。</p>
</div>

<div id="content" class="floatLeft">
	<?php
	
	require_once('../mysql_connect.php');
	
	if (isset($_GET['id'])) {
		
		$query = "SELECT * FROM community WHERE id=".$_GET['id'];
		$result = mysql_query($query) or trigger_error("Query: $query \n<br />MySQL Error: " . mysql_error());
		$row = mysql_fetch_array($result);
		
		echo '<h1>'.$row['name'].'</h1>';
		echo '<img src="'.$row['photo'].'" alt="'.$row['name'].'" />';
		echo '<p class="comAbout">'.$row['about'].'</p>';
		echo '<p class="tips">Leader：'.$row['leader'].'</p>';
		echo '<p class="tips">招新热线：'.$row['tel'].'</p>';
		

	} else {
		echo '<h1>百团大战</h1>';
		
		$query = "SELECT * FROM community WHERE pass='1' ORDER BY id ASC";
		$result = mysql_query($query) or trigger_error("Query: $query \n<br />MySQL Error: " . mysql_error());
		echo '<table id="com">';
		$count = 3;		//每行社团数
		while($row = mysql_fetch_array($result)) {

			if ($count == 3) {
				echo '<tr>';
			}
				
			echo '<td><h3><a href="?id=' . $row['id'] . '">' . $row['name'] . '</a></h3>';
			echo '<p class="tips">Leader：'.$row['leader'].'</p>';
			echo '<p class="tips">招新热线：'.$row['tel'].'</p></td>';
			$count--;
			
			if ($count == 0) {
				$count = 3;
				echo '</tr>';
			}
			
		}
		echo '</table><div id="pageNavi"><a href="#" class="center">返回顶部</a></div>';
	}
	?>
	
	
</div>

<div id="sideBar" class="floatRight">
	
	<?php
	$successFlag = false;
	if(isset($_POST['addCom'])) {
		
		echo '<div class="widget"><h2>消息</h2><div class="content" id="message">';
		
		$errors = array();	//初始化错误记录数组
		
		
		if (empty($_POST['comName'])) {
			$errors[] = '<p>请填写社团名称。</p>';
		} else {
			$comName = escape_data($_POST['comName']);
		}
		
		if (empty($_POST['comLeader'])) {
			$errors[] = '<p>请填写Leader。</p>';
		} else {
			$comLeader = escape_data($_POST['comLeader']);
		}
		
		if (empty($_POST['comTel'])) {
			$errors[] = '<p>请填写招新热线。</p>';
		} else {
			$comTel = escape_data($_POST['comTel']);
		}
		
		if ($_FILES['comPhoto']['name'] == "") {
		
			$errors[] = '<p>请选择社团活动照片。</p>';
			
		} else {
		
			$allowedTypes = array("image/jpeg", "image/pjpeg");
		
			if(in_array($_FILES['comPhoto']['type'], $allowedTypes)) {
			
				if($_FILES['comPhoto']['size'] <= 10485760) { 	//照片小于等于10MB
				
					if($_FILES['comPhoto']['error'] != 0) {

						$errors[] = '<p>上传过程出现错误，再试一次吧！</p>';
					}
					
				} else {
					$errors[] = '<p>图片过大。</p>';
				}
				
			} else {
				$errors[] = '<p>请选择jpg格式的图片。</p>';
			}
		}
		
		if (empty($_POST['comAbout'])) {
			$errors[] = '<p>请填写社团简介。</p>';
		} else if (mb_strlen($_POST['comAbout'], 'UTF-8') > 2000 ) {
			$errors[] = '<p>请将简介限制在2000字以内。</p>';
		} else {
			$comAbout = escape_data($_POST['comAbout']);
		}
		
		if (empty($errors)) {
			
			
			$query = "SELECT * FROM community WHERE name='$comName'";
			$result = mysql_query($query) or trigger_error("Query: $query \n<br />MySQL Error: " . mysql_error());
			$row = mysql_fetch_array($result);
			if (mysql_num_rows($result)!=0) {
				
				$errors[] = '<p>该社团已入驻。</p>';
				
			} else {
			
				$query = "INSERT INTO community (name, leader, tel, about, adder) VALUES ('$comName', '$comLeader', '$comTel','$comAbout','".$_SESSION['studentNumber']."')";
				$result = mysql_query($query) or trigger_error("Query: $query \n<br />MySQL Error: " . mysql_error());
				if(mysql_affected_rows()==1) {
					
					$query = "SELECT * FROM community WHERE name='$comName'";
					$result = mysql_query($query) or trigger_error("Query: $query \n<br />MySQL Error: " . mysql_error());
					$row = mysql_fetch_array($result);
					
					list($width, $height) = getimagesize($_FILES['comPhoto']['tmp_name']);	//获得原图的宽和高
					$ratio = $width / 600;
					$newHeight = $height / $ratio;
					
					$thumb = imagecreatetruecolor(600, $newHeight);	//创建宽度为600像素的缩略图
					$source = imagecreatefromjpeg($_FILES['comPhoto']['tmp_name']);	//创建原图
					imagecopyresized($thumb, $source, 0, 0, 0, 0, 600, $newHeight, $width, $height);	//等比缩放原图
					
					$saveFolder = "../images/community";	//保存的文件夹
					$newName = $saveFolder . "/" . $row['id'] . ".jpg";
					
					if(imagejpeg($thumb, $newName, 100)/*保存缩略图*/) {
						
						$photoAddr = 'http://' . $_SERVER['HTTP_HOST'].'/taolihupan' . '/images/community/' . $row['id'] . '.jpg';
						$query = "UPDATE community SET photo='$photoAddr' WHERE name='$comName'";
						$result = mysql_query($query) or trigger_error("Query: $query \n<br />MySQL Error: " . mysql_error());
						$successFlag = true;
						
						//发送邮件
						$body = "社团 " .$comName. " 申请入驻\nLeader：".$comLeader."\n招新热线：".$comTel."\n照片地址：".$photoAddr;
						
						$smtpserver = "smtp.qq.com";//SMTP服务器
						$smtpserverport =25;//SMTP服务器端口
						$smtpusermail = "admin@taolihupan.com";//SMTP服务器的用户邮箱
						$smtpemailto = "admin@taolihupan.com";//发送给谁
						$smtpuser = "";//SMTP服务器的用户帐号
						$smtppass = "";//SMTP服务器的用户密码
						$mailsubject = '社团入驻申请-桃李湖畔';//邮件主题
						$mailbody = $body;//邮件内容
						$mailtype = "HTML";//邮件格式（HTML/TXT）,TXT为文本邮件
						
						$smtp = new smtp($smtpserver,$smtpserverport,true,$smtpuser,$smtppass);//这里面的一个true是表示使用身份验证,否则不使用身份验证.
						$smtp->sendmail($smtpemailto, $smtpusermail, $mailsubject, $mailbody, $mailtype);
						
						//mail('admin@taolihupan.com', '社团入驻申请-桃李湖畔', $body, "From: auto@taolihupan.com");
						
						echo '<p>感谢入驻！申请已发送，我们将会尽快处理。</p>';
						
					} else {
						$errors[] = '<p>系统出现错误，再试一次吧！</p>';
					}
					imagedestroy($thumb);	//销毁缩略图
					
				}
			}
				
		}
		
		if (!empty($errors)) {
			foreach($errors as $msg) {
				echo "$msg\n";
			}
		}
		echo '</div></div>';
		
	}
	
	if (!$successFlag) {
	?>
	
	<div  class="widget">
		<h2>申请入驻</h2>
		<div  class="content">
			<p>身为社团一员苦于每年招新只有寥寥几天？<br />渴望新鲜血液的你们，快来宣传自己吧！</p><br />
			<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" enctype="multipart/form-data">
				<table>
					<tr><td class="marks">社团名称：</td><td><input type="text" name="comName" id="comName" value="<?php if(isset($_POST['comName']) && !$successFlag) echo $_POST['comName']; ?>" size="20" maxlength="50" /></td></tr>
					<tr><td class="marks">Leader：</td><td><input type="text" name="comLeader" id="comLeader" value="<?php if(isset($_POST['comLeader']) && !$successFlag) echo $_POST['comLeader']; ?>" size="20" maxlength="20" /></td></tr>
					<tr><td></td><td class="tips">请填写社团现任主席、社长或会长等</td></tr>
					<tr><td class="marks">招新热线：</td><td><input type="text" name="comTel" id="comTel" value="<?php if(isset($_POST['comTel']) && !$successFlag) echo $_POST['comTel']; ?>" size="20" maxlength="15" /></td></tr>
					<tr><td class="marks">活动照片：</td><td><input type="file" name="comPhoto" id="comPhoto" size="12" /></td></tr>
					<tr><td></td><td class="tips">限上传一张jpg格式照片</td></tr>
					<tr><td class="marks">社团简介：</td><td></td></tr>
				</table>
				<textarea name="comAbout" id="comAbout" ><?php if(isset($_POST['comAbout']) && !$successFlag) echo $_POST['comAbout']; ?></textarea>
				<p class="center"><input type="submit" value="申请入驻" class="send"/></p>
				<input type="hidden" name="addCom" value="true" />
			</form>
		</div>
	</div>
	
	<?php
	}
	?>
	
</div>

<?php
include('../includes/footer.html');
?>