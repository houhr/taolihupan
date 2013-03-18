<?php
ob_start();
session_name('taolihupan');
session_start();

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


require_once('./includes/config.inc.php');
require_once('mysql_connect.php');
require_once('activities/activitiesClass.inc.php');
require_once('play/playClass.inc.php');
require_once('includes/util.inc.php');
$page_title = '桃李湖畔';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xml:lang="zh-cn" lang="zh-cn">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title><?php echo $page_title; ?></title>
	<link href="./css/other.css" rel="stylesheet" type="text/css" media="screen" />
	<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
	<link rel="icon" href="/favicon.ico" type="image/x-icon" />
	<link rel="bookmark" href="/favicon.ico" type="image/x-icon" />
	<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=true"></script>
	<script type="text/javascript" src="http://<?php echo $_SERVER['HTTP_HOST'].'/taolihupan'; ?>/js/other.js"></script>
</head>
<body>
	<div id="wrapper">
		<div id="navi">
			<a href="http://<?php echo $_SERVER['HTTP_HOST'].'/taolihupan';?>/"><div id="logo"></div></a>
			<ul id="links">
				<li><a href="http://<?php echo $_SERVER['HTTP_HOST'].'/taolihupan'; ?>/home.php" title="首页">❤ 首页</a></li>
				<li><a href="./activities/" title="活动">☑ 活动</a></li>
				<li><a href="./play/" title="玩乐">♞ 玩乐</a></li>
				<li><a href="./books/" title="书籍">✎ 书籍</a></li>
				<li><a href="./experience/" title="经验">✈ 经验</a></li>
				<li><a href="./community/" title="社团">☢ 社团</a></li>
			</ul>
		</div>
		<div id="notice">
			<img src="./images/notice.jpg" alt="notice" />
		</div>
		<div id="settings">
			<span class="floatLeft">欢迎，<?php echo $_SESSION['studentNumber'];?></span>
			<ul class="floatRight" id="change">
				<li><a href="student.php?id=<?php echo $_SESSION['studentNumber']?>">我的主页</a></li>
				<li onmouseover="displaySubMenu(this)" onmouseout="hideSubMenu(this)"id="dropMenu">
					<a href="#">设置</a>
					<ul>
						<li ><a href="http://<?php echo $_SERVER['HTTP_HOST'].'/taolihupan'; ?>/change_profile.php">个人资料</a></li>
						<li ><a href="http://<?php echo $_SERVER['HTTP_HOST'].'/taolihupan'; ?>/change_page.php">个人页面</a></li>
						<li ><a href="http://<?php echo $_SERVER['HTTP_HOST'].'/taolihupan'; ?>/change_avatar.php">更改头像</a></li>
						<li ><a href="http://<?php echo $_SERVER['HTTP_HOST'].'/taolihupan'; ?>/change_password.php">更改密码</a></li>
					</ul>
				</li>
				<li><a href="http://<?php echo $_SERVER['HTTP_HOST'].'/taolihupan'; ?>/suggest.php">建议</a></li>
				<li><a href="http://<?php echo $_SERVER['HTTP_HOST'].'/taolihupan'; ?>/logout.php">退出</a></li>
			</ul>
			<form id="search" action="http://<?php echo $_SERVER['HTTP_HOST'].'/taolihupan'; ?>/search.php" method="post">
				<input id="search_word" name="search_word" type="text" value="找同学、搜文章" onfocus="clearInput();" onblur="restore();"/>
				<button type="submit" id="searchButton"></button>
			</form>
		</div>

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
	<div>
		<h1>最新活动</h1>
		<?php
		$query = "SELECT * FROM activities WHERE pass=1 ORDER BY `activities`.start_time LIMIT 1";
		$result = mysql_query($query) or trigger_error("Query: $query \n<br />MySQL Error: " . mysql_error());
		
		while($row = mysql_fetch_array($result)) {
			$item = new ActivitiesItem($row);
			$item -> display();
		}
		?>
	</div>
	
	<div>
		<h1>最新玩乐</h1>
		<?php
		$query = "SELECT * FROM play ORDER BY `play`.id DESC LIMIT 1";
		$result = mysql_query($query) or trigger_error("Query: $query \n<br />MySQL Error: " . mysql_error());
		
		while($row = mysql_fetch_array($result)) {
			$item = new PlayItem($row);
			$item -> display();
		}
		?>
	</div>
	
	
</div>

<div id="sideBar" class="floatRight">
	
	<div class="widget">
		<h2>你的留言本</h2>
		<div class="content">
			
			<?php
			
			$pageSize = 5;		//每页项目数
			$query = "SELECT id FROM students_page_comment WHERE host='".$_SESSION['studentNumber']."'";
			$result = mysql_query($query) or trigger_error("Query: $query \n<br />MySQL Error: " . mysql_error());
			$total = mysql_num_rows($result);		//总项目数
			if ($total!=0) {
				$pageCount = ceil($total / $pageSize);	//总页数
				$page = isset($_GET["page"]) ? intval($_GET["page"]) : 1;
				if ($page > $pageCount) {
					$page = $pageCount;
				}
				if ($page <= 0) {
					$page = 1;
				}
				$offset = ($page - 1) * $pageSize;
				($page - 1) < 1 ? $pre = 1 : $pre = ($page - 1);		//上一页
				($page + 1) > $pageCount ? $next = $pageCount : $next = ($page + 1);		//下一页
				
				$query = "SELECT * FROM students_page_comment WHERE host='".$_SESSION['studentNumber']."' ORDER BY date DESC LIMIT $offset,$pageSize";
				$result = mysql_query($query) or trigger_error("Query: $query \n<br />MySQL Error: " . mysql_error());
				
				
				while($row = mysql_fetch_array($result)) {
					echo '<a href="student.php?id='.$row['via'].'"><img src="http://' . $_SERVER['HTTP_HOST'].'/taolihupan' . '/images/students/'.$row['via'].'.jpg" class="floatLeft" alt="'.$row['via'].'" /></a>';
					echo '<div class="comment"><p class="date">'.$row['date'].'</p>';
					echo '<p>'.$row['content'].'</p></div>';
				}
				
				
				if ($total > $pageSize) {
					
					echo '<div id="pageNavi">';
						if ($page != 1) {
							echo '<a href="?page=1">&lt;&lt;第一页</a>';
							echo '<a href="?page=' . $pre . '">&lt;上一页</a>';
						}
						if ($page != $pageCount) {
							echo '<a href="?page=' . $next . '">下一页&gt;</a>';
						}
					echo '</div>';
					
				}
			} else {
				echo '<p>暂无留言。</p>';
			}
			?>
		</div>
	</div>
	
	<div class="widget">
		<h2>你的偶像们</h2>
		<div class="content">
			<?php
				
				$query1 = "SELECT * FROM concern WHERE fan='".$_SESSION['studentNumber']."' ORDER BY RAND() LIMIT 18";
				$result1 = mysql_query($query1) or trigger_error("Query: $query \n<br />MySQL Error: " . mysql_error());
				
				if (mysql_num_rows($result1) != 0) {
					
					while ($row1 = mysql_fetch_array($result1))
						echo '<a href="student.php?id='.$row1['idol'].'"><img src="http://' . $_SERVER['HTTP_HOST'].'/taolihupan' . '/images/students/'.$row1['idol'].'.jpg" alt="'.$row1['idol'].'"/></a>';
					
				} else {
					echo '<p>你暂时还没有偶像呢，<br />去关注几个感兴趣的同学吧！</p>';
				}
				
			?>
		</div>
	</div>
	
	<div class="widget">
		<h2>同班同学</h2>
		<div class="content">
			<?php
				
				$same = substr($_SESSION['studentNumber'], 0, 5);
				$query1 = "SELECT * FROM students WHERE stu_num LIKE '$same%' AND stu_num!=".$_SESSION['studentNumber']." AND active is NULL ORDER BY registration_date DESC";
				$result1 = mysql_query($query1) or trigger_error("Query: $query \n<br />MySQL Error: " . mysql_error());
				if (mysql_num_rows($result1) != 0) {
					
					while ($row1 = mysql_fetch_array($result1))
						echo '<a href="student.php?id='.$row1['stu_num'].'"><img src="http://' . $_SERVER['HTTP_HOST'].'/taolihupan' . '/images/students/'.$row1['stu_num'].'.jpg" alt="'.$row1['stu_num'].'"/></a>';
				
				} else {
					echo '<p>哇！你是班里第一名来到这里的哦！</p>';
				}
				
			?>
		</div>
	</div>
	
	<div class="widget">
		<h2>最近加入的同学</h2>
		<div class="content">
			<?php
				
				$query1 = "SELECT * FROM students WHERE active is NULL ORDER BY registration_date DESC LIMIT 30";
				$result1 = mysql_query($query1) or trigger_error("Query: $query \n<br />MySQL Error: " . mysql_error());
				
				while ($row1 = mysql_fetch_array($result1))
					echo '<a href="student.php?id='.$row1['stu_num'].'"><img src="http://' . $_SERVER['HTTP_HOST'].'/taolihupan' . '/images/students/'.$row1['stu_num'].'.jpg" alt="'.$row1['stu_num'].'"/></a>';
				
			?>
		</div>
	</div>
</div>


<?php
include('./includes/footer.html');

?>