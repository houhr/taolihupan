<?php

require_once('../includes/config.inc.php');
require_once('../includes/util.inc.php');
$page_title = '咬喃™ - 桃李湖畔';
include('../includes/header.html');

if(!isset($_SESSION['studentNumber'])) {
	$url = 'http://' . $_SERVER['HTTP_HOST'].'/taolihupan' . '/';
	
	ob_end_clean();
	header("Location: $url");
	exit();
}

?>

<div id="tag">
	<p>咬喃何意？内蒙古中部方言，意思是说啊说啊说，<br />咬喃何音？英音[ yǎo nàn ]，美音[ yǎo nàn ] 
	<object data="../images/yaonan.swf" type="application/x-shockwave-flash" width="16" height="16" id="pronunciation">
	<param name="movie" value="../images/yaonan.swf">
	</object>
	<br />二手交易、失物招领、票务转让……来尝尝这道内蒙古的亚文化大餐吧！</p>
</div>

<div id="content" class="floatLeft">
	
	<?php
	require_once('../mysql_connect.php');
	$correctId = false;
	
	if (isset($_GET['id'])) {	//页面处理有效的咬喃id，主体内容
		
		$query = "SELECT * FROM yaonan WHERE id='".$_GET['id']."'";
		$result = mysql_query($query) or trigger_error("Query: $query \n<br />MySQL Error: " . mysql_error());
		$row = mysql_fetch_array($result);
		if (mysql_num_rows($result)==0) {
			echo '<h1>该条咬喃不存在，或已被删除</h1>';
		} else {
		
			$correctId = $row['id'];
			
			echo '<h1>'.$row['topic'].'</h1>';
			echo '<a href="../student.php?id='.$row['via'].'"><img src="http://' . $_SERVER['HTTP_HOST'].'/taolihupan' . '/images/students/' . $row['via'] . '.jpg " class="floatLeft" alt="'.$row['via'].'"/></a>';
			echo '<div class="comment"><p class="date">'.$row['date'].'</p>';
			echo '<p>'.$row['content'].'</p></div>';
			
			//显示回复
			$insideQuery = "SELECT * FROM yaonan_reply WHERE belong='" . $row['id'] . "' ORDER BY date";
			$insideResult = mysql_query($insideQuery) or trigger_error("Query: $query \n<br />MySQL Error: " . mysql_error());
			
			while($insideRow = mysql_fetch_array($insideResult)) {
				
				echo '<a href="../student.php?id='.$insideRow['via'].'"><img src="http://' . $_SERVER['HTTP_HOST'].'/taolihupan' . '/images/students/' . $insideRow['via'] . '.jpg " class="floatRight" alt="'.$insideRow['via'].'"/></a>';
				echo '<div class="replyComment"><p class="date">'.$insideRow['date'].'</p>';
				echo '<p>'.$insideRow['content'].'</p></div>';
				
			}
			
		}
		
	} else if (isset($_GET['uid'])){	//某人的全部咬喃
		
		$query = "SELECT id FROM yaonan WHERE via='".$_GET['uid']."'";
		$result = mysql_query($query) or trigger_error("Query: $query \n<br />MySQL Error: " . mysql_error());
		
		if (mysql_num_rows($result)==0) {
			echo '<h1>'.$_GET['uid'].'没有发布任何咬喃</h1>';
		} else {
			echo '<h1>来自'.$_GET['uid'].'的咬喃</h1>';
			$pageSize = 20;		//每页项目数
			$total = mysql_num_rows($result);		//总项目数
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
			
			$query = "SELECT * FROM yaonan WHERE via='".$_GET['uid']."' ORDER BY date DESC LIMIT $offset,$pageSize";
			$result = mysql_query($query) or trigger_error("Query: $query \n<br />MySQL Error: " . mysql_error());
			
			echo '<table id="yaonan">';
			while($row = mysql_fetch_array($result)) {
				$cut = $row['topic'];
				if (mb_strlen($row['topic'], 'UTF-8') > 20) {
					$cut = mb_substr($row['topic'], 0, 20, 'UTF-8') . '...';
				}
				
				$insideQuery = "SELECT id FROM yaonan_reply WHERE belong='".$row['id']."'";		//查询每条咬喃的回复数
				$insideResult = mysql_query($insideQuery) or trigger_error("Query: $query \n<br />MySQL Error: " . mysql_error());
				$insideRow = mysql_num_rows($insideResult);
				
				$cutDate = mb_substr( $row['date'], 0, 16, 'UTF-8');
				
				echo '<tr><td class="title"><a href="?id='.$row['id'].'">'.$cut.'</a></td><td class="via">via '.$row['via'].'</td><td class="reply">'.$insideRow.' 条回复</td><td class="date">'.$cutDate.'</td></tr>';
				
			}
			echo '</table>';
			
			if ($total > $pageSize) {
				
				echo '<div id="pageNavi">';
					if ($page != 1) {
						echo '<a href="?uid='.$_GET['uid'].'&page=1">&lt;&lt;第一页</a>';
						echo '<a href="?uid='.$_GET['uid'].'&page=' . $pre . '">&lt;上一页</a>';
					}
					if ($page != $pageCount) {
						echo '<a href="?uid='.$_GET['uid'].'&page=' . $next . '">下一页&gt;</a>';
					}
				echo '</div>';
				
			}
			
		}
		
		
	} else {	//正常浏览
	
		echo '<h1>看看大家都在咬喃什么</h1>';
		$pageSize = 20;		//每页项目数
		$query = "SELECT id FROM yaonan";
		$result = mysql_query($query) or trigger_error("Query: $query \n<br />MySQL Error: " . mysql_error());
		$total = mysql_num_rows($result);		//总项目数
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
		
		$query = "SELECT * FROM yaonan ORDER BY date DESC LIMIT $offset,$pageSize";
		$result = mysql_query($query) or trigger_error("Query: $query \n<br />MySQL Error: " . mysql_error());
		
		echo '<table id="yaonan">';
		while($row = mysql_fetch_array($result)) {
			$cut = $row['topic'];
			if (mb_strlen($row['topic'], 'UTF-8') > 20) {
				$cut = mb_substr($row['topic'], 0, 20, 'UTF-8') . '...';
			}
			
			$insideQuery = "SELECT id FROM yaonan_reply WHERE belong='".$row['id']."'";		//查询每条咬喃的回复数
			$insideResult = mysql_query($insideQuery) or trigger_error("Query: $query \n<br />MySQL Error: " . mysql_error());
			$insideRow = mysql_num_rows($insideResult);
			
			$cutDate = mb_substr( $row['date'], 0, 16, 'UTF-8');
			
			echo '<tr><td class="title"><a href="?id='.$row['id'].'">'.$cut.'</a></td><td class="via">via '.$row['via'].'</td><td class="reply">'.$insideRow.' 条回复</td><td class="date">'.$cutDate.'</td></tr>';
			
		}
		echo '</table>';
		
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
	}
	?>
</div>

<div id="sideBar" class="floatRight">

	<?php
	
	if ($correctId) {	//页面处理有效的咬喃id，边栏内容
		
		$replySuccessFlag = false;
		if (isset($_POST['reply'])) {
			echo '<div class="widget"><h2>消息</h2><div class="content" id="message">';
			
			$errors = array();	//初始化错误记录数组
			
			if (empty($_POST['replyContent'])) {
				$errors[] = '<p>请填写回复内容。</p>';
			} else if (mb_strlen($_POST['replyContent'], 'UTF-8') > 2000 ) {
				$errors[] = '<p>请将回复内容限制在2000字以内。</p>';
			} else {
				$replyContent = escape_data($_POST['replyContent']);
			}
			
			if(empty($errors)) {
				
				$query = "INSERT INTO yaonan_reply (belong, via, content, date) VALUES ('".$_GET['id']."', '".$_SESSION['studentNumber']."', '$replyContent', now())";
				$result = mysql_query($query) or trigger_error("Query: $query \n<br />MySQL Error: " . mysql_error());
				if(mysql_affected_rows()==1) {
					gotoUrl($_SERVER['PHP_SELF'] . '?id=' . $_GET['id']);
				}
				
			} else {
				foreach($errors as $msg) {
					echo "$msg\n";
				}
			}
			echo '</div></div>';
		}
		
		?>

		<div  class="widget">
			<h2>回复TA</h2>
			<div  class="content">
				<form action="<?php echo $_SERVER['PHP_SELF'] . '?id=' . $correctId ?>" method="post">
					<textarea name="replyContent" id="replyContent"><?php if(isset($_POST['replyContent']) && !$replySuccessFlag) echo $_POST['replyContent']; ?></textarea>
					<p class="center"><input type="submit" value="回复" class="send"/></p>
					<input type="hidden" name="reply" value="true" />
				</form>
			</div>
		</div>
		<?php
		
	} else {	//正常浏览，边栏
	
		$successFlag = false;
		if (isset($_POST['yaonan'])) {
			echo '<div class="widget"><h2>消息</h2><div class="content" id="message">';
			
			$errors = array();	//初始化错误记录数组
			
			if (empty($_POST['title'])) {
				$errors[] = '<p>请填写话题。</p>';
			} else {
				$title = escape_data($_POST['title']);
			}
			
			if (empty($_POST['yaonanContent'])) {
				$errors[] = '<p>请填写咬喃正文。</p>';
			} else if (mb_strlen($_POST['yaonanContent'], 'UTF-8') > 2000 ) {
				$errors[] = '<p>请将咬喃正文限制在2000字以内。</p>';
			} else {
				$yaonanContent = escape_data($_POST['yaonanContent']);
			}
			
			if(empty($errors)) {
				
				$query = "INSERT INTO yaonan (via, topic, content, date) VALUES ('".$_SESSION['studentNumber']."', '$title', '$yaonanContent', now())";
				$result = mysql_query($query) or trigger_error("Query: $query \n<br />MySQL Error: " . mysql_error());
				if(mysql_affected_rows()==1) {
					
					gotoUrl($_SERVER['PHP_SELF']);
					
				}
				
			} else {
				foreach($errors as $msg) {
					echo "$msg\n";
				}
			}
			echo '</div></div>';
		}
		
		
		
		?>

		<div  class="widget">
			<h2>今天你咬喃了吗</h2>
			<div  class="content">
				<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
					<p>话题：<input type="text" name="title" id="title" value="<?php if(isset($_POST['title']) && !$successFlag) echo $_POST['title']; ?>" size="30" maxlength="30"/></p>
					<textarea name="yaonanContent" id="yaonanContent"><?php if(isset($_POST['yaonanContent']) && !$successFlag) echo $_POST['yaonanContent']; ?></textarea>
					<p class="center"><input type="submit" value="咬喃" class="send"/></p>
					<input type="hidden" name="yaonan" value="true" />
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