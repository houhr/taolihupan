<?php

class ipv6 {
	
	/*
	* return user IP
	*
	* @return IP
	*/
	function get_ip() {
		
		return $_SERVER['REMOTE_ADDR'];
		
	}
	
	/*
	* Detect if an IP is IPv6
	*
	* @param ip adresse
	* @return true / false
	*/
	function is_ipv6($ip = "") {
		
		if ($ip == "") {
			$ip = ipv6::get_ip();
		}
		
		if (substr_count($ip, ":") > 0 && substr_count($ip, ".") == 0) {
			return true;
		} else {
			return false;
		}
		
	}
	
	/*
	* Detect if an IP is IPv4
	*
	* @param ip adresse
	* @return true / false
	*/
	function is_ipv4($ip = "") {
		
		return !ipv6::is_ipv6($ip);
		
	}
	
	/*
	* Compress an IPv6 address
	*
	* @param ip adresse IP
	* @return ip adresse IP IPv6 compress
	*/
	function compress_ipv6($ip ="") {
		
		if ($ip == "") {
			$ip = ipv6::get_ip();
		}
		
		if(!strstr($ip,"::" )) {
			$e = explode(":", $ip);
			$zeros = array(0);
			$result = array_intersect($e, $zeros );
			
			if (sizeof($result) >= 6) {
				if ($e[0] == 0) {
					$newip[] = "";
				}
				
				foreach($e as $key=>$val) {
					if ($val !== "0") {
						$newip[] = $val;
					}
				}
				$ip = implode("::", $newip);
			}
		}
		
		return $ip;
		
	}
	
	/*
	* Uncompress an IPv6 address
	*
	* @param ip adresse IP IPv6 uncompresser
	* @return ip adresse IP IPv6 uncompress
	*/
	function uncompress_ipv6($ip ="") {
		
		if ($ip == "") {
			$ip = ipv6::get_ip();
		}
		
		if(strstr($ip, "::")) {
			$e = explode(":", $ip);
			$s = 8 - sizeof($e) + 1;
			foreach($e as $key=>$val) {
				if ($val == "") {
					for($i=0;$i<=$s;$i++)
						$newip[] = 0;
				} else {
					$newip[] = $val;
				}
			}
			$ip = implode(":", $newip);
		}
		
		return $ip;
		
	}

}

/*
*  IPv6 Compression
*/
//echo " IPv6 compression : ".ipv6::compress_ipv6("0:0:0:0:0:0:0:1");
//echo '<br />';
/*
* IPv6 Uncompression
*/
//echo "IPv6 Uncompression : ".ipv6::uncompress_ipv6("::1");
//echo '<br />';
/*
* Tester IPv6
*/
//echo "Your IP is ".ipv6::get_ip()." et You're using : ";
//echo (ipv6::is_ipv6())? "IPv6":"IPv4";

?>