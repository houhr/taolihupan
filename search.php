<?php

require_once('./includes/config.inc.php');
require_once('./includes/util.inc.php');
if(!empty($_REQUEST['search_word'])){
	$searchWord=$_REQUEST['search_word'];
	$page_title = '搜索 - '.$searchWord;
}else{
	gotoUrl('/');
}
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
	<p align="center"><br/>
	<a href="?in=all&search_word=<?php echo $searchWord;?>">全部</a>&nbsp;
	<a href="?in=student&search_word=<?php echo $searchWord;?>">同学</a>&nbsp;
	<a href="?in=activities&search_word=<?php echo $searchWord;?>">活动</a>&nbsp;
	<a href="?in=play&search_word=<?php echo $searchWord;?>">玩乐</a>&nbsp;
	<a href="?in=book&search_word=<?php echo $searchWord;?>">书籍</a>&nbsp;
	<a href="?in=experience&search_word=<?php echo $searchWord;?>">经验</a>&nbsp;
	<a href="?in=community&search_word=<?php echo $searchWord;?>">社团</a>&nbsp;
	<br /></p>
</div>
<?php
	require_once('mysql_connect.php');
?>
<div id="content" class="floatLeft">
	<?php
		$pageSize = 15;
		if(empty($_REQUEST['in'])){
			$in='all';
		}else{
			$in= $_REQUEST['in'];
		}
		if ($in != 'all' && $in != 'book'){
			$fun='search'.ucwords($in);
			$total = $fun($searchWord,0,100000,false);
			$pageCount = ceil($total / $pageSize);
			$page = isset($_GET["page"]) ? intval($_GET["page"]) : 1;
			if ($page > $pageCount) {
				$page = $pageCount;
			}
			if ($page <= 0) {
				$page = 1;
			}
			$offset = ($page - 1) * $pageSize;
			$fun($searchWord,$offset,$pageSize,true);
		}else{
			if($in == 'all'){
				$searchIn=array('Student','Activities','Play','Book','BookComment'
					,'Experience','Community');
			}else{
				$searchIn=array('Book','BookComment');
			}
			$countSize = array();
			foreach($searchIn as $si){
				$fun='search'.$si;
				$countSize[]=$fun($searchWord,0,100000,false);
			}
			$total = array_sum($countSize);
			$pageCount = ceil($total / $pageSize);
			$page = isset($_GET["page"]) ? intval($_GET["page"]) : 1;
			if ($page > $pageCount) {
				$page = $pageCount;
			}
			if ($page <= 0) {
				$page = 1;
			}
			$offset = ($page - 1) * $pageSize;
			$leaveItem=$pageSize;
			for($i=0;$i<count($searchIn);$i++){
				if($offset<=$countSize[$i]){
					$fun='search'.$searchIn[$i];
					$t=$fun($searchWord,$offset,$leaveItem,true);
					$offset-=$t;
					$offset=($offset>0?$offset:0);
					$leaveItem-=$t;
					if($leaveItem<=0)
						break;
				}else{
					$offset-=$countSize[$i];
				}
			}
		}
		$pre = ($page - 1) < 1 ?  1 : ($page - 1);
		$next = ($page + 1) > $pageCount ?  $pageCount : $next = ($page + 1);
		if ($total > $pageSize) {
			echo '<div id="pageNavi">';
				if ($page != 1) {
					echo '<a href="?page=1&in='.$in.'&search_word='.$searchWord.'">&lt;&lt;第一页</a>';
					echo '<a href="?page='.$pre.'&in='.$in.'&search_word='.$searchWord.'">&lt;上一页</a>';
				}
				if ($page != $pageCount) {
					echo '<a href="?page='.$next.'&in='.$in.'&search_word='.$searchWord. '">下一页&gt;</a>';
				}
			echo '</div>';
			
		}
		
		function searchPlay($searchWord,$start,$maxLength,$print){
			$query="SELECT * FROM `play` WHERE "
				."`title` LIKE '%$searchWord%' "
				."OR `address` LIKE '%$searchWord%' "
				."OR `via` LIKE '%$searchWord%' LIMIT $start,$maxLength";
			$result = mysql_query($query) 
				or trigger_error("Query: $query \n<br />MySQL Error: " . mysql_error());
			if($print)
				makeMessage($searchWord,$result);
			return mysql_num_rows($result);
		}
		
		function searchStudent($searchWord,$start,$maxLength,$print){
			$query="SELECT * FROM `students_profile` WHERE "
				."`stu_num` LIKE '%$searchWord%' "
				."OR `name` LIKE '%$searchWord%' "
				."OR `academy` LIKE '%$searchWord%' "
				."OR `sex` LIKE '%$searchWord%' "
				."OR `hometown` LIKE '%$searchWord%' "
				."OR `phone` LIKE '%$searchWord%' "
				."OR `qq` LIKE '%$searchWord%' "
				."OR `birthday` LIKE '%$searchWord%' LIMIT $start,$maxLength";
			$result = mysql_query($query) 
				or trigger_error("Query: $query \n<br />MySQL Error: " . mysql_error());
			if($print)
				makeMessage($searchWord,$result);
			return mysql_num_rows($result);
		}
		
		function searchActivities($searchWord,$start,$maxLength,$print){
			$query="SELECT * FROM `activities` WHERE "
				."`title` LIKE '%$searchWord%' "
				."OR `start_time` LIKE '%$searchWord%' "
				."OR `address` LIKE '%$searchWord%' "
				."OR `content` LIKE '%$searchWord%' "
				."OR `stu_num` LIKE '%$searchWord%' LIMIT $start,$maxLength";
			$result = mysql_query($query) 
				or trigger_error("Query: $query \n<br />MySQL Error: " . mysql_error());
			if($print)
				makeMessage($searchWord,$result);
			return mysql_num_rows($result);
		}
		
		function searchExperience($searchWord,$start,$maxLength,$print){
			$query="SELECT * FROM `experience` WHERE "
				."`stu_num` LIKE '%$searchWord%' "
				."OR `grade` LIKE '%$searchWord%' "
				."OR `title` LIKE '%$searchWord%' "
				."OR `content` LIKE '%$searchWord%'  LIMIT $start,$maxLength";
			$result = mysql_query($query) 
				or trigger_error("Query: $query \n<br />MySQL Error: " . mysql_error());
			if($print)
				makeMessage($searchWord,$result);
			return mysql_num_rows($result);
		}
		
		function searchCommunity($searchWord,$start,$maxLength,$print){
			$query="SELECT * FROM `community` WHERE "
				."`name` LIKE '%$searchWord%' "
				."OR `leader` LIKE '%$searchWord%' "
				."OR `tel` LIKE '%$searchWord%' "
				."OR `about` LIKE '%$searchWord%' "
				."OR `adder` LIKE '%$searchWord%' LIMIT $start,$maxLength";
			$result = mysql_query($query) 
				or trigger_error("Query: $query \n<br />MySQL Error: " . mysql_error());
			if($print)
				makeMessage($searchWord,$result);
			return mysql_num_rows($result);
		}
		
		function searchBook($searchWord,$start,$maxLength,$print){
			$query="SELECT * FROM `book` WHERE "
				."`name` LIKE '%$searchWord%' "
				."OR `author` LIKE '%$searchWord%' "
				."OR `publish_company` LIKE '%$searchWord%' "
				."OR `search_number` LIKE '%$searchWord%' LIMIT $start,$maxLength";
			$result = mysql_query($query) 
				or trigger_error("Query: $query \n<br />MySQL Error: " . mysql_error());
			if($print)
				makeMessage($searchWord,$result);
			return mysql_num_rows($result);
		}
		
		function searchBookComment($searchWord,$start,$maxLength,$print){
			$query="SELECT * FROM `book_comment`,`book` WHERE "
				."`book`.`id` = `book_id` AND "
				."(`stu_num` LIKE '%$searchWord%' "
				."OR `content` LIKE '%$searchWord%' "
				."OR `title` LIKE '%$searchWord%') LIMIT $start,$maxLength";
			$result = mysql_query($query) 
				or trigger_error("Query: $query \n<br />MySQL Error: " . mysql_error());
			if($print)
				makeMessage($searchWord,$result);
			return mysql_num_rows($result);
		}
		
		function makeMessage($searchWord,$result){
			$t=mysql_fetch_field($result);
			switch ($t -> table){
			case 'play':
				while($row = mysql_fetch_array($result)){
					$ply = new message();
					$ply -> title =$row['title'];
					$ply -> msg = '地址：'.$row['address'].'; '
								.'via：'.$row['via'].'; '
								.formatOutput($row['content'],true);
					$ply ->href='./play/index.php?id='.$row['id'];
					$ply -> display($searchWord);
				}
				break;
			case 'book_comment':
				while($row = mysql_fetch_array($result)){
					$bok = new message();
					$bok -> title =$row['title'];
					$bok -> msg = $row['stu_num'].'号同学给《'.$row['name'].'》的评论; '
								.$row['content'];
					$bok ->href='./books/comments.php?id='.$row['book_id'].'&cid='.$row['id'];
					$bok -> display($searchWord);
				}
				break;
			case 'book':
				while($row = mysql_fetch_array($result)){
					$bok = new message();
					$bok -> title =$row['name'];
					$bok -> msg = '作者：'.$row['author'].'; '
								.'出版社：'.$row['publish_company'].'; '
								.'索书号：'.$row['search_number'];
					$bok ->href='./books/comments.php?id='.$row['id'];
					$bok -> display($searchWord);
				}
				break;
			case 'community':
				while($row = mysql_fetch_array($result)){
					$com = new message();
					$com -> title =$row['name'];
					$com -> msg = 'Leader：'.$row['leader'].'; '
								.'招新热线：'.$row['tel'].'; '
								.'via：'.$row['adder'].'; '
								.$row['about'];
					$com ->href='';
					$com -> display($searchWord);
				}
				break;
			case 'experience':
				while($row = mysql_fetch_array($result)){
					$exp = new message();
					$exp -> title = $row['title'];
					$grade = $row['grade'];
					switch($grade){
					case 'freshman':
						$grade='大一';
						break;
					case 'sophomore':
						$grade='大二';
						break;
					case 'junior':
						$grade='大三';
						break;
					case 'senior':
						$grade='大四';
						break;
					case 'master1':
						$grade='研一';
						break;
					case 'master2':
						$grade='研二';
						break;
					case 'master3':
						$grade='研三';
						break;
					}
					$exp -> msg = $row['stu_num'].'号同学写给 '
								.$grade.'的同学们。 '
								.$row['content'];
					$exp ->href='';
					$exp -> display($searchWord);
				}
				break;
			case 'activities':
				while($row = mysql_fetch_array($result)){
					$act = new message();
					$act -> title = $row['title'];
					$act -> msg = '时间：'.$row['start_time'].'; '
								.'地点：'.$row['address'].'; '
								.'via：'.$row['stu_num'].'; '
								.formatOutput($row['content'],true);
					$act ->href='./activities/index.php?id='.$row['id'];
					$act -> display($searchWord);
				}
				break;
			case 'students_profile':
				while($row = mysql_fetch_array($result)){
					$stu = new message();
					$stu -> title = $row['stu_num'].'号同学';
					$stu -> msg = '姓名：'.$row['name'].'; '
								.'学院：'.$row['academy'].'; '
								.'性别：'.$row['sex'].'; '
								.'生日：'.$row['birthday'].'; '
								.'家乡：'.$row['hometown'].'; '
								.'电话：'.$row['phone'].'; '
								.'QQ：'.$row['qq'];
					$stu ->href = './student.php?id='.$row['stu_num'];
					$stu -> display($searchWord);
				}
				break;
			}
		}
		
		class message{
			public $title;
			public $msg;
			public $href;
			public function __get($name){
				return $this -> name;
			}
			public function __set($name,$value){
				$this -> name = $value;
			}
			public function display($searchWord){
				echo '<div class="search_result">';
				if(!empty($this->href)){
					echo '<a href="'.$this->href.'">';
				}
				echo '<h3>'.str_replace($searchWord,'<span class="key_word">'
							.$searchWord.'</span>',$this->title).'</h3>';
				echo '<p>'.str_replace($searchWord,'<span class="key_word">'
							.$searchWord.'</span>',substr_cut($this->msg)).'</p>';
				if(!empty($this->href)){
					echo '</a>';
				}
				echo '</div>';
			}
		}
	?>
</div>

<?php
include(BASE_PATH . '/includes/footer.html');
?>