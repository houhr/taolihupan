window.onload = function() {
	window.scrollTo(0, 705);
	document.getElementById("studentNumber").focus();
	clear();
	moveCloud();
}

function clear() {	//清除提示		
	document.getElementById("noNumber").style.display = "none";
	document.getElementById("wrongNumber").style.display = "none";
	document.getElementById("noEmail").style.display = "none";
	document.getElementById("wrongEmail").style.display = "none";
	document.getElementById("noPassword").style.display = "none";
	document.getElementById("noAgree").style.display = "none";
	document.getElementById("noNumber2").style.display = "none";
	document.getElementById("wrongNumber2").style.display = "none";
	document.getElementById("noPassword2").style.display = "none";
}

function check() {	//表单各项检测
	clear();
	var flag = true;
	
	var checkStudentNumber = document.getElementById("regStudentNumber");
	var checkEmail = document.getElementById("regEmail");
	var checkPassword = document.getElementById("regPassword");
	var checkAgree = document.getElementById("agreement");
	
	if(!checkStudentNumber.value) {	//学号为空
		checkStudentNumber.style.border = "1px red solid";
		document.getElementById("noNumber").style.display = "inline";
		flag = false;
	}
	else {	//学号格式错误
		var reg = /^\d{8}$/;
		if(!reg.test(checkStudentNumber.value)){
			checkStudentNumber.style.border = "1px red solid";
			document.getElementById("wrongNumber").style.display = "inline";
			flag = false;
		}
	}
	
	if(!checkEmail.value) {	//邮箱为空
		checkEmail.style.border = "1px red solid";
		document.getElementById("noEmail").style.display = "inline";
		flag = false;
	}
	else {	//邮箱格式错误
		var reg = /^([a-zA-Z0-9_\-\.])+@([a-zA-Z0-9_\-])+(\.[a-zA-Z0-9_\-])+/;
		if(!reg.test(checkEmail.value)){
			checkEmail.style.border = "1px red solid";
			document.getElementById("wrongEmail").style.display = "inline";
			flag = false;
		}
	}
	
	if(!checkPassword.value) {	//密码为空
		checkPassword.style.border = "1px red solid";
		document.getElementById("noPassword").style.display = "inline";
		flag = false;
	}
	
	if(!checkAgree.checked) {	//未同意协议
		checkAgree.style.border = "1px red solid";
		document.getElementById("noAgree").style.display = "inline";
		flag = false;
	}
	
	return flag;
}

function check2() {
	clear();
	var flag = true;
	var checkStudentNumber = document.getElementById("studentNumber");
	var checkPassword = document.getElementById("password");
	
	if(!checkStudentNumber.value) {	//学号为空
		checkStudentNumber.style.border = "1px red solid";
		document.getElementById("noNumber2").style.display = "inline";
		flag = false;
	}
	else {	//学号格式错误
		var reg = /^\d{8}$/;
		if(!reg.test(checkStudentNumber.value)){
			checkStudentNumber.style.border = "1px red solid";
			document.getElementById("wrongNumber2").style.display = "inline";
			flag = false;
		}
	}
	
	if(!checkPassword.value) {	//密码为空
		checkPassword.style.border = "1px red solid";
		document.getElementById("noPassword2").style.display = "inline";
		flag = false;
	}
	
	return flag;
}

function scrollGoTo(targetY, dir) {
	var nowY = document.documentElement.scrollTop;
	var deta = Math.abs(targetY - nowY);
	if(dir==1) {	//up
		window.scrollTo(0, nowY - deta/20);
		if(deta>0) {
			window.setTimeout("scrollGoTo(" + targetY + "," + dir + ")", "15");
		}
	} else {	//down
		window.scrollTo(0, nowY + deta/20);
		if(deta>20) {
			window.setTimeout("scrollGoTo(" + targetY + "," + dir + ")", "15");
		}
	}
}

function moveCloud() {
	var cloud = document.getElementById("cloud");
	var y = cloud.offsetLeft;
	if(y<document.body.clientWidth) {
		y+=1;
	} else {
		y=-390;
	}
	cloud.style.left = y+"px";
	window.setTimeout("moveCloud()", 50);
}

function moveSnail() {
	var snail = document.getElementById("snail");
	var y = snail.offsetLeft;
	if(y+680<document.body.clientWidth) {
		y+=1;
	}
	snail.style.left = y+"px";
	window.setTimeout("moveSnail()", 100);
}