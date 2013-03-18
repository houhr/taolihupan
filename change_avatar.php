<?php	//更改头像

require_once('./includes/config.inc.php');
$page_title = '更改头像 - 桃李湖畔';
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
<h1>更改头像</h1>
<p>来吧！让我们做个“有头有脸”的人！</p>
<div class="attention">
<?php

if(isset($_POST['submitted'])) {

	if($_FILES['upLoadAvatar']['name'] != "") {
	
		$allowedTypes = array("image/jpeg", "image/pjpeg");
		
		if(in_array($_FILES['upLoadAvatar']['type'], $allowedTypes)) {
		
			if($_FILES['upLoadAvatar']['size'] <= 3145728) { 	//头像小于等于3MB
			
				if($_FILES['upLoadAvatar']['error'] == 0) {
					
					$source = imagecreatefromjpeg($_FILES['upLoadAvatar']['tmp_name']);	//创建原图
					list($width, $height) = getimagesize($_FILES['upLoadAvatar']['tmp_name']);	//获得原图的宽和高
					$saveFolder = "./images/students";	//保存头像的文件夹
					
					//保存大头像，将头像宽度设定为300px
					$ratio = $width / 300;
					$newHeight = $height / $ratio;
					$thumb_b = imagecreatetruecolor(300, $newHeight);	//创建大头像的缩略图
					imagecopyresized($thumb_b, $source, 0, 0, 0, 0, 300, $newHeight, $width, $height);	//等比缩放原图
					$newName = $saveFolder . "/" . $_SESSION['studentNumber'] . "_b.jpg";
					imagejpeg($thumb_b, $newName, 100);/*保存大头像的缩略图*/
					
					$thumb = imagecreatetruecolor(48, 48);	//创建小头像缩略图
					$detaX = 0;		//取原图正中最大的正方形
					$detaY = 0;
					if ($width >= $height) {
						$detaX = ((int) ($width / 2)) - 24 * ($height / 48);
						$width = $height;
					} else {
						$detaY = ((int) ($height / 2)) - 24 * ($width / 48);
						$height = $width;
					}
					
					imagecopyresized($thumb, $source, 0, 0, $detaX, $detaY, 48, 48, $width, $height);	//等比缩放原图
					$newName = $saveFolder . "/" . $_SESSION['studentNumber'] . ".jpg";
					
					if(imagejpeg($thumb, $newName, 100)/*保存缩略图*/) {
						echo '<p>上传成功！<br />如果头像没有更新，请点击到首页，然后用浏览器刷新页面几次。</p>';
						
					} else {
						echo '<p>系统出现错误，再试一次吧！</p>';
					}
					imagedestroy($thumb_b);	//销毁缩略图
					imagedestroy($thumb);
					
				} else {
					echo '<p>上传过程出现错误，再试一次吧！</p>';
				}
				
			} else {
				echo '<p>图片过大。</p>';
			}
			
		} else {
			echo '<p>请选择允许的图片格式。</p>';
		}
		
	} else {
		echo '<p>请选择头像图片。</p>';
	}
	
}
?>

</div>
	
	<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" enctype="multipart/form-data">
		<p><input type="file" id="upLoadAvatar" name="upLoadAvatar" /></p>
		<p class="tips">请选择小于3MB的jpg格式图片。</p>
		<input type="submit" value="更改头像" class="send" />
		<input type="hidden" name="submitted" value="true" />
	</form>
</div>

<div id="sideBar" class="floatRight">
	<div  class="widget">
		<h2>大头像预览</h2>
		<img src="http://<?php echo $_SERVER['HTTP_HOST'].'/taolihupan' . '/images/students/' . $_SESSION['studentNumber'] . '_b.jpg';?>" />
	</div>
</div>

<?php
include('./includes/footer.html');
?>