<?php
	class ActivitiesItem{
		private $activitieData;
		
		function __construct($param){
			$this -> activitieData = $param;
		}
		
		function display(){
			echo '<div class="commentItem">';
			echo '<a href="../student.php?id='.$this -> activitieData['stu_num'].'">';
			echo '<img class="floatLeft" src="' . BASE_PATH . '/images/students/' . $this -> activitieData['stu_num'] . '.jpg" alt="'.$this -> activitieData['stu_num'].'" /></a>';
			echo '<div class="comment"><h4>'.$this -> activitieData['title'].'</h4>';
			echo '<div>';
			echo '<p>'.'时间：'.$this -> activitieData['start_time'].'</p>';
			echo '<p>'.'地点：'.$this -> activitieData['address'].'</p>';
			$content=$this -> activitieData['content'];
			formatOutput($content);
			echo '</div>';
			echo '</div></div>';
		}
	}
?>