<?php
define('BASE_PATH','..');
require_once(BASE_PATH . '/includes/config.inc.php');
require_once(BASE_PATH . '/includes/util.inc.php');
$page_title = '活动 - 桃李湖畔';
include(BASE_PATH . '/includes/header.inc.php');

if(!isset($_SESSION['studentNumber'])) {
	$url = 'http://' . $_SERVER['HTTP_HOST'].'/taolihupan' . '/';
	
	ob_end_clean();
	header("Location: $url");
	exit();
}
?>

<div id="tag">
	<p>大学生活从来不是一成不变，<br/>
	这儿就是那丰富多彩的一页。<br/>
	来吧，看看到哪儿能结交三五好友，或者开开眼界。</p>
</div>
<?php
	require_once(BASE_PATH.'/activities/activitiesClass.inc.php');
	require_once('../mysql_connect.php');
?>
<div id="content" class="floatLeft">
	<h1>近期活动</h1>
	<p>知道吗？手机登录 m.taolihupan.com ，可以随时随地查看今日活动哦。[内测期间尚未开通]<p>
<?php
	if (isset($_GET['tag'])) {	//以tag为参数显示
		echo '<p>以下是标签为 “' . $_GET['tag'] . '” 的活动：</p>';	
		$t = $_GET['tag'];
		$queryBase = " FROM activities_tag,activities WHERE activities_tag.content='$t' AND activities_tag.activities_id=activities.id AND activities.pass=1 ";
	}else if (isset($_GET['id'])) {	//显示单独的消息
		$t = $_GET['id'];
		$queryBase = " FROM activities WHERE activities.id='$t' AND pass=1 ";
	}else{
		$queryBase =" FROM activities WHERE pass=1 ";
	}
	$pageSize = 5;		//每页项目数
	//$query = "SELECT id FROM activities";
	$query = "SELECT `activities`.id".$queryBase;
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
	$tomorrow = mktime(0,0,0,date("m"),date("d"),date("Y"));
	//删除之前活动的标签
	$query ="SELECT id FROM `activities` WHERE `start_time`< '".date('Y-n-j G:i',$tomorrow)."'";
	$result = mysql_query($query) or trigger_error("Query: $query \n<br />MySQL Error: " . mysql_error());
	while($row = mysql_fetch_array($result)) {
		$query1="DELETE FROM `activities_tag` WHERE `activities_id`= '".$row['id']."'";
		mysql_query($query1) or trigger_error("Query: $query \n<br />MySQL Error: " . mysql_error());
	}
	//删除之前活动
	$query ="DELETE FROM `activities` WHERE `start_time`< '".date('Y-n-j G:i',$tomorrow)."'";
	mysql_query($query) or trigger_error("Query: $query \n<br />MySQL Error: " . mysql_error());
	
	$query = "SELECT * ".$queryBase."ORDER BY `activities`.start_time LIMIT $offset,$pageSize";
	$result = mysql_query($query) or trigger_error("Query: $query \n<br />MySQL Error: " . mysql_error());
	
	while($row = mysql_fetch_array($result)) {
		$item = new ActivitiesItem($row);
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
		echo '活动已成功发布，等待管理员审核。<br />谢谢你的热心参与 : )';
		echo '</div></div>';
	}
	
	if(isset($_POST['addActivities'])) {
		
		echo '<div class="widget"><h2>消息</h2><div class="content" id="message">';
		
		$errors = array();	//初始化错误记录数组
		
		if (empty($_POST['activitiesName'])) {
			$errors[] = '<p>请填写活动名称。</p>';
		} else {
			$activitiesName = escape_data($_POST['activitiesName']);
		}
		
		if (empty($_POST['activitiesAddr'])) {
			$errors[] = '<p>请填写活动地点。</p>';
		} else {
			$activitiesAddr = escape_data($_POST['activitiesAddr']);
		}
		
		if (!empty($_POST['editorArea'])) {
			$activitiesContent = escape_data($_POST['editorArea']);
		}
		
		$startTime = date('Y').'-'.$_POST['month'].'-'.$_POST['day'].' '.$_POST['hour'].':'.$_POST['minute'].':00';
		
		
		if(empty($errors)) {
				$query = "INSERT INTO `activities`(`title`,`start_time`,`address`,
					`content`,`stu_num`) VALUES ( '$activitiesName',
					'$startTime','$activitiesAddr',"
					.(isset($activitiesContent)?"'$activitiesContent'":"NULL")
					.",'".$_SESSION['studentNumber']."')";
				$result = mysql_query($query) or trigger_error("Query: $query \n<br />MySQL Error: " . mysql_error());
				if(mysql_affected_rows()==1) {
					$activitiesID=mysql_insert_id();
					if (!empty($_POST['activitiesTag'])) {
						$tags = array();
						$tags = explode(" ", $_POST['activitiesTag']);
						
						foreach($tags as $tag) {
							$query = "INSERT INTO activities_tag (activities_id, content) VALUES ('$activitiesID','$tag')";
							$result = mysql_query($query) or trigger_error("Query: $query \n<br />MySQL Error: " . mysql_error());
						}
					}
					gotoUrl(BASE_PATH.'/activities/index.php?success');	
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
		<h2>发布活动</h2>
		<div class="content">
			<p>最近有新活动？快告诉同学们吧！</p><br />
			<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
				<table>
					<tr><td class="marks">活动名称：</td><td><input type="text" name="activitiesName" id="activitiesName" value="<?php if(isset($_POST['activitiesName'])) echo $_POST['activitiesName']; ?>" size="20" maxlength="80" /></td></tr>
					<tr><td class="marks">时间：</td><td>
					<select id='month' name="month">
					<?php
					for ($i=1;$i<13;$i++)
					{
						echo '<option value="'.$i.'"';
						if(date('n')==$i){
							echo 'selected="selected"';
						}
						echo '>';
						if($i<10)
						echo '&nbsp;&nbsp;';
						echo $i.'</option>';
					}
					?>
					</select>
					月
					<select id="day" name="day">
					<?php
					for ($i=1;$i<32;$i++)
					{
						echo '<option value="'.$i.'"';
						if(date('d')==$i){
							echo 'selected="selected"';
						}
						echo '>';
						if($i<10)
						echo '&nbsp;&nbsp;';
						echo $i.'</option>';
					}
					?>
					</select>
					日</td></tr>
					
					<tr><td></td><td>
					<select id="hour" name="hour">
					 <?php
					for ($i=0;$i<24;$i++)
					{
						echo '<option value="'.$i.'"';
						if(19==$i){
							echo 'selected="selected"';
						}
						echo '>';
						if($i<10)
						echo '&nbsp;&nbsp;';
						echo $i.'</option>';
					}
					?>
					</select>
					时
					<select id="minute" name="minute">
					<?php
					  for ($i=0;$i<6;$i++)
					{
						echo '<option value="'.$i.'0"';
						if(3==$i){
							echo 'selected="selected"';
						}
						echo '>'.$i.'0</option>';
					}
					?>
					</select>
					分
					</td></tr>
					<tr><td class="marks">地点：</td><td><input type="text" name="activitiesAddr" id="activitiesAddr" value="<?php if(isset($_POST['activitiesAddr'])) echo $_POST['activitiesAddr']; ?>" size="20" maxlength="80" /></td></tr>
					<tr><td class="marks">标签：</td><td><input type="text" name="activitiesTag" id="activitiesTag" value="<?php if(isset($_POST['activitiesTag'])) echo $_POST['activitiesTag']; ?>" size="20" maxlength="20" /></td></tr>
					<tr><td></td><td class="tips">选填，若有多个请用空格隔开</td></tr>
					<tr><td class="marks">补充说明：</td><td class="tips">选填，如可添加海报、链接、地图、</td></tr>
					<tr><td></td><td class="tips">说明文字等</td></tr>
				</table>
				<?php
					$editor=new editor();
					$editor -> uploadFilePath='activities';
					$editor -> display();
				?>
				<p class="center"><input type="submit" value="发布活动" class="send"/></p>
				<input type="hidden" name="addActivities" value="true" />
			</form>
		</div>
	</div>
	<?php 
	//TAG
	$tagTable='activities_tag';
	require(BASE_PATH.'/includes/tag.inc.php'); 
	?>
</div>

<?php
include(BASE_PATH . '/includes/footer.html');
?>