<div  class="widget">
	<h2>热门标签</h2>
	<div  class="content">
			<?php
	
				$query = "SELECT content,count(content) AS c FROM $tagTable GROUP BY content ORDER BY c DESC LIMIT 50";
				$result = mysql_query($query) or trigger_error("Query: $query \n<br />MySQL Error: " . mysql_error());
				while($row = mysql_fetch_array($result)) {
					echo '<a href="?tag='. $row['content'] .'" class="tags">' . $row['content'] . '</a>';
				}
				mysql_close();
			?>
	</div>
</div>