<?php 
function gotoUrl($url){
	echo '<script language="javascript">';
	echo 'location="'.$url.'";';
	echo '</script>';
}

function toAbsAdd($base,$url){
	$arr_base=explode('/',$base);
	$mins=strlen(BASE_PATH);
	$re='http:/';
	for($i=0; $i<(count($arr_base)-$mins); $i++){
		$re .= '/'.$arr_base[$i] ;
	}
	$re .= $url;
	return $re;
}

function formatOutput(&$str,$onlyText=false){
	$re='';
	while(strlen($str)>0){
		$t = strpos($str,'[[');
		$l = strpos($str,']]');
		if(($t!==false)&&(($l===false)||($l!==false&&$t<$l))){
			if($onlyText)
				$re.=substr($str,0,$t);
			else
				echo substr($str,0,$t);
			$str=substr($str,$t);
			formatMeta($str,$onlyText);
		}else if($l!==false){
			if($onlyText)
				$re.=substr($str,0,$l);
			else
				echo substr($str,0,$l);
			$str=substr($str,$l+2);
			return $re;		
		}else{
			if($onlyText)
				$re.=$str;
			else
				echo $str."\n";
			$str='';
		}
	}
	return $re;
}

function formatMeta(&$str,$onlyText=false){
	$t=strpos($str,'@');
	$ty=trim(substr($str,2,$t-2));
	$str=substr($str,$t+1);
	switch($ty){
	case 'map':
		formatMap($str,$onlyText);
		break;
	case 'image':
		formatImage($str,$onlyText);
		break;
	case 'title':
		formatTitle($str,$onlyText);
		break;
	case 'link':
		formatLink($str,$onlyText);
		break;
	}
}


function formatImage(&$str,$onlyText=false){
	if(!$onlyText)
		echo '<p align="center"><img src="';
	$l = strpos($str,']]');
	if(!$onlyText){
		echo trim(substr($str,0,$l));
		echo '" width="100%"/></p>'."\n";
	}
	$str=substr($str,$l+2);
}

function formatLink(&$str,$onlyText=false){
	if(!$onlyText)
		echo '<a href="';
	$l = strpos($str,'&#;');
	if(!$onlyText)
		echo trim(substr($str,0,$l));
	$str=substr($str,$l+3);
	if(!$onlyText)
		echo '">';
	formatOutput($str,$onlyText);
	if(!$onlyText)
		echo '</a>'."\n";
}

function formatMap(&$str,$onlyText=false){
	$l = strpos($str,'&#;');
	$ty=trim(substr($str,0,$l));
	$str=substr($str,$l+3);
	$r = rand(0,1000);
	switch($ty){
	case 'Gmap':
		if(!$onlyText){
			echo '<p align="center">';
			echo '<div class="map_self">'."\n";
			echo '<div id="gmap'.$r.'" name="gmap'.$r.'" style="width:100%;height:100%;"></div>'."\n";
			echo '<script type="text/javascript">';
		}
		$l = strpos($str,'&#;');
		$ty=trim(substr($str,0,$l));
		$str=substr($str,$l+3);
		if(!$onlyText){
			echo 'var myLatlng = new google.maps.LatLng'.$ty.";\n";
			echo 'var myOptions = {'."\n".'mapTypeId: google.maps.MapTypeId.';
		}
		$l = strpos($str,'&#;');
		$ty=trim(substr($str,0,$l));
		$str=substr($str,$l+3);
		if(!$onlyText)
			echo $ty.",\nzoom: ";
		$l = strpos($str,'&#;');
		$ty=trim(substr($str,0,$l));
		$str=substr($str,$l+3);
		if(!$onlyText){
			echo $ty.',center: myLatlng,mapTypeControlOptions:'."\n";
			echo '{style:google.maps.MapTypeControlStyle.DROPDOWN_MENU}};'."\n";
			echo 'map'.$r.' = new google.maps.Map(document.getElementById("gmap'.$r;
			echo '"), myOptions);'."\n";
		}
		$l = strpos($str,']]');
		$ty=trim(substr($str,0,$l));
		$str=substr($str,$l+2);
		$re = preg_match_all('/\(\s*(\([^)]+\))\s*&#;\s*([^)]*)\s*\)/',
			$ty, $out, PREG_SET_ORDER);
		if($re>0){
			foreach($out as $mk){
				if(!$onlyText){
					echo 'new google.maps.Marker({ position: new google.maps.LatLng'."\n";
					echo $mk[1].',map: map'.$r.',title:"'.$mk[2].'"});'."\n";
				}
			}
		}
		if(!$onlyText)
			echo '</script></div></p>';
		break;
	case 'NdMapM':
		if(!$onlyText){
			echo '<p align="center">';
			echo '<div class="map_self">'."\n";
			echo '<div id="nm'.$r.'_dg" name="nm'.$r.'_dg" ';
			echo 'class="ndmapm" ondragstart="return false" ';
		}
		$l = strpos($str,'&#;');
		$ty=trim(substr($str,0,$l));
		$str=substr($str,$l+3);
		preg_match ('/\(\s*(-?[0-9]+)\s*,\s*(-?[0-9]+)\s*\)/',$ty,$loc);
		if(!$onlyText){
			echo 'style="left:'.$loc[1].'px; top:'.$loc[2].'px;" ';
			echo 'onmouseover="xb=-200; yb=-222;">'."\n";
			echo '<div class="navm">'."\n";
		}
		$l = strpos($str,']]');
		$ty=trim(substr($str,0,$l));
		$str=substr($str,$l+2);
		$re = preg_match_all('/\(\s*\(\s*(-?[0-9]+)\s*,\s*(-?[0-9]+)\s*\)\s*&#;\s*([^)]*)\s*\)/',
			$ty, $out, PREG_SET_ORDER);
		if($re>0){
			foreach($out as $mk){
				if(!$onlyText){
					echo '<img src="';
					echo toAbsAdd($_SERVER['HTTP_HOST'].'/taolihupan'.$_SERVER['PHP_SELF']
						,'/images/marker.png');
					echo '" title="'.$mk[3].'" style="top:'.$mk[2].'px;left:';
					echo $mk[1].'px;position:absolute;"></img>'."\n";
				}
			}
		}
		if(!$onlyText)
			echo "</div>\n</div>\n</div>\n</p>";
		break;
	}
}

function numToCookie($num){
	$mm_key='tlhpwebs';
	$arr_s=str_split($num);
	$arr_k=str_split($mm_key);
	$re='';
	for($i=0;$i<8;$i++){
		$s=ord($arr_s[$i]);
		$k=ord($arr_k[$i]);
		$re .= chr( $s ^ $k);
	}
	return $re;
}

function dump($s){
	echo $s.'<br/>';
}

function substr_cut($string,$length = 200){    
	$start=0;
	$add=true;
	$wordscut='';
	if(ord($string[$start]) > 127)
		$start--;
	if($start<0) $start=0;
	if(strlen($string) <= $length) {
		return $string;
	}
	else {
		if($add){
			$length=$length - 3;
			$addstr="...";  
		}  
	}
	for($i =$start; $i < $length; $i++) {
		if(ord($string[$i]) > 127) {
			$wordscut .= $string[$i].$string[$i + 1];
			$i++;
		}
		else {
			$wordscut.= $string[$i];
		}
	}
	return $wordscut.$addstr;  
}

?>