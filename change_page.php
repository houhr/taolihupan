<?php	//更改头像

require_once('./includes/config.inc.php');
$page_title = '个人页面 - 桃李湖畔';
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

require_once('mysql_connect.php');
$setted = false;
$query = "SELECT * FROM students_page WHERE stu_num='".$_SESSION['studentNumber']."'";
$result = mysql_query($query) or trigger_error("Query: $query \n<br />MySQL Error: " . mysql_error());
$rowOld = mysql_fetch_array($result);

if (mysql_num_rows($result) != 0) {
	$setted = true;
}
?>

<div id="tag">
	<div class="floatLeft">
		<a href="student.php?id=<?php echo $_SESSION['studentNumber']?>" class="avatar floatLeft">
		<img src="http://<?php echo $_SERVER['HTTP_HOST'].'/taolihupan' . '/images/students/' . $_SESSION['studentNumber'] . '.jpg';?>" alt="<?php echo $_SESSION['studentNumber'];?>" /></a>
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
<h1>设置个人页面</h1>
<p>想让自己的个人页面与众不同？来设置顶部大图&随身听的音乐吧！</p>
<div class="attention">

<?php

if(isset($_POST['submitted'])) {

	$errors = array();
	
	$img = NULL;
	$musicTitle = NULL;
	$musicAddress = NULL;
	
	
	if (!empty($_POST['img'])) {
		if(preg_match('/^http:\/\/.+\.(jpg|png|bmp|gif)$/', stripslashes(trim($_POST['img'])))) {
			$img = escape_data($_POST['img']);
		} else {
			$errors[] = '<p>图片地址格式错误。</p>';
		}
	}
	
	
	
	if (!empty($_POST['musicAddress']) || !empty($_POST['musicTitle'])) {
		
		if (empty($_POST['musicTitle'])) {
			$errors[] = '<p>请填写音乐名字。</p>';
		}
		if (empty($_POST['musicAddress'])) {
			$errors[] = '<p>请填写音乐地址。</p>';
		} else {
		
			if(preg_match('/^http:\/\/.+\.mp3$/', stripslashes(trim($_POST['musicAddress'])))) {
				$musicAddress = escape_data($_POST['musicAddress']);
			} else {
				$errors[] = '<p>请选用mp3格式的音乐。</p>';
			}
		
		}
		
		$musicTitle = escape_data($_POST['musicTitle']);
		
	}
	
	if(empty($errors)) {	//全部通过
		
		if ($setted) {	//之前设置过
			
			$query = "UPDATE students_page SET img='$img', music_title='$musicTitle', music_addr='$musicAddress' WHERE stu_num='".$_SESSION['studentNumber']."'";
			$result = mysql_query($query) or trigger_error("Query: $query \n<br />MySQL Error: " . mysql_error());
			if(mysql_affected_rows()==1) {
				echo '<p>设置成功！</p>';
			}
			
		} else {	//第一次设置
			
			$query = "INSERT INTO students_page (stu_num, img, music_title, music_addr) VALUES ('".$_SESSION['studentNumber']."', '$img', '$musicTitle', '$musicAddress')";
			$result = mysql_query($query) or trigger_error("Query: $query \n<br />MySQL Error: " . mysql_error());
			if(mysql_affected_rows()==1) {
				echo '<p>设置成功！</p>';
				
			}
		}
	
	} else {	//报告错误
		foreach($errors as $msg) {
			echo "<p>$msg</p>";
		}
	}
}
?>

</div>
	
	<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
		<table>
			<tr><td>顶部大图：</td><td><input type="text" name="img" id="img" size="45" maxlength="200" value="<?php if(isset($_POST['img'])) echo $_POST['img']; else if($setted) echo $rowOld['img']; ?>" /></td></tr>
			<tr><td></td><td class="tips">请输入连同http://在内完整的顶部大图地址，图片宽度将被设为950像素</td></tr>
			<tr><td></td><td class="tips">某些网站的照片有防盗链功能，如百度空间、校内网、QQ空间等，请注意筛选以避免使用</td></tr>
			<tr><td></td><td class="tips">懒得去找？试试下面这些吧！</td></tr>
			<tr><td></td><td class="tips">小狗刀刀http://<?php echo $_SERVER['HTTP_HOST'].'/taolihupan';?>/images/stupage/daodao.jpg</td></tr>
			<tr><td></td><td class="tips">卡通周董http://i3.6.cn/cvbnm/05/77/fd/f5c9c72be0028e7f2ce193e3717eae1a.jpg</td></tr>
			<tr><td>音乐名字：</td><td><input type="text" name="musicTitle" id="musicTitle" maxlength="30" value="<?php if(isset($_POST['musicTitle'])) echo $_POST['musicTitle']; else if($setted) echo $rowOld['music_title']; ?>" /></td></tr>
			<tr><td>音乐地址：</td><td><input type="text" name="musicAddress" id="musicAddress" size="45" maxlength="200" value="<?php if(isset($_POST['musicAddress'])) echo $_POST['musicAddress']; else if($setted) echo $rowOld['music_addr']; ?>" /></td></tr>
			<tr><td></td><td class="tips">请输入连同http://在内完整的mp3格式的音乐地址</td></tr>
			<tr><td></td><td class="tips"><input type="submit" value="设置页面" class="send" /></td></tr>
		</table>
		<input type="hidden" name="submitted" value="true" />
	</form>
</div>

<?php
include('./includes/footer.html');
?>