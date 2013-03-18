<?php

DEFINE ('DB_USER', '');
DEFINE ('DB_PASSWORD', '');
DEFINE ('DB_HOST', '');
DEFINE ('DB_NAME', '');

if($dbc = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD)) {
	
	if(!mysql_select_db(DB_NAME)) {
		trigger_error("无法选择数据库!\n<br />MySQL Error:" . mysql_error());
		include('includes/footer.html');
		exit();
	}
	
} else {
	trigger_error("无法连接到MySQL！\n<br />MySQL Error:" . mysql_error());
	include('includes/footer.html');
	exit();
}

function escape_data ($data) {	//对有问题的字符进行转义
	
	$data = nl2br($data);	//将换行字符转成<br />
	
	if(ini_get('magic_quotes_gpc')) {
		$data = stripslashes($data);
	}
	
	if(function_exists('mysql_real_escape_string')) {
		global $dbc;
		$data = mysql_real_escape_string(trim($data), $dbc);
	} else {
		$data = mysql_escape_string(trim($data));
	}
	
	return $data;
}

?>