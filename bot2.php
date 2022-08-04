<?php
function Curl($u, $h = 0, $p = 0) {
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $u);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
	curl_setopt($ch, CURLOPT_COOKIE,TRUE);
	curl_setopt($ch, CURLOPT_COOKIEFILE,"cookie.txt");
	curl_setopt($ch, CURLOPT_COOKIEJAR,"cookie.txt");
	if($p) {
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $p);
	}
	if($h) {
		curl_setopt($ch, CURLOPT_HTTPHEADER, $h);
	}
	curl_setopt($ch, CURLOPT_HEADER, true);
	$r = curl_exec($ch);
	$c = curl_getinfo($ch);
	if(!$c) return "Curl Error : ".curl_error($ch); else{
		$hd = substr($r, 0, curl_getinfo($ch, CURLINFO_HEADER_SIZE));
		$bd = substr($r, curl_getinfo($ch, CURLINFO_HEADER_SIZE));
		curl_close($ch);
		return array($hd,$bd)[1];
	}
}
