<?php
define('BASE_PATH','..');
require_once(BASE_PATH . '/includes/config.inc.php');
require_once(BASE_PATH . '/includes/util.inc.php');
$page_title = '玩乐 - 桃李湖畔';
include(BASE_PATH . '/includes/header.inc.php');

if(!isset($_SESSION['studentNumber'])) {
	$url = 'http://' . $_SERVER['HTTP_HOST'].'/taolihupan' . '/';
	
	ob_end_clean();
	header("Location: $url");
	exit();
}
?>

<div id="tag">
	<p>哪有鸟瞰全市的摩天轮？<br/>
	哪有冰欺凌或者酸奶店？<br/>
	快来翻翻这本周边游玩的全攻略，里面一定有你中意的风景或格子店。</p>
</div>
<?php
	require_once(BASE_PATH.'/play/playClass.inc.php');
	require_once('../mysql_connect.php');
?>
<div id="content" class="floatLeft">
	<h1>哇！PLAY STATION，么么</h1>
<?php
	if (isset($_GET['tag'])) {	//以tag为参数显示
		echo '<p>以下是标签为 “' . $_GET['tag'] . '” 的玩乐：</p>';	
		$t = $_GET['tag'];
		$queryBase = " FROM play_tag,play WHERE play_tag.content='$t' AND play_tag.play_id=play.id ";
	}else if (isset($_GET['id'])) {	//显示单独的消息
		$t = $_GET['id'];
		$queryBase = " FROM play WHERE play.id='$t'";
	}else{
		$queryBase =" FROM play ";
	}
	$pageSize = 5;		//每页项目数
	//$query = "SELECT id FROM play";
	$query = "SELECT `play`.id".$queryBase;
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
	
	$query = "SELECT * ".$queryBase."ORDER BY `play`.id DESC LIMIT $offset,$pageSize";
	$result = mysql_query($query) or trigger_error("Query: $query \n<br />MySQL Error: " . mysql_error());
	
	while($row = mysql_fetch_array($result)) {
		$item = new PlayItem($row);
		$item -> display();
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
?>
</div>
<div id="sideBar" class="floatRight">
<?php
	if(isset($_GET['success'])) {
		echo '<div class="widget"><h2>消息</h2><div class="content" id="message">';
		echo '玩乐已成功添加，谢谢你的分享！';
		echo '</div></div>';
	}
	
	if(isset($_POST['addPlay'])) {
		
		echo '<div class="widget"><h2>消息</h2><div class="content" id="message">';
		
		$errors = array();	//初始化错误记录数组
		
		if (empty($_POST['playName'])) {
			$errors[] = '<p>请填写玩乐名称。</p>';
		} else {
			$playName = escape_data($_POST['playName']);
		}
		
		if (empty($_POST['playAddr'])) {
			$errors[] = '<p>请填写玩乐地点。</p>';
		} else {
			$playAddr = escape_data($_POST['playAddr']);
		}
		
		if (!empty($_POST['editorArea'])) {
			$playContent = escape_data($_POST['editorArea']);
		}
		
		if(empty($errors)) {
				$query = "INSERT INTO play(title, address, content, via) VALUES ( '$playName', '$playAddr', ".(isset($playContent)?"'$playContent'":"NULL") .",'".$_SESSION['studentNumber']."')";
				$result = mysql_query($query) or trigger_error("Query: $query \n<br />MySQL Error: " . mysql_error());
				if(mysql_affected_rows()==1) {
					$playID=mysql_insert_id();
					if (!empty($_POST['playTag'])) {
						$tags = array();
						$tags = explode(" ", $_POST['playTag']);
						
						foreach($tags as $tag) {
							$query = "INSERT INTO play_tag (play_id, content) VALUES ('$playID','$tag')";
							$result = mysql_query($query) or trigger_error("Query: $query \n<br />MySQL Error: " . mysql_error());
						}
					}
					gotoUrl(BASE_PATH.'/play/index.php?success');	
				}
		} else {
			foreach($errors as $msg) {
				echo "$msg\n";
			}
		}
		echo '</div></div>';
	}
?>
	<div class="widget">
		<h2>添加玩乐</h2>
		<div class="content">
			<p>又发现好玩的地方啦？给大家介绍介绍呗！</p><br />
			<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
				<table>
					<tr><td class="marks">玩乐名称：</td><td><input type="text" name="playName" id="playName" value="<?php if(isset($_POST['playName'])) echo $_POST['playName']; ?>" size="20" maxlength="80" /></td></tr>
					<tr><td></td><td class="tips">如店铺名称、景点名称等</td></tr>
					<tr><td class="marks">地点：</td><td><input type="text" name="playAddr" id="playAddr" value="<?php if(isset($_POST['playAddr'])) echo $_POST['playAddr']; ?>" size="20" maxlength="80" /></td></tr>
					<tr><td class="marks">标签：</td><td><input type="text" name="playTag" id="playTag" value="<?php if(isset($_POST['playTag'])) echo $_POST['playTag']; ?>" size="20" maxlength="20" /></td></tr>
					<tr><td></td><td class="tips">选填，若有多个请用空格隔开</td></tr>
					<tr><td class="marks">补充说明：</td><td class="tips">选填，如可添加地图、照片、链接、</td></tr>
					<tr><td></td><td class="tips">介绍文字等</td></tr>
				</table>
				<?php
					$editor=new editor();
					$editor -> uploadFilePath='play';
					$editor -> display();
				?>
				<p class="center"><input type="submit" value="发布玩乐" class="send"/></p>
				<input type="hidden" name="addPlay" value="true" />
			</form>
		</div>
	</div>
	<?php 
	//TAG
	$tagTable='play_tag';
	require(BASE_PATH.'/includes/tag.inc.php'); 
	?>
</div>
<?php
include(BASE_PATH . '/includes/footer.html');

?>