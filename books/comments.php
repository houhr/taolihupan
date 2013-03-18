<?php

require_once('../includes/config.inc.php');
require_once('../includes/util.inc.php');
$page_title = '书籍 - 桃李湖畔';
include('../includes/header.html');

if(!isset($_SESSION['studentNumber'])) {
	$url = 'http://' . $_SERVER['HTTP_HOST'].'/taolihupan' . '/';
	
	ob_end_clean();
	header("Location: $url");
	exit();
}

if (!isset($_GET['id'])) {
	$url = 'http://' . $_SERVER['HTTP_HOST'].'/taolihupan' . dirname($_SERVER['PHP_SELF']);
	if((substr($url, -1) == '/') OR (substr($url, -1) == '\\')) {
		$url =substr($url, 0, -1);
	}
	$url .= '/index.php';
	gotoUrl($url);
} else {
	require_once('../mysql_connect.php');
	$query = "SELECT id FROM book WHERE id=".$_GET['id'];
	$result = mysql_query($query) or trigger_error("Query: $query \n<br />MySQL Error: " . mysql_error());
	if (mysql_num_rows($result) == 0) {
		$url = 'http://' . $_SERVER['HTTP_HOST'].'/taolihupan' . dirname($_SERVER['PHP_SELF']);
		if((substr($url, -1) == '/') OR (substr($url, -1) == '\\')) {
			$url =substr($url, 0, -1);
		}
		$url .= '/index.php';
		gotoUrl($url);
	}
}

?>

<div id="tag">
	<p>也许你从未踏进图书馆的宽敞大门</p>
	<p>也许你的图书证已经磨得不成样子</p>
	<p>但那里，静静地躺着一些文字，没错，它们在等你去发现它们。</p>
</div>


<div id="content" class="floatLeft">
	<?php
		$query = "SELECT * FROM book WHERE id=".$_GET['id'];
		$result = mysql_query($query) or trigger_error("Query: $query \n<br />MySQL Error: " . mysql_error());
		$row = mysql_fetch_array($result);
		echo '<div class="bookItem">';
		if ($row['cover']) {
			echo '<img src="' . $row['cover'] . '" class="bookCover floatLeft" alt="'.$row['name'].'" />';
		} else {
			echo '<img src="http://'. $_SERVER['HTTP_HOST'].'/taolihupan' . '/images/bookcover.jpg" class="bookCover floatLeft" />';
		}
		echo '<h3>' . $row['name'] . '</h3>';
		echo '<p>'.$row['author'].'</p>';
		echo '<p>'.$row['publish_company'].'</p>';
		echo '<p>'.$row['search_number'].'</p>';
		echo '<p><a href="http://61.134.115.100:8080/opac/openlink.php?callno='.$row['search_number'].'&doctype=ALL&lang_code=ALL&displaypg=20&sort=CATA_DATE&orderby=desc&showmode=list&location=ALL" target="_blank">借阅此书</a></p>';
		echo '</div>';
		
		$id = $_GET['id'];
		if (isset($_GET['cid'])) {	//显示单独的消息
			$t = $_GET['cid'];
			$queryBase = " FROM book_comment WHERE id='$t' ";
		}else{
			$queryBase =" FROM book_comment WHERE book_id='$id' ";
		}
		$pageSize = 5;		//每页项目数
		$query = "SELECT id".$queryBase;
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
		
		$query = "SELECT *". $queryBase ." ORDER BY id DESC LIMIT $offset,$pageSize";
		$result = mysql_query($query) or trigger_error("Query: $query \n<br />MySQL Error: " . mysql_error());
		
		while($row = mysql_fetch_array($result)) {
			echo '<div class="commentItem">';
			echo '<a href="../student.php?id='.$row['stu_num'].'"><img src="http://' . $_SERVER['HTTP_HOST'].'/taolihupan' . '/images/students/'.$row['stu_num'].'.jpg" class="floatLeft" alt="'.$row['stu_num'].'"/></a>';
			echo '<div class="comment"><h4>'.$row['title'].'</h4>';
			echo '<p class="date">'.$row['date'].'</p>';
			echo '<div>'.$row['content'].'</div>';
			echo '</div></div>';
		}
		
		if ($total > $pageSize) {
			
			echo '<div id="pageNavi">';
				if ($page != 1) {
					echo '<a href="?id='.$_GET['id'].'&page=1">&lt;&lt;第一页</a>';
					echo '<a href="?id='.$_GET['id'].'&page=' . $pre . '">&lt;上一页</a>';
				}
				if ($page != $pageCount) {
					echo '<a href="?id='.$_GET['id'].'&page=' . $next . '">下一页&gt;</a>';
				}
			echo '</div>';
			
		}
	?>
</div>

<div id="sideBar" class="floatRight">
	<?php
	
	$successFlag = false;
	if (isset($_POST['addBookComment'])) {
		
		$errors = array();	//初始化错误记录数组
		
		if (empty($_POST['title'])) {
			$errors[] = '<p>请填写标题。</p>';
		} else {
			$title = escape_data($_POST['title']);
		}
		
		if (empty($_POST['commentContent'])) {
			$errors[] = '<p>请填写书评正文。</p>';
		} else if (mb_strlen($_POST['commentContent'], 'UTF-8') > 2000 ) {
			$errors[] = '<p>请将简介限制在2000字以内。</p>';
		} else {
			$commentContent = escape_data($_POST['commentContent']);
		}
		
		if(empty($errors)) {
			
			$query = "INSERT INTO book_comment (stu_num, book_id, content, title, date) VALUES ('".$_SESSION['studentNumber']."', '".$_GET['id']."', '$commentContent', '$title', now())";
			$result = mysql_query($query) or trigger_error("Query: $query \n<br />MySQL Error: " . mysql_error());
			if(mysql_affected_rows()==1) {
				
				$successFlag = true;
				gotoUrl($_SERVER['PHP_SELF'] . '?id='.$_GET['id'].'&m=1');
				
			}
			
		} else {
			echo '<div class="widget"><h2>消息</h2><div class="content">';
			foreach($errors as $msg) {
				echo "$msg\n";
			}
		}
		echo '</div></div>';
	}
	
	if (isset($_GET['m']) && ($_GET['m'] == '1')) {
		echo '<div class="widget"><h2>消息</h2><div class="content">';
		echo '<p>发表成功！谢谢你！</p>';
		echo '</div></div>';
	}
	
	
	if (!(isset($_GET['m']) && $_GET['m']=='1')) {
	?>
	<div  class="widget">
		<h2>添加书评</h2>
		<div class="content">
			<p>对这本书有何看法，快发表下你的高见吧！</p><br />
			<form action="<?php echo $_SERVER['PHP_SELF'].'?id='.$_GET['id'] ?>" method="post">
				<p>标题：<input type="text" name="title" id="title" value="<?php if(isset($_POST['title'])) echo $_POST['title']; ?>" size="30" maxlength="30"/></p>
				<textarea name="commentContent" id="commentContent"><?php if (isset($_POST['commentContent'])) echo $_POST['commentContent']; ?></textarea>
				<p class="center"><input type="submit" value="发表书评" class="send"/></p>
				<input type="hidden" name="addBookComment" value="true" />
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