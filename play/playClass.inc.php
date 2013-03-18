<?php
	class PlayItem{
		private $playData;
		
		function __construct($param){
			$this -> playData = $param;
		}
		
		function display(){
			echo '<div class="commentItem">';
			echo '<a href="../student.php?id='.$this -> playData['via'].'">';
			echo '<img class="floatLeft" src="' . BASE_PATH . '/images/students/' . $this -> playData['via'] . '.jpg" alt="'.$this -> playData['via'].'" /></a>';
			echo '<div class="comment"><h4>'.$this -> playData['title'].'</h4>';
			echo '<div>';
			echo '<p>'.'地点：'.$this -> playData['address'].'</p>';
			$content=$this -> playData['content'];
			formatOutput($content);
			echo '</div>';
			echo '</div></div>';
		}
	}
?>