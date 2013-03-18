<?php

require_once('./includes/config.inc.php');
require_once('./includes/util.inc.php');

$page_title = '个人资料 - 桃李湖畔';
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
		<a href="student.php?id=<?php echo $_SESSION['studentNumber']?>" class="avatar floatLeft"><img src="http://<?php echo $_SERVER['HTTP_HOST'].'/taolihupan' . '/images/students/' . $_SESSION['studentNumber'] . '.jpg';?>" alt="<?php echo $_SESSION['studentNumber'];?>"/></a>
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
	<h1>个人资料</h1>
	<p>只有填写了个人资料才能被大家关注，任何同学都可以看到你填写的资料哦。</p>
	<div class="attention">
	
	<?php
		if(isset($_POST['submitted'])) {
			
			require_once('mysql_connect.php');
			
			if(empty($_POST['studentName'])) {
				echo '<p>请填写姓名。</p>';
			} else {
			
				$date = $_POST['year'].'-'.$_POST['month'].'-'.$_POST['day'];
				
				$query = "SELECT * FROM students_profile WHERE stu_num='". $_SESSION['studentNumber']."'";
				$result = mysql_query($query) or trigger_error("Query: $query \n<br />MySQL Error: " . mysql_error());
				if (mysql_num_rows($result)==0) {	//第一次设置个人资料
					
					$query = "INSERT INTO students_profile (stu_num ,name, academy, sex, birthday, hometown) VALUES ('". $_SESSION['studentNumber']."', '".$_POST['studentName']."', '".$_POST['studentAcademy']."','".$_POST['studentSex']."','$date','".$_POST['studentHome'].$_POST['studentHomeNum']."')";
					$result = mysql_query($query) or trigger_error("Query: $query \n<br />MySQL Error: " . mysql_error());
					if (mysql_affected_rows()==1) {
						if (!empty($_POST['phoneNumber'])) {
							$query = "UPDATE students_profile SET phone='".$_POST['phoneNumber']."' WHERE stu_num='".$_SESSION['studentNumber']."'";
							$result = mysql_query($query) or trigger_error("Query: $query \n<br />MySQL Error: " . mysql_error());
						}
						if (!empty($_POST['qq'])) {
							$query = "UPDATE students_profile SET qq='".$_POST['qq']."' WHERE stu_num='".$_SESSION['studentNumber']."'";
							$result = mysql_query($query) or trigger_error("Query: $query \n<br />MySQL Error: " . mysql_error());
						}
						
						gotoUrl('/student.php?id='.$_SESSION['studentNumber']);
						echo '<p>个人资料已经更新！</p>';
						
					}
				
				} else {	//修改个人资料
					
					$query = "UPDATE students_profile SET stu_num='". $_SESSION['studentNumber']."', name='".$_POST['studentName']."', academy='".$_POST['studentAcademy']."', sex='".$_POST['studentSex']."', birthday='$date', hometown='".$_POST['studentHome'].$_POST['studentHomeNum']."' WHERE stu_num='". $_SESSION['studentNumber']."'";
					$result = mysql_query($query) or trigger_error("Query: $query \n<br />MySQL Error: " . mysql_error());
					
					$query = "UPDATE students_profile SET phone='".$_POST['phoneNumber']."' WHERE stu_num='".$_SESSION['studentNumber']."'";
					$result = mysql_query($query) or trigger_error("Query: $query \n<br />MySQL Error: " . mysql_error());

					$query = "UPDATE students_profile SET qq='".$_POST['qq']."' WHERE stu_num='".$_SESSION['studentNumber']."'";
					$result = mysql_query($query) or trigger_error("Query: $query \n<br />MySQL Error: " . mysql_error());
					
					gotoUrl('/student.php?id='.$_SESSION['studentNumber']);
					echo '<p>个人资料已经更新！</p>';
					
					
				}
				
			}
		}
	?>
	</div>
		<form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
			<p>嗨！大家好，我叫<input type="text" name="studentName" id="studentName" class="smallInputText" maxlength="6"/>，<br />是内蒙古大学
			<select name="studentAcademy" id="studentAcademy">
				<option value="计算机">计算机</option>
				<option value="电子信息工程">电子信息工程</option>
				<option value="生命科学">生命科学</option>
				<option value="软件">软件</option>
				<option value="化学化工">化学化工</option>
				<option value="环境与资源">环境与资源</option>
				<option value="经济管理">经济管理</option>
				<option value="法">法</option>
				<option value="外国语">外国语</option>
				<option value="公共管理">公共管理</option>
				<option value="数学科学">数学科学</option>
				<option value="物理科学与技术">物理科学与技术</option>
				<option value="蒙古学">蒙古学</option>
				<option value="民族学与社会学">民族学与社会学</option>
				<option value="文学与新闻传播">文学与新闻传播</option>
				<option value="历史与旅游文化">历史与旅游文化</option>
				<option value="哲学">哲学</option>
				<option value="交通">交通</option>
				<option value="艺术">艺术</option>
				<option value="创业">创业</option>
				<option value="鄂尔多斯">鄂尔多斯</option>
				<option value="满洲里">满洲里</option>
				<option value="国际教育">国际教育</option>
				<option value="继续教育">继续教育</option>
			</select>学院的一名
			<select name="studentSex" id="studentSex"><option value="男">男</option><option value="女">女</option></select>生，<br />
			我的生日是
			<select name="year" id="year">
			<?php
			
				for($i=date("Y")-25; $i<=date("Y"); $i++) {
					echo '<option value="'. $i .'">'. $i . '</option>';
				}
			?>
			</select>
			年
			<select name="month">
			<?php
			
				for($i=1; $i<=12; $i++) {
					echo '<option value="'. $i .'">'. $i . '</option>';
				}
			?>
			</select>
			月
			<select name="day">
			<?php
			
				for($i=1; $i<=31; $i++) {
					echo '<option value="'. $i .'">'. $i . '</option>';
				}
			?>
			</select>
			日，<br />
			什么？我是哪里人呀？我家那边最多的车牌是
			<select name="studentHome" id="studentHome">
				<option value="蒙">蒙</option>
				<option value="京">京</option>
				<option value="沪">沪</option>
				<option value="津">津</option>
				<option value="渝">渝</option>
				<option value="冀">冀</option>
				<option value="晋">晋</option>
				<option value="辽">辽</option>
				<option value="吉">吉</option>
				<option value="黑">黑</option>
				<option value="苏">苏</option>
				<option value="浙">浙</option>
				<option value="皖">皖</option>
				<option value="闽">闽</option>
				<option value="赣">赣</option>
				<option value="鲁">鲁</option>
				<option value="豫">豫</option>
				<option value="鄂">鄂</option>
				<option value="湘">湘</option>
				<option value="粤">粤</option>
				<option value="桂">桂</option>
				<option value="琼">琼</option>
				<option value="川">川</option>
				<option value="贵">贵</option>
				<option value="云">云</option>
				<option value="藏">藏</option>
				<option value="陕">陕</option>
				<option value="甘">甘</option>
				<option value="青">青</option>
				<option value="宁">宁</option>
				<option value="新">新</option>
				<option value="港">港</option>
				<option value="澳">澳</option>
				<option value="台">台</option>
			</select>
			<select name="studentHomeNum" id="studentHomeNum">
				<option value="Ａ">Ａ</option>
				<option value="Ｂ">Ｂ</option>
				<option value="Ｃ">Ｃ</option>
				<option value="Ｄ">Ｄ</option>
				<option value="Ｅ">Ｅ</option>
				<option value="Ｆ">Ｆ</option>
				<option value="Ｇ">Ｇ</option>
				<option value="Ｈ">Ｈ</option>
				<option value="Ｉ">Ｉ</option>
				<option value="Ｊ">Ｊ</option>
				<option value="Ｋ">Ｋ</option>
				<option value="Ｌ">Ｌ</option>
				<option value="Ｍ">Ｍ</option>
				<option value="Ｎ">Ｎ</option>
				<option value="Ｏ">Ｏ</option>
				<option value="Ｐ">Ｐ</option>
				<option value="Ｑ">Ｑ</option>
				<option value="Ｒ">Ｒ</option>
				<option value="Ｓ">Ｓ</option>
				<option value="Ｔ">Ｔ</option>
				<option value="Ｕ">Ｕ</option>
				<option value="Ｖ">Ｖ</option>
				<option value="Ｗ">Ｗ</option>
				<option value="Ｘ">Ｘ</option>
				<option value="Ｙ">Ｙ</option>
				<option value="Ｚ">Ｚ</option>
			</select>
			的，<br />嘿，现在知道我是哪里人了吧！<br />欢迎大家到我的家乡做客！
			</p><br />
			<input type="submit" value="写好了" class="send floatRight">
</div>

<div id="sideBar" class="floatRight">
	<h1>公开更多？</h1>
	<p>More contacts,more friends...你懂的...</p>
	<div class="attention">
	</div>
	<p>
	我的手机号是
	<input type="text" name="phoneNumber" id="phoneNumber" class="smallInputText" maxlength="11"/>，<br />
	当然，谁的电脑里能少得了那只带围脖的企鹅？<br />我的QQ号是
	<input type="text" name="qq" id="qq" class="smallInputText" maxlength="11"/>。<br />总之很高兴认识大家，<br />希望我们可以成为好朋友，<br />谢谢！
	</p>
	<input type="hidden" name="submitted" value="true" />
	</form>
</div>

<?php
include('./includes/footer.html');
?>