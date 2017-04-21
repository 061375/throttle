<?php
/** 
 * Super Basic Throttle
 * 
 * @author Jeremy Heminger http://jeremyheminger.com
 * @version 1.0.2
 * 
 * the idea here is that if placed before everything in the code this will stop a simple attack, 
 * malicious or poorly built web-crawler.
 * This script is mostly designed to reduce resource waste that comes from a full hit to the website and database.
 * This is not designed with a DDOS in mind, but might provide SOME relief in that case.
 * 
 * @param int $max the maximum hits before a throttle will occur
 * 
 * @param string $rtime the release time ( use strtotime ) http://php.net/manual/en/function.strtotime.php
 * 
 * @param string $message an error message if the threshold is met (defaults to '' because ... f*ck the haters)
 * 
 * @param string $file path to a file
 * 
 * @return void
 * */
function throttle($max = 5, $rtime = '-5 minutes', $message = '', $file = '.ips')
{
    // get IP address 
	$ip = $_SERVER['REMOTE_ADDR'];
	if(empty($ip))die(); // if no IP then something is wrong...no message for you

	// get previously stored IP addresses
	if(file_exists($file)) {

		$ips = file_get_contents($file);
		$ips = json_decode($ips);
	}else{

		// no data, then create empty object
		$ips = new stdClass();
	}

	// if ip is alreay in system
	if(isset($ips->$ip->count)) {
		// if its been $rtime
		if((int)$ips->$ip->ts < strtotime($rtime)) {

			// delete
			unset($ips->$ip);	
		}else{

			// otherwise if its over $max hits
			if($ips->$ip->count >= $max) {

				// die with message
				header("HTTP/1.0 429 Too Many Requests");
				die($message);	
			}else{

				// else increment
				$ips->$ip->count++;
				$ips->$ip->ts = strtotime('now');
			}
		}
	}else{

		// set first hit
		$ips->$ip->count = 1;
		$ips->$ip->ts = strtotime('now');
	}
	// record what happened
	file_put_contents($file,json_encode($ips));
}
