<?php
/*
Full tutorial : https://youtu.be/60pMCTSfKpg
Bahasa : Indonesia
*/

error_reporting(0);
const
b = "\033[1;34m",
c = "\033[1;36m",
d = "\033[0m",
h = "\033[1;32m",
k = "\033[1;33m",
m = "\033[1;31m",
p = "\033[1;37m",
u = "\033[1;35m";

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

$line = b.str_repeat('~',50)."\n";

$header[] = "Host: tronhash.io";
$header[] = "user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/103.0.0.0 Safari/537.36";

//$wallet = "D5pt4wELzzQRCeP1691dtpBqCsYWurD6Mu";
$password = "123456";

$wall = file("wallet");

while(true){
	foreach($wall as $wallet){
		$data = "username=".trim($wallet)."&password=".$password."&reference_user_id=";
		$login = curl("https://tronhash.io/ajax_auth",$header,$data);
		if($login=="success"){
		}else{
			exit(m."Login gagal");
		}

		$dashboard = Curl("https://tronhash.io/dashboard",$header);
		//<font id="bal">0.00050692</font>
		$balance = explode('</font>',explode('<font id="bal">',$dashboard)[1])[0];
		$wallet = explode("'",explode("#show_address').html('",$dashboard)[1])[0];
		print h."balance".m.":".p." $balance TRX\n";
		print h."wallet".m." :".p." $wallet\n";

		$withdraw = Curl("https://tronhash.io/withdrawal",$header);
		//5.00000000 TRX</div>
		$minimal = explode(' ',explode('<div class="alert alert-success"><b>Min:</b> ',$withdraw)[1])[0];

		if($balance >= $minimal){
			$data = "amount=".$balance;
			$withdraw = Curl("https://tronhash.io/withdrawal",$header,$data);
			//<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>You don't have enough earning blance to withdrawal the given amount!</div>
			$alert = explode('</div>',explode('<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>',$withdraw)[1])[0];
			($alert)? print m."withdraw gagal\n":print h."withdraw sukses\n";

			//if($alert){
			//	print "withdraw gagal\n";
			//}else{
			//	print "withdraw sukses\n";
			//}
		}
		sleep(5);
		print $line;
	}
	print k."MULAI DARI AWAL LAGI\n";
	print $line;
	sleep(5);
}

