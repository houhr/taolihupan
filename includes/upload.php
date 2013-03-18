<?php
	echo 'uploading';
	define('BASE_PATH','..');
	include('./util.inc.php');
	$path='';
	if(isset($_POST['upload_path'])){
		$path=trim($_POST['upload_path']).'/';
	}
	$destination_path = BASE_PATH.'/images/'.$path;
	$arr_name = explode('.',basename($_FILES['upload_file']['name']));
	$target_path = $destination_path . date('Y-n-j-G-i-s') . '-' . rand(0,1000) . '.' . $arr_name[count($arr_name)-1];

	if(@move_uploaded_file($_FILES['upload_file']['tmp_name'], $target_path)) {
		$result = 1;
	}
	sleep(1);
	$c=1;
?>

<script language="javascript" type="text/javascript">
	window.top.window.stopUpload(
	<?php 
		$c=1;
		echo $result.',"'.toAbsAdd($_SERVER['HTTP_HOST'].'/taolihupan'.$_SERVER['PHP_SELF'],
			str_replace('..','', $target_path,$c)).'"'; 
	?>
	);
</script>   
