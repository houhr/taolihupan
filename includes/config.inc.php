<?php
$live = false;
$email = 'i@houhong.ru';
if(!defined('BASE_PATH')){
	define('BASE_PATH','.');
}
define('MAX_FILE_SIZE','3145728');

function my_error_handler($e_number, $e_message, $e_file, $e_line, $e_vars) {
	global $live, $email;
	$message = "在文件" . $e_file . "的第" . $e_line . "行发生了一个错误：\n<br />". $e_message . "\n<br />";
	$message .= "时间：" . date('Y年j月n日 H:i:s') . "\n<br />";
	$message .= "<pre>" . print_r($e_vars, 1) . "</pre>\n<br />";
	if($live) {
		//error_log($message, 1, $email);
		if($e_number != E_NOTICE) {
			echo '<div id="error">系统错误。</div><br />';
		}
	} else {
		echo '<div id="error">' . $message . '</div><br />';
	}
}

set_error_handler('my_error_handler');


?>