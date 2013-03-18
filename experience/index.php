<?php

require_once('../includes/config.inc.php');
require_once('../includes/util.inc.php');
$page_title = '经验 - 桃李湖畔';
include('../includes/header.html');

if(!isset($_SESSION['studentNumber'])) {
	$url = 'http://' . $_SERVER['HTTP_HOST'].'/taolihupan' . '/';
	
	ob_end_clean();
	header("Location: $url");
	exit();
}

?>

<div id="tag">
	<p>并不是她说的是真理，<br />也不是他讲的都正确，<br />只是有些人会先经历，当你还在起点热身，他们已经胜利撞线。</p>
</div>


<div id="content" class="floatLeft">
	<?php
	require_once('../mysql_connect.php');
	if (isset($_GET['id'])){
		$id=intval($_GET['id']);
		$query = "SELECT * FROM experience WHERE id='$id'";
		$result = mysql_query($query) or trigger_error("Query: $query \n<br />MySQL Error: " . mysql_error());
		
		while($row = mysql_fetch_array($result)) {
			echo '<div class="commentItem">';
			echo '<a href="../student.php?id='.$row['stu_num'].'"><img src="http://' . $_SERVER['HTTP_HOST'].'/taolihupan' . '/images/students/'.$row['stu_num'].'.jpg" class="floatLeft" alt="'.$row['stu_num'].'"/></a>';
			echo '<div class="comment"><h4>'.$row['title'].'</h4>';
			echo '<p class="date">'.$row['date'].'</p>';
			echo '<div>'.$row['content'].'</div>';
			echo '</div></div>';
		}
	}else if (isset($_GET['grade']) && ($_GET['grade'] == 'freshman' || $_GET['grade'] == 'sophomore' || $_GET['grade'] == 'junior' || $_GET['grade'] == 'senior' || $_GET['grade'] == 'master1' || $_GET['grade'] == 'master2' || $_GET['grade'] == 'master3')) {
		$grade = $_GET['grade'];
		echo '<h1>You\'re '.$_GET['grade'].',huh?</h1>';
		$pageSize = 5;		//每页项目数
		$query = "SELECT id FROM experience WHERE grade='$grade'";
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
		
		$query = "SELECT * FROM experience WHERE grade='$grade' ORDER BY id DESC LIMIT $offset,$pageSize";
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
					echo '<a href="?grade='.$_GET['grade'].'&page=1">&lt;&lt;第一页</a>';
					echo '<a href="?grade='.$_GET['grade'].'&page=' . $pre . '">&lt;上一页</a>';
				}
				if ($page != $pageCount) {
					echo '<a href="?grade='.$_GET['grade'].'&page=' . $next . '">下一页&gt;</a>';
				}
			echo '</div>';
			
		}
		
		
	} else if (isset($_GET['uid'])){
		
		$query = "SELECT id FROM experience WHERE stu_num='".$_GET['uid']."'";
		$result = mysql_query($query) or trigger_error("Query: $query \n<br />MySQL Error: " . mysql_error());
		
		if (mysql_num_rows($result)==0) {
			echo '<h1>'.$_GET['uid'].'没有发布任何经验。</h1>';
		} else {
			
			echo '<h1>来自'.$_GET['uid'].'的经验之谈</h1>';
			
			$pageSize = 5;		//每页项目数
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
			
			$query = "SELECT * FROM experience WHERE stu_num='".$_GET['uid']."' ORDER BY date DESC LIMIT $offset,$pageSize";
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
						echo '<a href="?uid='.$_GET['uid'].'&page=1">&lt;&lt;第一页</a>';
						echo '<a href="?uid='.$_GET['uid'].'&page=' . $pre . '">&lt;上一页</a>';
					}
					if ($page != $pageCount) {
						echo '<a href="?uid='.$_GET['uid'].'&page=' . $next . '">下一页&gt;</a>';
					}
				echo '</div>';
				
			}
			
		}
		
		
		
	} else {
	?>
	
	<h1>在路上</h1>
	
	<table id="exp">
		<tr>
			<td>
			<h3><a href="?grade=freshman">大一</a></h3>
			<p>一入学就要考一场英语，为什么呀？</p>
			<p>听说冬天会在桃李湖上上滑冰课？</p>
			<p>图书馆的借阅流程是怎样的啊？</p>
			</td>
			<td>
			<h3><a href="?grade=sophomore">大二</a></h3>
			<p>选修课的A、B、C类是什么意思呀？</p>
			<p>四六级该怎么准备？</p>
			<p>课余时间充裕想去考个驾照？</p>
			</td>
		</tr>
		<tr>
			<td>
			<h3><a href="?grade=junior">大三</a></h3>
			<p>计算机等级证书你拿到了吗？</p>
			<p>爱要怎么说出口？</p>
			<p>到底要不要考研？</p>
			</td>
			<td>
			<h3><a href="?grade=senior">大四</a></h3>
			<p>保研了怎么找导师？</p>
			<p>考公务员需要注意哪些事项？</p>
			<p>给人打工还是自己创业？</p>
			</td>
		<tr>
		</tr>
			<td>
			<h3><a href="?grade=master1">研一</a></h3>
			<p></p>
			</td>
			<td>
			<h3><a href="?grade=master2">研二</a></h3>
			<p></p>
			</td>
		<tr>
			<td>
			<h3><a href="?grade=master3">研三</a></h3>
			<p></p>
			</td>
		</tr>
	</table>
	
	<?php
	}
	?>
</div>

<div id="sideBar" class="floatRight">
	
	<?php
	
	$successFlag = false;
	if (isset($_POST['addExperience'])) {
		
		$errors = array();	//初始化错误记录数组
		
		if (empty($_POST['title'])) {
			$errors[] = '<p>请填写标题。</p>';
		} else {
			$title = escape_data($_POST['title']);
		}
		
		if (empty($_POST['experienceContent'])) {
			$errors[] = '<p>请填写经验正文。</p>';
		} else if (mb_strlen($_POST['experienceContent'], 'UTF-8') > 2000 ) {
			$errors[] = '<p>请将简介限制在2000字以内。</p>';
		} else {
			$experienceContent = escape_data($_POST['experienceContent']);
		}
		
		if(empty($errors)) {
			
			$query = "INSERT INTO experience (stu_num, grade, title, content, date) VALUES ('".$_SESSION['studentNumber']."', '".$_POST['forWhom']."', '$title', '$experienceContent', now())";
			$result = mysql_query($query) or trigger_error("Query: $query \n<br />MySQL Error: " . mysql_error());
			if(mysql_affected_rows()==1) {
				$successFlag = true;
				
				gotoUrl($_SERVER['PHP_SELF']. "?grade=" .$_POST['forWhom']."&m=1");
			}
			
		} else {
			echo '<div class="widget"><h2>消息</h2><div class="content" id="message">';
			foreach($errors as $msg) {
				echo "$msg\n";
			}
		}
		echo '</div></div>';
	}
	
	if (isset($_GET['m']) && ($_GET['m']=='1')) {
		
		echo '<div class="widget"><h2>消息</h2><div class="content" id="message">';
		echo '<p>分享的人最快乐！谢谢你！</p>';
		echo '</div></div>';
	}

	?>
	
	<div  class="widget">
		<h2>分享经验</h2>
		<div  class="content">
			<p>人在内大，一路走来，<br />有自己的体会与感触？何不写下来，<br />学弟学妹们渴望听听过来人的声音。</p><br />
			<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
				<p>写给：<select name="forWhom" id="forWhom"><option value="freshman">大一的同学</option><option value="sophomore">大二的同学</option><option value="junior">大三的同学</option><option value="senior">大四的同学</option><option value="master1">研一的同学</option><option value="master2">研二的同学</option><option value="master3">研三的同学</option></select></p>
				<p>标题：<input type="text" name="title" id="title" value="<?php if(isset($_POST['title']) && !$successFlag) echo $_POST['title']; ?>" size="30" maxlength="30"/></p>
				<textarea name="experienceContent" id="experienceContent"><?php if (isset($_POST['experienceContent']) && !$successFlag) echo $_POST['experienceContent']; ?></textarea>
				<p class="center"><input type="submit" value="分享经验" class="send"/></p>
				<input type="hidden" name="addExperience" value="true" />
			</form>
		</div>
	</div>
	
	<div  class="widget">
		<h2>常用链接</h2>
		<div  class="content">
			<ul>
				<li><a href="http://jwxt.ndzsw.cn/" target="_blank">综合教务系统</a></li>
				<li><a href="http://61.134.115.100:8080/opac/search.php" target="_blank">图书馆检索系统</a></li>
				<li><a href="http://xscx.flagnet.net:8080/login.asp" target="_blank">诚信平台</a></li>
				<li><a href="http://cet.99sushe.com/" target="_blank">大学英语四六级查分系统</a></li>
			</ul>
		</div>
	</div>
</div>

<?php
include('../includes/footer.html');
?>