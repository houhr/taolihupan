<?php
ob_start();
session_name('taolihupan');
session_start();
require_once('./includes/config.inc.php');
require_once('./includes/util.inc.php');

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

if ($_SESSION['studentNumber'] == $_GET['id']) {
	$self = true;
} else {
	$self = false;
}

if (!isset($_GET['id'])) {
	$url = 'http://' . $_SERVER['HTTP_HOST'].'/taolihupan';
	if((substr($url, -1) == '/') OR (substr($url, -1) == '\\')) {
		$url =substr($url, 0, -1);
	}
	$url .= '/index.php';
	
	ob_end_clean();
	header("Location: $url");
	exit();
} else {
	require_once('mysql_connect.php');
	$query = "SELECT * FROM students WHERE stu_num='".$_GET['id']."'";
	$result = mysql_query($query) or trigger_error("Query: $query \n<br />MySQL Error: " . mysql_error());
	if (mysql_num_rows($result) == 0) {
		$url = 'http://' . $_SERVER['HTTP_HOST'].'/taolihupan';
		if((substr($url, -1) == '/') OR (substr($url, -1) == '\\')) {
			$url =substr($url, 0, -1);
		}
		$url .= '/index.php';
		
		ob_end_clean();
		header("Location: $url");
		exit();
	}
	
	if (isset($_GET['c']) && ($_GET['c'] != '1' && $_GET['c'] != '0')) {
		$url = 'http://' . $_SERVER['HTTP_HOST'].'/taolihupan';
		if((substr($url, -1) == '/') OR (substr($url, -1) == '\\')) {
			$url =substr($url, 0, -1);
		}
		$url .= '/index.php';
		
		ob_end_clean();
		header("Location: $url");
		exit();
	}
	
	if (isset($_GET['c']) && $_GET['c'] == '1') {	//关注某人
		$query3 = "SELECT id FROM concern WHERE idol='".$_GET['id']."' AND fan='".$_SESSION['studentNumber']."'";
		$result3 = mysql_query($query3) or trigger_error("Query: $query \n<br />MySQL Error: " . mysql_error());
		if (mysql_num_rows($result3) == 0) {
			$query3 = "INSERT INTO concern (idol, fan, date) VALUES ('".$_GET['id']."', '".$_SESSION['studentNumber']."', now())";
			$result3 = mysql_query($query3) or trigger_error("Query: $query \n<br />MySQL Error: " . mysql_error());
			gotoUrl($_SERVER['PHP_SELF'] . '?id='.$_GET['id']);
		}
	}
	
	if (isset($_GET['c']) && $_GET['c'] == '0') {	//取消关注某人
		$query3 = "DELETE FROM concern WHERE idol='".$_GET['id']."' AND fan='".$_SESSION['studentNumber']."'";
		$result3 = mysql_query($query3) or trigger_error("Query: $query \n<br />MySQL Error: " . mysql_error());
		gotoUrl($_SERVER['PHP_SELF'] . '?id='.$_GET['id']);
	}
	
	
	$page_title = $_GET['id'] .' - 桃李湖畔';
	$profileFlag = false;
	$pageFlag = false;
	$concernFlag = false;
	
	$query = "SELECT * FROM students_profile WHERE stu_num='".$_GET['id']."'";
	$result = mysql_query($query) or trigger_error("Query: $query \n<br />MySQL Error: " . mysql_error());
	$row = mysql_fetch_array($result);
	if (mysql_num_rows($result) != 0) {
		$profileFlag = true;
		$page_title = $row['name'] .' - 桃李湖畔';
	}
	
	$query2 = "SELECT * FROM students_page WHERE stu_num='".$_GET['id']."'";
	$result2 = mysql_query($query2) or trigger_error("Query: $query \n<br />MySQL Error: " . mysql_error());
	$row2 = mysql_fetch_array($result2);
	if (mysql_num_rows($result2) != 0) {
		$pageFlag = true;
	}
	
	$query3 = "SELECT * FROM concern WHERE idol='".$_GET['id']."' AND fan='".$_SESSION['studentNumber']."'";
	$result3 = mysql_query($query3) or trigger_error("Query: $query \n<br />MySQL Error: " . mysql_error());
	$row3 = mysql_fetch_array($result3);
	if (mysql_num_rows($result3) != 0) {
		$concernFlag = true;
	}
	
}
$successFlag = false;	//留言成功与否哨兵
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
	<script src="http://www.google.com/jsapi?key=ABQIAAAABb9KZHHJTyEmQTbgH3nubRRSPIdEuYaah-coEaLR0SZEGdFe2hTeBcJYqi0oeZr7WTKYOG5oTf2zIA" type="text/javascript"></script>
	
	<script type="text/javascript" src="./musicplayer/swfobject.js"></script>
	<script type="text/javascript" src="./musicplayer/1bit.js"></script>
	<script type="text/javascript">
		oneBit = new OneBit('./musicplayer/1bit.swf');
		oneBit.ready(function() {
			oneBit.specify('color', '#5f5fff');
			oneBit.specify('background', '#ffffff');
			oneBit.specify('playerSize', '12');
			oneBit.specify('position', 'after');
			oneBit.specify('analytics', false);
			oneBit.apply('a');
		});
	</script>
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
		
		<?php
		if ($pageFlag) {
			echo '<img id="topImg" src="'.$row2['img'].'" alt="" />';
		}
		?>
		
		<div id="content" class="floatLeft">
			
			<div  class="module">
				<h2><?php if ($self) echo '我'; else echo 'TA';?>的经验</h2>
				<div class="content">
				<?php
					
					$query1 = "SELECT * FROM experience WHERE stu_num='".$_GET['id']."' ORDER BY date DESC LIMIT 1";
					$result1 = mysql_query($query1) or trigger_error("Query: $query \n<br />MySQL Error: " . mysql_error());
					$row1 = mysql_fetch_array($result1);
					if (mysql_num_rows($result1) != 0) {
						echo '<h3>'.$row1['title'].'</h3>';
						echo '<p class="date">'.$row1['date'].'</p>';
						echo '<p>'.$row1['content'].'</p>';
						echo '<p><a href="../experience/?grade='.$row1['grade'].'">也去给'.ucwords($row1['grade']).'们写点经验&gt;&gt;</a><a href="../experience/?uid='.$_GET['id'].'"  class="floatRight">更多经验&gt;&gt;</a></p>';
						
					} else {
						echo '<p>主人暂时还没有发布任何经验。</p>';
					}
					
					?>
				</div>
			</div>
			
			<div  class="module">
				<h2><?php if ($self) echo '我'; else echo 'TA';?>的书评</h2>
				<div class="content">
				<?php
					
					$query1 = "SELECT * FROM book_comment WHERE stu_num='".$_GET['id']."' ORDER BY date DESC LIMIT 1";
					$result1 = mysql_query($query1) or trigger_error("Query: $query \n<br />MySQL Error: " . mysql_error());
					$row1 = mysql_fetch_array($result1);
					if (mysql_num_rows($result1) != 0) {
						echo '<h3>'.$row1['title'].'</h3>';
						echo '<p class="date">'.$row1['date'].'</p>';
						echo '<p>'.$row1['content'].'</p>';
						echo '<p><a href="../books/comments.php?id='.$row1['book_id'].'">去看看这本书&gt;&gt;</a><a href="books/index.php?uid='.$_GET['id'].'"  class="floatRight">更多书评&gt;&gt;</a></p>';
						
					} else {
						echo '<p>主人暂时还没有发布任何书评。</p>';
					}
					
				?>
				</div>
			</div>
			
			<div  class="module">
				<h2><?php if ($self) echo '我'; else echo 'TA';?>的偶像</h2>
				<div class="content">
				<?php
					
					$query1 = "SELECT * FROM concern WHERE fan='".$_GET['id']."' ORDER BY date DESC LIMIT 60";
					$result1 = mysql_query($query1) or trigger_error("Query: $query \n<br />MySQL Error: " . mysql_error());
					
					if (mysql_num_rows($result1) != 0) {
						
						while ($row1 = mysql_fetch_array($result1))
							echo '<a href="?id='.$row1['idol'].'"><img src="http://' . $_SERVER['HTTP_HOST'].'/taolihupan' . '/images/students/'.$row1['idol'].'.jpg" alt="'.$row1['idol'].'"/></a>';
						
					} else {
						echo '<p>暂时还没有偶像。</p>';
					}
					
				?>
				</div>
			</div>
			
			<div  class="module">
				<h2><?php if ($self) echo '我'; else echo 'TA';?>的粉丝</h2>
				<div class="content">
				<?php
					
					$query1 = "SELECT * FROM concern WHERE idol='".$_GET['id']."' ORDER BY date DESC LIMIT 60";
					$result1 = mysql_query($query1) or trigger_error("Query: $query \n<br />MySQL Error: " . mysql_error());
					
					if (mysql_num_rows($result1) != 0) {
						
						while ($row1 = mysql_fetch_array($result1))
							echo '<a href="?id='.$row1['fan'].'"><img src="http://' . $_SERVER['HTTP_HOST'].'/taolihupan' . '/images/students/'.$row1['fan'].'.jpg" alt="'.$row1['fan'].'"/></a>';
						
					} else {
						echo '<p>暂时还没有粉丝。</p>';
					}
					
				?>
				</div>
			</div>
		</div>

		<div id="sideBar" class="floatRight">
			<div  class="widget">
				<img src="http://<?php echo $_SERVER['HTTP_HOST'].'/taolihupan' . '/images/students/' . $_GET['id'] . '_b.jpg';?>" />
			</div>
			
			<?php
			
			if ($profileFlag) {
				echo '<div class="widget">';
				echo '<h2>';
				if ($self) echo '我'; else echo 'TA';
				echo '的学生证</h2><div  class="content">';
				echo '<table><tr><td><h1>'.$row['name'].'</h1></td>';
				if ($row['qq']) {
					echo '<td><a href="tencent://message/?uin='.$row['qq'].'&Menu=yes"><img src="http://wpa.qq.com/pa?p=1:'.$row['qq'].':7" alt="Q我吧！" /></td></tr></table>';
				} else {
					echo '</tr></table>';
				}
				
				echo '<p class="tips">'.$row['academy'].'学院 / '.$row['stu_num'].'</p>';
				echo '<p class="tips">'.$row['birthday'].' / '.$row['hometown'];
				if ($row['phone']) {
					echo ' / '.$row['phone'].'</p>';
				} else {
					echo '</p>';
				}
				
				if (!$self) {
				
					if ($concernFlag) {
						echo '<p><a href="?id='.$_GET['id'].'&c=0">取消关注TA&gt;&gt;</a></p>';
					} else {
						echo '<p><a href="?id='.$_GET['id'].'&c=1">关注TA&gt;&gt;</a></p>';
					}
					
				}
				
				echo '</div></div>';
			}
			
			?>
			
			<?php
			if ($row2['music_addr']) {
				?>
				<div class="widget">
					<h2><?php if ($self) echo '我'; else echo 'TA';?>的随身听</h2>
					<div class="content">
						<a href="<?php echo $row2['music_addr'];?>"><?php echo $row2['music_title']?></a>
					</div>
				</div>
				<?php
			}
			?>
			
			<div class="widget">
				<h2><?php if ($self) echo '我'; else echo 'TA';?>的留言本</h2>
				<div class="content">
					<?php
					if(isset($_POST['addComment'])) {
						if (!empty($_POST['note'])) {
							if (mb_strlen($_POST['note'], 'UTF-8') <= 140 ) {
								
								$content = escape_data($_POST['note']);
								$query = "INSERT INTO students_page_comment (host, via, content, date) VALUES ('".$_GET['id']."', '".$_SESSION['studentNumber']."','$content', now())";
								$result = mysql_query($query) or trigger_error("Query: $query \n<br />MySQL Error: " . mysql_error());
								$successFlag = true;
								
							} else {
								echo '<p>请将留言内容限制在140字以内。</p>';
							}
						} else {
							echo '<p>请输入留言内容。</p>';
						}
					}
					?>
					<form action="<?php echo $_SERVER['PHP_SELF'].'?id='.$_GET['id'] ?>" method="post">
						<textarea name="note" id="note" ><?php if(isset($_POST['note']) && !$successFlag) echo $_POST['note']; ?></textarea>
						<p class="center"><input type="submit" value="留 言" class="send"/></p>
						<input type="hidden" name="addComment" value="true" />
					</form>
					<?php
			
					$pageSize = 5;		//每页项目数
					$query = "SELECT id FROM students_page_comment WHERE host='".$_GET['id']."'";
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
						
						$query = "SELECT * FROM students_page_comment WHERE host='".$_GET['id']."' ORDER BY date DESC LIMIT $offset,$pageSize";
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
			
		</div>

		<div id="footer">
			&#169; 2010 桃李湖畔<span class="floatRight">创建于2010年暑假</span>
		</div>
	</div>

</body>
</html>

<?php
ob_end_flush();
?>