<?php
define('BASE_PATH','..');
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
?>

<div id="tag">
	<p>是否还彷徨在图书馆之外，<br />用空虚来填充时间的空白，<br />何不来书架间转转，这些智慧的精灵，定会将你空荡的心海灌溉。</p>
</div>

<div id="content" class="floatLeft">
	
	<?php
		require_once('../mysql_connect.php');
		
		if (isset($_GET['uid'])) {
			
			$query = "SELECT id FROM book_comment WHERE stu_num='".$_GET['uid']."'";
			$result = mysql_query($query) or trigger_error("Query: $query \n<br />MySQL Error: " . mysql_error());
			
			if (mysql_num_rows($result)==0) {
				echo '<h1>'.$_GET['uid'].'没有发布任何书评。</h1>';
			} else {
				
				echo '<h1>来自'.$_GET['uid'].'的书评</h1>';
				
				$pageSize = 10;		//每页项目数
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
				
				$query = "SELECT * FROM book_comment WHERE stu_num='".$_GET['uid']."' ORDER BY id DESC LIMIT $offset,$pageSize";
				$result = mysql_query($query) or trigger_error("Query: $query \n<br />MySQL Error: " . mysql_error());
				
				while($row = mysql_fetch_array($result)) {
					echo '<div class="commentItem">';
					echo '<a href="../student.php?id='.$row['stu_num'].'"><img src="http://' . $_SERVER['HTTP_HOST'].'/taolihupan' . '/images/students/'.$row['stu_num'].'.jpg" class="floatLeft" alt="'.$row['stu_num'].'"/></a>';
					echo '<div class="comment"><h4>'.$row['title'].'</h4>';
					echo '<p class="date">'.$row['date'].'</p>';
					echo '<div>'.$row['content'].'</div>';
					echo '<p>&nbsp;<a href="comments.php?id='.$row['book_id'].'" class="floatRight">查看这本书&gt;&gt;</a></p>';
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
			
			
		} else if (isset($_GET['tag'])) {	//以tag为参数显示相关书籍
			
			echo '<h1>为同学找好书，为好书找同学</h1><p>以下是标签为 “' . $_GET['tag'] . '” 的书籍：</p>';
			
			$t = $_GET['tag'];
			$query = "SELECT book_id FROM book_tag WHERE content='$t' ";
			$result = mysql_query($query) or trigger_error("Query: $query \n<br />MySQL Error: " . mysql_error());
			
			while($row = mysql_fetch_array($result)) {
				$queryInside = "SELECT * FROM book WHERE id='$row[0]' ";
				$resultInside = mysql_query($queryInside) or trigger_error("Query: $queryInside \n<br />MySQL Error: " . mysql_error());
				$rowInside = mysql_fetch_array($resultInside);
				echo '<div class="bookItem">';
				if ($rowInside['cover']) {
					echo '<a href="./comments.php?id=' . $rowInside['id'] . '"><img src="' . $rowInside['cover'] . '" class="bookCover floatLeft" alt="'.$rowInside['name'].'" /></a>';
				} else {
					echo '<a href="./comments.php?id=' . $rowInside['id'] . '"><img src="http://'. $_SERVER['HTTP_HOST'].'/taolihupan' . '/images/bookcover.jpg" class="bookCover floatLeft" alt="'.$rowInside['name'].'" /></a>';
				}
				echo '<h3><a href="./comments.php?id=' . $rowInside['id'] . '">' . $rowInside['name'] . '</a></h3>';
				echo '<p>'.$rowInside['author'].'</p>';
				echo '<p>'.$rowInside['publish_company'].'</p>';
				echo '<p>'.$rowInside['search_number'].'</p>';
				echo '<p><a href="http://61.134.115.100:8080/opac/openlink.php?callno='.$rowInside['search_number'].'&doctype=ALL&lang_code=ALL&displaypg=20&sort=CATA_DATE&orderby=desc&showmode=list&location=ALL" target="_blank">借阅此书</a></p>';
				echo '<p><a href="./comments.php?id=' . $rowInside['id'] . '">写读后感</a></p>';
				echo '</div>';
			}
			
		} else {	//正常翻页
			echo '<h1>为同学找好书，为好书找同学</h1>';
			$pageSize = 5;		//每页项目数
			$query = "SELECT id FROM book";
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
			
			$query = "SELECT * FROM book ORDER BY id DESC LIMIT $offset,$pageSize";
			$result = mysql_query($query) or trigger_error("Query: $query \n<br />MySQL Error: " . mysql_error());
			
			while($row = mysql_fetch_array($result)) {
				echo '<div class="bookItem">';
				if ($row['cover']) {
					echo '<a href="./comments.php?id=' . $row['id'] . '"><img src="' . $row['cover'] . '" class="bookCover floatLeft" alt="'.$row['name'].'"/></a>';
				} else {
					echo '<a href="./comments.php?id=' . $row['id'] . '"><img src="http://'. $_SERVER['HTTP_HOST'].'/taolihupan' . '/images/bookcover.jpg" class="bookCover floatLeft" alt="'.$row['name'].'"/></a>';
				}
				echo '<h3><a href="./comments.php?id=' . $row['id'] . '">' . $row['name'] . '</a></h3>';
				echo '<p>'.$row['author'].'</p>';
				echo '<p>'.$row['publish_company'].'</p>';
				echo '<p>'.$row['search_number'].'</p>';
				echo '<p><a href="http://61.134.115.100:8080/opac/openlink.php?callno='.$row['search_number'].'&doctype=ALL&lang_code=ALL&displaypg=20&sort=CATA_DATE&orderby=desc&showmode=list&location=ALL" target="_blank">借阅此书</a> / <a href="./comments.php?id=' . $row['id'] . '">写读后感</a></p>';
				echo '</div>';
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
		}
	?>
</div>

<div id="sideBar" class="floatRight">
	
	<?php
	
	$successFlag = false;
	
	if(isset($_POST['addBook'])) {
		
		$errors = array();	//初始化错误记录数组
		
		if (empty($_POST['bookName'])) {
			$errors[] = '<p>请填写书名。</p>';
		} else {
			$bookName = escape_data($_POST['bookName']);
		}
		
		if (empty($_POST['author'])) {
			$errors[] = '<p>请填写作者。</p>';
		} else {
			$author = escape_data($_POST['author']);
		}
		
		if (empty($_POST['publisher'])) {
			$errors[] = '<p>请填写出版社。</p>';
		} else {
			$publisher = escape_data($_POST['publisher']);
		}
		
		if (empty($_POST['searchNumber'])) {
			$errors[] = '<p>请填写出索书号。</p>';
		} else {
			$searchNumber = strtoupper(escape_data(str_replace(" ", "", $_POST['searchNumber'])));	//去掉空格，并转换为大写
		}
		
		if (empty($errors)) {
		
			$query = "SELECT id FROM book WHERE search_number='$searchNumber' ";
			$result = mysql_query($query) or trigger_error("Query: $query \n<br />MySQL Error: " . mysql_error());
			$row = mysql_fetch_array($result);
			if (mysql_num_rows($result)==0) {	//通过索书号检查是否已经有这本书
				
				if (empty($_POST['bookCover'])) {
					$query = "INSERT INTO book (name, author, publish_company, search_number) VALUES ('$bookName', '$author', '$publisher', '$searchNumber')";
				} else {
					$cover = escape_data($_POST['bookCover']);
					$query = "INSERT INTO book (name, author, publish_company, search_number, cover) VALUES ('$bookName', '$author', '$publisher', '$searchNumber','$cover')";
				}
				$result = mysql_query($query) or trigger_error("Query: $query \n<br />MySQL Error: " . mysql_error());
				if(mysql_affected_rows()==1) {
					$query = "SELECT id FROM book WHERE search_number='$searchNumber' ";
					$result = mysql_query($query) or trigger_error("Query: $query \n<br />MySQL Error: " . mysql_error());
					$row = mysql_fetch_array($result);
					$successFlag = true;
					
					gotoUrl($_SERVER['PHP_SELF'] . '?id='.$row['id'].'&m=1');
					
					if (!empty($_POST['tags'])) {	//给书添加标签
						$tags = array();
						$tags = explode(" ", $_POST['tags']);
						
						foreach($tags as $tag) {
							$query = "INSERT INTO book_tag (book_id, content) VALUES ('$row[0]','$tag')";
							$result = mysql_query($query) or trigger_error("Query: $query \n<br />MySQL Error: " . mysql_error());
						}
					}
					
				}
				
			} else {
				echo '<div class="widget"><h2>消息</h2><div class="content"  id="message">';
				echo '<p>这本书已经有同学添加过啦！它在<a href="./comments.php?id=' . $row['id'] . '">这儿</a>呢！</p>';		//$result['id']中存的是找到的已经存在的书的id
			}
			
		} else {
			echo '<div class="widget"><h2>消息</h2><div class="content"  id="message">';
			foreach($errors as $msg) {
				echo "$msg\n";
			}
		}
		echo '</div></div>';
		
	}
	
	if (isset($_GET['m']) && ($_GET['m'] == '1')) {
		echo '<div class="widget"><h2>消息</h2><div class="content"  id="message">';
		echo '<p>添加成功！谢谢你！<br />也许，你想为这本书<a href="./comments.php?id=' . $_GET['id'] . '">写篇读后感</a>？</p>';
		echo '</div></div>';
	}
	
	?>
	<div class="widget">
		<h2>添加书籍</h2>
		<div class="content">
			<p>在图书馆里淘到本好书？分享给大家吧 :)</p><br />
			<form action="<?php if (isset($_GET['id'])) echo $_SERVER['PHP_SELF'].$_GET['id']; else echo $_SERVER['PHP_SELF']; ?>" method="post">
				<table>
					<tr><td class="marks">书名：</td><td><input type="text" name="bookName" id="bookName" value="<?php if(isset($_POST['bookName']) && !$successFlag) echo $_POST['bookName']; ?>" size="20"  maxlength="30"/></td></tr>
					<tr><td class="marks">作者：</td><td><input type="text" name="author" id="author" value="<?php if(isset($_POST['author']) && !$successFlag) echo $_POST['author']; ?>" size="20" maxlength="50"/></td></tr>
					<tr><td class="marks">出版社：</td><td><input type="text" name="publisher" id="publisher" value="<?php if(isset($_POST['publisher']) && !$successFlag) echo $_POST['publisher']; ?>" size="20" maxlength="50"/></td></tr>
					<tr><td class="marks">索书号：</td><td><input type="text" name="searchNumber" id="searchNumber" value="<?php if(isset($_POST['searchNumber']) && !$successFlag) echo $_POST['searchNumber']; ?>" size="20" maxlength="30"/></td></tr>
					<tr><td></td><td class="tips">索书号位于书脊红色标签上<br />两行索书号请用“/”隔开<br />如：TP312PH/G151</td></tr>
					<tr><td class="marks">标签：</td><td><input type="text" name="tags" id="tags" value="<?php if(isset($_POST['tags']) && !$successFlag) echo $_POST['tags']; ?>" size="20" maxlength="20"/></td></tr>
					<tr><td></td><td class="tips">选填，若有多个请用空格隔开</td></tr>
					<tr><td class="marks">封面：</td><td><input type="text" name="bookCover" id="bookCover" value="<?php if(isset($_POST['bookCover']) && !$successFlag) echo $_POST['bookCover']; ?>" size="20" maxlength="100"/></td></tr>
					<tr><td></td><td class="tips">选填，请填写网上图片地址</td></tr>
				</table>
				<p class="center"><input type="submit" value="添加此书" class="send"/></p>
				<input type="hidden" name="addBook" value="true" />
			</form>
		</div>
	</div>
<?php
	$tagTable='book_tag';
	require(BASE_PATH.'/includes/tag.inc.php'); 
?>
</div>


<?php
include('../includes/footer.html');

?>