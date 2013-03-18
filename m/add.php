<?php
session_name('taolihupan');
session_start();
if(!isset($_SESSION['studentNumber'])) {
	$url = 'http://' . $_SERVER['HTTP_HOST'].'/taolihupan' . dirname($_SERVER['PHP_SELF']);
	if((substr($url, -1) == '/') OR (substr($url, -1) == '\\')) {
		$url =substr($url, 0, -1);
	}
	$url .= '/index.php';
	
	ob_end_clean();
	header("Location: $url");
	exit();
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
		<p><?php echo $_SESSION['studentNumber'].'号同学，你好！';?></p>
		<h2>欢迎发布新活动</h2>
		<?php
		
		require_once('../mysql_connect.php');
		
		$successFlag = false;
		
		if (isset($_POST['s'])) {
			$errors = array();

			if(empty($_POST['name'])) {
				$errors[] = '<p>请填写活动名称。</p>';
			} else if (mb_strlen($_POST['name'], 'UTF-8') > 80) {
				$errors[] = '<p>请将活动名称限制在80字内。</p>';
			} else {
				$name = escape_data($_POST['name']);
			}

			if(empty($_POST['time'])) {	//检查用户名
				$errors[] = '<p>请填写活动时间。</p>';
			} else if(preg_match('/^\d{4}-\d{1,2}-\d{1,2} \d{2}:\d{2}$/', stripslashes(trim($_POST['time'])))) {
				$time = escape_data($_POST['time']);
			} else {
				$errors[] = '<p>时间格式错误，请检查日期和时间之间是否有用空格隔开，时间为24小时制，时分均为2位且之间为英文输入模式下的冒号。</p>';
			}
			
			if(empty($_POST['address'])) {
				$errors[] = '<p>请填写活动地点。</p>';
			} else if (mb_strlen($_POST['address'], 'UTF-8') > 80) {
				$errors[] = '<p>请将活动地点限制在80字内。</p>';
			} else {
				$address = escape_data($_POST['address']);
			}

			if(empty($errors)) {
				
				$query = "INSERT INTO activities(title, start_time, address, stu_num) VALUES ('$name','$time','$address','".$_SESSION['studentNumber']."')";
				$result = mysql_query($query) or trigger_error("Query: $query \n<br />MySQL Error: " . mysql_error());
				if(mysql_affected_rows()==1) {
					
					echo '<div class="y">';
					echo '<h3>'.$name.'</h3>';
					echo '<p>时间：'.$time.'</p>';
					echo '<p>地点：'.$address.'</p>';
					echo '</div>';
					echo '<p>活动成功发布，等待管理员审核，谢谢你的热心参与！</p>';
					$successFlag = true;
				}
				
			} else {
				foreach($errors as $msg) {
					echo "$msg\n";
				}
			}
			
		}
		
		if (!$successFlag) {
		?>
		<p>请确保你所添加的活动真实有效</p>
		<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
			<p>名称：<input type="text" id="name" name="name" value="<?php if(isset($_POST['name'])) echo $_POST['name']?>" /></p>
			<p>时间：<input type="text" id="time" name="time" value="<?php if(isset($_POST['time'])) echo $_POST['time']?>" /></p>
			<p>格式：<?php echo date("Y-m-d H:i");?></p>
			<p>地点：<input type="text" id="address" name="address" value="<?php if(isset($_POST['address'])) echo $_POST['address']?>" /></p>
			<input type="submit" value="立刻告诉大家！" id="go" />
			<input type="hidden" name="s" value="true" />
		</form>
		<?php
		}
		?>
		<p><a href="logout.php">退出&gt;&gt;</a></p>
	</div>
	<p class="z">&copy; 2010 桃李湖畔</p>
</body>
</html>
