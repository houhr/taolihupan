<?php	//更改密码

require_once('./includes/config.inc.php');
$page_title = '更改密码 - 桃李湖畔';
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
<h1>更改密码</h1>
<div class="attention">
<?php
if(isset($_POST['submitted'])) {
	require_once('mysql_connect.php');
	if(!empty($_POST['newPassword']) && !empty($_POST['oldPassword'])) {
		$op = escape_data($_POST['oldPassword']);
		$query = "SELECT password FROM students WHERE stu_num='".$_SESSION['studentNumber']."'";
		$result = mysql_query($query) or trigger_error("Query: $query \n<br />MySQL Error: " . mysql_error());
		$row = mysql_fetch_array($result);
		$op = md5($op);
		if($op == $row['password']) {
			$np = escape_data($_POST['newPassword']);
			$query = "UPDATE students SET password=MD5('$np') WHERE stu_num='".$_SESSION['studentNumber']."'";
			$result = mysql_query($query) or trigger_error("Query: $query \n<br />MySQL Error: " . mysql_error());
			if(mysql_affected_rows() == 1) {
				echo '<p>密码已更改 :)</p>';
			} else {
				echo '<p>新密码与旧密码是一样一样一样地。</p>';
			}
		} else {
			echo '<p>旧密码不正确。</p>';
		}
	
	} else {
		echo '<p>请输入密码。</p>';
	}
	
	mysql_close();
}

?>

</div>
	<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
		<p>旧密码：<input type="password" name="oldPassword" /></p>
		<p>新密码：<input type="password" name="newPassword" /></p>
		<input type="submit" value="更改密码" class="send" />
		<input type="hidden" name="submitted" value="true" />
	</form>
</div>

<?php
include('./includes/footer.html');
?>