function getXMLHttp() {

	var xmlhttp = false;

	try {
		xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
	} catch (e) {
		try {
			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		} catch (E) {
			xmlhttp = false;
		}
	}
	if (!xmlhttp && typeof XMLHttpRequest != 'undefined') {
		xmlhttp = new XMLHttpRequest();
	}
	
	return xmlhttp;
}

function processAjax(serverPage, obj, getOrPost, str) {

	var xmlhttp = getXMLHttp();
	
	if(getOrPost = "get") {
	
		xmlhttp.open("GET", serverPage);
		xmlhttp.onreadystatechange = function () {
			if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
				obj.innerHTML = xmlhttp.responseText;
			}
		}
		xmlhttp.send(null);
		
	} else {
	
		xmlhttp.open("POST", serverPage, true);
		xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded; charset=UTF-8");
		xmlhttp.onreadystatechange = function () {
			if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
				obj.innerHTML = xmlhttp.responseText;
			}
		}
		xmlhttp.send(str);
		
	}
}