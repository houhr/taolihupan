<?php
session_name('taolihupan');
session_start();
require_once('includes/util.inc.php');
if(isset($_COOKIE['taolihupan_autologin'])) {
	
	$_SESSION['studentNumber'] = numToCookie($_COOKIE['taolihupan_autologin']);
	$url = 'http://' . $_SERVER['HTTP_HOST'].'/taolihupan';
	if((substr($url, -1) == '/') OR (substr($url, -1) == '\\')) {
		$url =substr($url, 0, -1);
	}
	$url .= '/home.php';
	header("Location: $url");
	exit();
} else {

	if(isset($_SESSION['studentNumber'])) {
		$url = 'http://' . $_SERVER['HTTP_HOST'].'/taolihupan';
		if((substr($url, -1) == '/') OR (substr($url, -1) == '\\')) {
			$url =substr($url, 0, -1);
		}
		$url .= '/home.php';
		header("Location: $url");
		exit();
	}
}
require_once('ipv6.php');
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xml:lang="zh-cn" lang="zh-cn">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>桃李湖畔</title>
	<meta content="内蒙古大学学生电子社区" name="description"/>
	<link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
	<link rel="icon" href="favicon.ico" type="image/x-icon" />
	<link rel="bookmark" href="favicon.ico" type="image/x-icon" />
	<style rel="stylesheet" type="text/css" >
		*{margin:0;padding:0;}
		*,:active,:focus{outline:none;}
		body,button,input{font:14px/160% "Microsoft YaHei","lucidaGrande",Verdana,Arial,'宋体',Sans-Serif;}
		body{overflow:hidden;}
		a{text-decoration:none;}
		.blackboard{background:transparent url("./images/index.jpg") no-repeat;width:950px;height:508px;position:relative;padding-bottom:350px;margin-top:50px;}
		#wrapper{margin:0 auto;width:950px;background-color:#fff;}
		#reg,#login,#join{position:absolute;left:620px;}
		#reg,#login{top:118px;}
		#join{top:70px;}
		#reg td,.indexLink,#regtdlabel,#login td,.indexLink,#logintd label,#join,#go,.indexMarks input,#ok{color:#ddd;}
		#indexTips{color:#ff5f5f;font-size:17px;line-height:160%;}
		#indexTips:hover{border-bottom:1px solid #ff5f5f;}
		.indexLink:hover{border-bottom:1px solid #ddd;}
		#go,#ok{width:180px;height:30px;margin:10px 0;background-color:#2b5642;border-width:1px;border-color:#406666 #1a4a34 #1a4a34 #406666;cursor:pointer;}
		.indexMarks{font-size:22px;margin-top:20px;}
		.indexMarks input{width:168px;padding:3px;font-size:18px;font-family:Georgia,"TimesNewRoman",Times,serif;background-color:#224c36;border-width:0 0 1px 0;border-bottom-color:#ddd;}
		#autologin,#agreement{margin-right:5px;vertical-align:middle;}
		#cover,#dialog{display:none;}
		#cover{position:absolute;z-index:9998;top:0;left:0;background-color:#e3e3e3;filter:alpha(Opacity=60);opacity:0.6;}
		#dialog{position:fixed;z-index:9999;width:240px;background-color:#406963;color:#ddd;border:5px #c36b43 solid;text-align:center;padding:20px 0;}
		#close{margin-top:10px;width:100px;}
		#ip{position:relative;top:-65px;left:130px;color:#fff;}
	</style>
</head>
<body>
	
	<div id="wrapper">
		
		<a id="1">&nbsp;</a>
		<div id="register">
			<div class="blackboard">
				<form id="reg" action="./register.php" method="post" onsubmit="return check();">
					<table>
						<tr class="indexMarks"><td>学号：</td><td><input type="text" name="regStudentNumber" id="regStudentNumber" /></td></tr>
						<tr class="indexMarks"><td>邮箱：</td><td><input type="text" name="regEmail" id="regEmail" /></td></tr>
						<tr class="indexMarks"><td>密码：</td><td><input type="password" name="regPassword" id="regPassword" /></td></tr>
						<tr><td></td><td><input type="checkbox" name="agreement" id="agreement" /><label for="agreement">我已阅读并同意</label>《<a class="indexLink" href="http://<?php echo $_SERVER['HTTP_HOST'].'/taolihupan';?>/agreement.php" target="_blank">使用协议</a>》</td></tr>
						<tr><td></td><td><input type="submit" value="注册" id="ok" /></td></tr>
						<tr><td></td><td><a class="indexLink" href="http://<?php echo $_SERVER['HTTP_HOST'].'/taolihupan';?>/newcomer.php">未入学新生通道&gt;&gt;</a></td></tr>
						<tr><td></td><td><a class="indexLink" href="#2" onclick="scrollGoTo(950,0);return false;">返回登录页面&gt;&gt;</a></td></tr>
					</table>
					<input type="hidden" name="submitted" value="true" />
				</form>
			</div>
		</div>
		
		<a id="2">&nbsp;</a>
		<div id="main">
			<div class="blackboard">
				<form id="login" action="./login.php" method="post" onsubmit="return check2();">
					<table>
						<tr class="indexMarks"><td>学号：</td><td><input type="text" id="studentNumber" name="studentNumber" /></td></tr>
						<tr class="indexMarks"><td>密码：</td><td><input type="password" id="password" name="password" /></td></tr>
						<tr><td></td><td><a class="indexLink" href="http://<?php echo $_SERVER['HTTP_HOST'].'/taolihupan';?>/login.php?forget=password" tabindex="-1">忘了密码？</a></td></tr>
						<tr><td></td><td><input type="checkbox" name="autologin" id="autologin" value="yes" /><label for="autologin" >记住我</label></td></tr>
						<tr><td></td><td><input type="submit" value="登录" id="go" /></td></tr>
						<tr><td></td><td><a id="indexTips" href="#1" onclick="scrollGoTo(1,1);return false;">首次登录前请先注册&gt;&gt;</a></td></tr>
						<tr><td></td><td><a class="indexLink" href="http://<?php echo $_SERVER['HTTP_HOST'].'/taolihupan';?>/newcomer.php">未入学新生通道&gt;&gt;</a></td></tr>
						<tr><td></td><td><a class="indexLink" href="#3" onclick="scrollGoTo(1878,0);return false;">关于我们&gt;&gt;</a> & <a class="indexLink" href="mailto:admin@taolihupan.com">联系我们&gt;&gt;</a></td></tr>
					</table>
					<input type="hidden" name="submitted" value="true" />
				</form>
			</div>
		</div>
		
		<a id="3">&nbsp;</a>
		<div id="about">
			<div class="blackboard">
				<p id="join">我们和你一样<br />
				每天往返于东区与本部<br />
				我们和你一样<br />
				每天赶场于主楼与研楼<br />
				也许我们前天还曾擦肩<br />
				昨天还在同一教室自习<br />
				<br />
				无论你是什么专业<br />
				无论你是什么年级<br />
				只要你有漂亮的想法<br />
				或者总为网页而着迷<br />
				欢迎你加入我们的团队<br />
				让我们一同来实现创意<br />
				<br />
				<a class="indexLink" href="#2" onclick="scrollGoTo(930,1);return false;" >返回登录页面&gt;&gt;</a>
				</p>
			</div>
		</div>
		
	</div>
	
	<div id="cover">
	</div>
	
	<div id="dialog">
		<div id="error">
		</div>
		<button onclick="javascript:hideDialog();" id="close">确定</button>
	</div>
	
	<script type="text/javascript">
	/*<![CDATA[*/
		window.onload = function() {
			window.scrollTo(0, 930);
		}
		
		function scrollGoTo(targetY, dir) {
			if (document.documentElement.scrollTop)
				var nowY = document.documentElement.scrollTop;
			if (document.body.scrollTop)
				var nowY = document.body.scrollTop;
			var deta = Math.abs(targetY - nowY);
			if(dir == 1) {	//up
				window.scrollTo(0, nowY - deta/20);
				if(deta > 0) {
					window.setTimeout("scrollGoTo(" + targetY + "," + dir + ")", "15");
				}
			} else {	//down
				window.scrollTo(0, nowY + deta/20);
				if(deta > 20) {
					window.setTimeout("scrollGoTo(" + targetY + "," + dir + ")", "15");
				}
			}
		}
		
		function getWidth() {
			return document.documentElement.clientWidth > document.body.clientWidth ? document.documentElement.clientWidth : document.body.clientWidth;
		}
		
		function getHeight() {
			return document.documentElement.clientHeight > document.body.clientHeight ? document.documentElement.clientHeight : document.body.clientHeight;
		}
		
		function showDialog() {
			var cover = document.getElementById("cover");
			cover.style.width = getWidth() + "px";
			cover.style.height = getHeight() + "px";
			cover.style.display = "block";
			var dialog = document.getElementById("dialog");
			dialog.style.left = getWidth()/2 - 120 + "px";
			dialog.style.top = 200 + "px";	//screen.height获取屏幕的高度
			dialog.style.display = "block";
		}
		
		function hideDialog() {
			var error = document.getElementById("error");
			while (error.firstChild) {
				var oldNode = error.removeChild(error.firstChild);
				oldNode = null;
			}
			document.getElementById("dialog").style.display = "none";
			document.getElementById("cover").style.display = "none";
		}
		
		function check() {	//表单各项检测
		
			var flag = true;
			var error = document.getElementById("error");
			
			if(!document.getElementById("regStudentNumber").value) {	//学号为空
				var errorItem1 = document.createElement("p");
				errorItem1.appendChild(document.createTextNode("请填写学号"));
				error.appendChild(errorItem1);
				flag = false;
			} else {	//学号格式错误
				var reg = /^\d{8}$/;
				if(!reg.test(document.getElementById("regStudentNumber").value)){
					var errorItem1 = document.createElement("p");
					errorItem1.appendChild(document.createTextNode("学号格式错误"));
					error.appendChild(errorItem1);
					flag = false;
				}
			}
			
			if(!document.getElementById("regEmail").value) {	//邮箱为空
				var errorItem2 = document.createElement("p");
				errorItem2.appendChild(document.createTextNode("请填写邮箱"));
				error.appendChild(errorItem2);
				flag = false;
			} else {	//邮箱格式错误
				var reg = /^([a-zA-Z0-9_\-\.])+@([a-zA-Z0-9_\-])+(\.[a-zA-Z0-9_\-])+/;
				if(!reg.test(document.getElementById("regEmail").value)){
					var errorItem2 = document.createElement("p");
					errorItem2.appendChild(document.createTextNode("邮箱格式错误"));
					error.appendChild(errorItem2);
					flag = false;
				}
			}
			
			if(!document.getElementById("regPassword").value) {	//密码为空
				var errorItem3 = document.createElement("p");
				errorItem3.appendChild(document.createTextNode("请填写密码"));
				error.appendChild(errorItem3);
				flag = false;
			}
			
			if(!document.getElementById("agreement").checked) {	//未同意协议
				var errorItem4 = document.createElement("p");
				errorItem4.appendChild(document.createTextNode("请阅读并同意使用协议"));
				error.appendChild(errorItem4);
				flag = false;
			}
			
			if (flag) {
				return true;
			} else {
				showDialog();
				return false;
			}
		}
		
		function check2() {
		
			var flag = true;
			var error = document.getElementById("error");
			if(!document.getElementById("studentNumber").value) {	//学号为空
				var errorItem1 = document.createElement("p");
				errorItem1.appendChild(document.createTextNode("请填写学号"));
				error.appendChild(errorItem1);
				flag = false;
			}
			else {	//学号格式错误
				var reg = /^\d{8}$/;
				if(!reg.test(document.getElementById("studentNumber").value)){
					var errorItem1 = document.createElement("p");
					errorItem1.appendChild(document.createTextNode("学号格式错误"));
					error.appendChild(errorItem1);
					flag = false;
				}
			}
			
			if(!document.getElementById("password").value) {	//密码为空
				var errorItem3 = document.createElement("p");
				errorItem3.appendChild(document.createTextNode("请填写密码"));
				error.appendChild(errorItem3);
				flag = false;
			}
			
			if (flag) {
				return true;
			} else {
				showDialog();
				return false;
			}
		}
	/*]]>*/
	</script>

</body>
</html>