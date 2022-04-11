<?php
error_reporting(0);
system('clear');
//CLASS MODUL
function Run($url, $head = 0, $post = 0){
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
	curl_setopt($ch, CURLOPT_COOKIE,TRUE);
	if($post){
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
	}
	if($head){
		curl_setopt($ch, CURLOPT_HTTPHEADER, $head);
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

$line = str_repeat('~',50)."\n";

$cookie = "ci_session=f59a0f9b0e1d651b6fc0d8c8c4eadc8ba7bf739d; csrf_cookie_name=da1b36645d7f592674b855264a109784";
$useragent = "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/100.0.4896.75 Safari/537.36";

$header[] = "cookie: ".$cookie;
$header[] = "user-agent: ".$useragent;

$url = "https://topfaucet.co/dashboard";
$res = Run($url, $header);
//<span class="d-none d-xl-inline-block ml-1" key="t-henry">iewilmaestro</span>
$username = explode('</span>',explode('<span class="d-none d-xl-inline-block ml-1" key="t-henry">',$res)[1])[0];
//<h4 class="mb-0">2777.6 tokens</h4>
$balance = explode('</h4>',explode('<h4 class="mb-0">',$res)[1])[0];

print "Username : ".$username."\n";
print "Balance  : ".$balance."\n";
print $line;

while(true){
	$url = "https://topfaucet.co/auto";
	$res = Run($url, $header);
	//<input type="hidden" name="token" value="PzwQKht1e62qiET8N5sD">
	$token = explode('">',explode('<input type="hidden" name="token" value="',$res)[1])[0];
	//let timer = 300,
	$timer = explode(',',explode('let timer = ',$res)[1])[0];
	for($i=$timer;$i>0;$i--){
		print "\r     \r";
		print $i;
		sleep(1);
		print "\r     \r";
	}

	$data = "token=".$token;
	$url = "https://topfaucet.co/auto/verify";
	$res = Run($url, $header, $data);
	//</b> to get 34.7 tokens                        </div>
	$sukses = trim(explode('</div>',explode('</b> to get ',$res)[1])[0]);
	$url = "https://topfaucet.co/dashboard";
	$res = Run($url, $header);
	$balance = explode('</h4>',explode('<h4 class="mb-0">',$res)[1])[0];
	print "Sukses   : ".$sukses."\n";
	print "Balance  : ".$balance."\n";
	print $line;
}
