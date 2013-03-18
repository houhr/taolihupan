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
		<p>欢迎来到桃李湖畔手机版</p>
		
			<?php
				require_once('../mysql_connect.php');
				$today = date("Y-n-d").' 23:59:59';
				$query = "SELECT id FROM activities WHERE pass=1 AND start_time<'$today' ORDER BY start_time";
				$result = mysql_query($query) or trigger_error("Query: $query \n<br />MySQL Error: " . mysql_error());
				$total = mysql_num_rows($result);
				if ($total != 0) {
					echo '<h2>今日活动</h2><p>以下信息均由同学自行添加，<br />桃李湖畔不保证其真实有效。</p>';
					
					$pageSize = 5;		//每页项目数
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
					
					$query = "SELECT * FROM activities WHERE pass=1 AND start_time<'$today' ORDER BY start_time LIMIT $offset,$pageSize";
					$result = mysql_query($query) or trigger_error("Query: $query \n<br />MySQL Error: " . mysql_error());
					
					while ($row = mysql_fetch_array($result)) {
						echo '<div class="y">';
						echo '<h3>'. $row['title'] .'</h3>';
						echo '<p>时间：'. $row['start_time'] .'</p>';
						echo '<p>地点：'. $row['address'] .'</p>';
						echo '<p>来自：'.$row['stu_num'].'号同学</p>';
						echo '</div>';
					}
					
					if ($total > $pageSize) {
						
						echo '<p>';
							if ($page != 1) {
								echo '<a href="?page=1">&lt;&lt;第一页</a>';
								echo '<a href="?page=' . $pre . '">&lt;上一页</a>';
							}
							if ($page != $pageCount) {
								echo '<a href="?page=' . $next . '">下一页&gt;</a>';
							}
						echo '</p>';
						
					}
					
				} else {
					echo '<h2>今日暂无活动</h2>';
				}
			?>	
		<h2>有新活动？登录来发布活动吧！</h2>
		<form action="login.php" method="post">
			<p>学号：<input type="text" id="n" name="n" /></p>
			<p>密码：<input type="password" id="p" name="p" /></p>
			<input type="submit" value="登录" id="go" />
			<input type="hidden" name="sub" value="true" />
		</form>
		<p>未注册的同学，请用计算机访问<br />http://taolihupan.com进行注册。</p>
	</div>
	<p class="z">&copy; 2010 桃李湖畔</p>
</body>
</html>