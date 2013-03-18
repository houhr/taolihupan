window.onload = function() {
	if (document.getElementById("message")) {
		yellow(0);
	}
}

function clearInput() {
	var e=document.getElementById('search_word');
	e.value="";
}
function restore() {
	var e=document.getElementById('search_word');
	e.value="找同学、搜文章";
}

/*-------------editor--------------*/
var rightUrlForImage=false
function imageSubmit(){
	if(!rightUrlForImage)
		previewImage(document.getElementById('imageAddress').value);
	else
		addImage(document.getElementById('imageAddress').value);
}

function addImage(add){
	var s='[[image@'+add+']]';
	addText(s);
	closeWindow('image',true);
	document.getElementById('view_image').src='';
}

function previewImage(url){
	rightUrlForImage=true;
	var img=document.getElementById('view_image');
	img.src=url;
}

function linkSubmit(){
	addLink(document.getElementById('linkAddress').value);
}

function addLink(add){
	var title=getSelection();
	var s='[[link@'+add+'&#; '+((title!=null&&title.length>0)?title:add)+']]';
	addText(s);
	closeWindow('link',true);
}

function addTitle(){
	var title=getSelection();
	if(title==null||title.length<=0){
		return;
	}
	var s='[[title@'+title+']]';
	addText(s);
}

function openNewWindow(id){
	document.getElementById(id).style.display='block';
	document.getElementById('fade').style.display='block';
	document.getElementById('fade').style.top= getScrollTop()+'px';
}

function getScrollTop(){
	if (document.documentElement && document.documentElement.scrollTop) {
		return document.documentElement.scrollTop;
	} else if (document.body) {
		return document.body.scrollTop;
	}
	return 0;
}

function closeWindow(id,father){
	var win=document.getElementById(id);
	win.style.display='none'; 
	if(father){
		document.getElementById('fade').style.display='none';
	}
	var texts=win.getElementsByTagName('input');
	for (var i in texts){
		if(texts[i].type=='text'){
			texts[i].value='';
		}
	}
}

function stopBubble(e) {  
	var e = e ? e : window.event;  
	if (window.event) { // IE  
		e.cancelBubble = true;   
	} else { // FF  
		e.preventDefault();     
	}   
}  

var speed=10 ;

function marqueeLeft(){
	var faceBar=document.getElementById('smilyBar');
	var faces=document.getElementById('smilys');
	if(faceBar.scrollLeft<faces.scrollWidth)
		faceBar.scrollLeft++;
}

function marqueeRight(){
	var faceBar=document.getElementById('smilyBar');
	if(faceBar.scrollLeft>0)
		faceBar.scrollLeft--;
} 

function clearTimer(){
	if(timer!=null)
		clearInterval(timer);
} 

function setTimer(dir) {
	timer=setInterval(dir?marqueeLeft:marqueeRight,speed);
} 

function addText(txt) {  
	obj = document.getElementById('editorArea');  
	selection = document.selection;  
	checkFocus();  
	if(typeof(obj.selectionStart)!='undefined') {  
		obj.value = obj.value.substr(0, obj.selectionStart) 
			+ txt + obj.value.substr(obj.selectionEnd);  
	} else if(selection && selection.createRange) {  
		var sel = selection.createRange();  
		sel.text = txt;  
		sel.moveStart('character', -txt.length);  
	} else {  
		obj.value += txt;  
	}
}

function getSelection(){
	obj = document.getElementById('editorArea');  
	selection = document.selection;  
	checkFocus();  
	if(typeof(obj.selectionStart)!='undefined') {
		s=obj.value;
		v=0;
		t=0;
		for(i=0;i<obj.selectionStart;i++){
			if(s[i]=='['&&s[i+1]=='['){
				t=i;
				v++;
				i++;
				continue;
			}
			if(s[i]==']'&&s[i+1]==']'){
				v--;
				i++;
				continue;
			}
		}
		if(v==1){
			obj.selectionStart=t;
			v=0;
			for(i=obj.selectionEnd;i<s.length;i++){
				if(s[i]=='['&&s[i+1]=='['){
					v++;
					i++;
					continue;
				}
				if(s[i]==']'&&s[i+1]==']'){
					t=i+2;
					v--;
					i++;
					continue;
				}
			}
			if(v==-1){
				obj.selectionEnd=t;
			}
		}
		return obj.value.substr(obj.selectionStart,obj.selectionEnd-obj.selectionStart);  
	} else if(selection && selection.createRange) {
		//var sel = selection.createRange();  
		//sel.text = txt;  
		//sel.moveStart('character', -txt.length);  
	} else {
		return null;  
	}
}

function checkFocus() {  
	obj.focus();  
} 

function startUpload(){
	document.getElementById('upload_process').style.display = 'block';
	document.getElementById('upload_form').style.display = 'none';
	return true;
}

function stopUpload(success,name){
	if (success == 1){
		document.getElementById('imageAddress').value=name;
	}
    return true; 
}

/*------------drag-element----------------------------*/
var ie=document.all; 
var nn6=document.getElementById&&!document.all;
var isdrag=false;
var y,x;
var oDragObj;
var xb,yb;

function moveMouse(e) {
	if (isdrag) {
		var ty=(nn6 ? nTY + e.clientY - y : nTY + event.clientY - y);
		var tx=(nn6 ? nTX + e.clientX - x : nTX + event.clientX - x);
		tx=tx<xb?xb:tx;
		tx=tx>0?0:tx;
		ty=ty<yb?yb:ty;
		ty=ty>0?0:ty;
		oDragObj.style.left = tx + "px";
		oDragObj.style.top = ty + "px";
	}	
}

function initDrag(e) {
	var oDragHandle = nn6 ? e.target : event.srcElement;
	var topElement = "HTML";
	while (oDragHandle.tagName != topElement && (oDragHandle.id+'').indexOf('_dg')==-1) {
		oDragHandle = nn6 ? oDragHandle.parentNode : oDragHandle.parentElement;
	}
	
	if ((oDragHandle.id+'').indexOf('_dg')!=-1) {
		isdrag = true;
		oDragObj = oDragHandle;
		nTY = parseInt(oDragObj.style.top+0);
		y = nn6 ? e.clientY : event.clientY;
		nTX = parseInt(oDragObj.style.left+0);
		x = nn6 ? e.clientX : event.clientX;
		document.onmousemove=moveMouse;
	}
}
document.onmousedown=initDrag;
document.onmouseup=new Function("isdrag=false"); 
/*--------------------------------------------*/
var maptag=false;
var markers=new Array();
var markerNum=0;
var map;
var mapType;
function addTag(){
	maptag=true;
}
function clearAll(){
	document.getElementById('gmap_bnt').className='';
	document.getElementById('ndmapm_bnt').className='';
	document.getElementById('gmap').style.display='none';
	document.getElementById('ndmapm_dg').style.display='none';
	document.getElementById("map_msg").value='';
}
function selectGmap(){
	mapType='Gmap';
	clearAll();
	document.getElementById('gmap_bnt').className='onShow';
	document.getElementById('gmap').style.display='block';
	initMap();
	initGmap();
}
function selectNdmapm(){
	mapType='NdMapM';
	clearAll();
	document.getElementById('ndmapm_bnt').className='onShow';
	document.getElementById('ndmapm_dg').style.display='block';
	initMap();
	xb=-200;
	yb=-222;
	document.getElementById('ndmapm_dg').ondblclick='alert("clk")';
}
function initMap(){
	maptag=false;
	markers=new Array();
	markerNum=0;
}
function initGmap(){
	var myLatlng = new google.maps.LatLng(40.812195,111.690652);
	var myOptions = {
	zoom: 12,
	center: myLatlng,
	mapTypeId: google.maps.MapTypeId.ROADMAP,
	mapTypeControlOptions:{style:google.maps.MapTypeControlStyle.DROPDOWN_MENU}
	};
	map = new google.maps.Map(document.getElementById("gmap"), myOptions);
	google.maps.event.addListener(map, 'click', function(event) {
		placeGmapMarker(event.latLng);
	});
}
function placeGmapMarker(location) {
	if(!maptag)
		return;
	var tagTitle=document.getElementById("map_msg").value;
	var clickedLocation = new google.maps.LatLng(location);
	var marker = new google.maps.Marker({
		position: location, 
		map: map,
		title:tagTitle
	});
	maptag=false;
	document.getElementById("map_msg").value='';
	markers[markerNum++]=marker;
}
function placeNdmapMarker(location) {
	if(!maptag)
		return;
	if(getBrowser()=='Firefox'){
		var locX=location.layerX-10;
		var locY=location.layerY-32;
	}else{
		var locX=location.offsetX-10;
		var locY=location.offsetY-32;
	}
	var tagTitle=document.getElementById("map_msg").value;
	var mapDiv=document.getElementById("ndmapm_nav");
	var markElem=document.getElementById("markerHome").value;
	markElem=markElem.replace('%x%',locX);
	markElem=markElem.replace('%y%',locY);
	markElem=markElem.replace('%title%',tagTitle);
	mapDiv.innerHTML=mapDiv.innerHTML+markElem;
	maptag=false;
	document.getElementById("map_msg").value='';
	markers[markerNum++]=new NdMapMark(locX,locY,tagTitle);
}

function addMap(){
	switch (mapType){
	case 'Gmap':
		mapInterface='[[map@Gmap&#;';
		mapInterface+=map.getCenter().toString();
		mapInterface+='&#; ';
		mapInterface+=map.getMapTypeId().toUpperCase();
		mapInterface+='&#; ';
		mapInterface+=map.getZoom();
		mapInterface+='&#; ';
		if(markers.length>0){
			markTable='';
			for( m in markers){
				markTable+='(';
				markTable+=markers[m].getPosition().toString();
				markTable+='&#; ';
				markTable+=markers[m].getTitle();
				markTable+=') ';
			}
			mapInterface+=markTable;
		}
		mapInterface+=']]';
		addText(mapInterface);
		break;
	case 'NdMapM':
		mapInterface='[[map@NdMapM&#;';
		mapInterface+='(';
		mapInterface+=parseInt(oDragObj.style.left+0);
		mapInterface+=','+parseInt(oDragObj.style.top+0);
		mapInterface+=')&#; ';
		if(markers.length>0){
			markTable='';
			for( m in markers){
				markTable+='(';
				markTable+=markers[m].location;
				markTable+='&#; ';
				markTable+=markers[m].title;
				markTable+=') ';
			}
			mapInterface+=markTable;
		}
		mapInterface+=']]';
		addText(mapInterface);
		break;
	}
	closeWindow('add_maps',true);
}

function NdMapMark(x,y,title){
	  this.location='('+x+','+y+')';
	  this.title=title;
}

function getBrowser(){
	var Sys = {};
	var ua = navigator.userAgent.toLowerCase();
	var s;
	(s = ua.match(/msie ([\d.]+)/)) ? Sys.ie = s[1] :
	(s = ua.match(/firefox\/([\d.]+)/)) ? Sys.firefox = s[1] :
	(s = ua.match(/chrome\/([\d.]+)/)) ? Sys.chrome = s[1] :
	(s = ua.match(/opera.([\d.]+)/)) ? Sys.opera = s[1] :
	(s = ua.match(/version\/([\d.]+).*safari/)) ? Sys.safari = s[1] : 0;

	//以下进行测试
	if (Sys.ie) return 'IE';
	if (Sys.firefox) return 'Firefox';
	if (Sys.chrome) return 'Chrome';
	if (Sys.opera) return 'Opera';
	if (Sys.safari) return 'Safari';
}

function dump(str){
	alert(str);
}

/*---------------------message------------------------*/
	
function yellow(val) {
	
	if (val <=255 ) {
	
		var target = document.getElementById("message");
		var color = "rgb(255, 255, "+val+")";
		target.style.backgroundColor = color;
		val += 15;
		window.setTimeout("yellow("+val+")", "200");
		
	}
}

/*---------------------dropMenu-----------------------*/

function displaySubMenu(li) { 
	var subMenu = li.getElementsByTagName("ul")[0]; 
	subMenu.style.display = "block"; 
}

function hideSubMenu(li) { 
	var subMenu = li.getElementsByTagName("ul")[0]; 
	subMenu.style.display = "none"; 
}