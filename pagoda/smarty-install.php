<?php
/*
	SmartyPHP v3.1.12
	cURL Installer for Pagoda Box v1.06
	Copyright 2012 by Martin Zeitler
	http://codefx.biz/contact
*/

/* the environment */
$fn='Smarty-3.1.12.zip';
$src='http://www.smarty.net/files/'.$fn;
$base_dir = str_replace('/pagoda','', dirname(__FILE__));
$hostname=$_SERVER['APP_NAME'].'.pagodabox.com';
$dst=$base_dir.'/pagoda/'.$fn;

/* fetch the package */
wget($src, $dst);

/* extract the package */
$zip = new ZipArchive;
if($zip->open($dst) === TRUE) {
	$zip->extractTo(dirname(__FILE__));
	$zip->close();
}

echo 'SmartyPHP v3.1.12 will now be deployed & configured.';

function wget($src, $dst){
	$fp = fopen($dst, 'w');
	$curl = curl_init();
	$opt = array(CURLOPT_URL => $src, CURLOPT_HEADER => false, CURLOPT_FILE => $fp);
	curl_setopt_array($curl, $opt);
	$rsp = curl_exec($curl);
	if($rsp===false){
		die("[cURL] errno:".curl_errno($curl)."\n[cURL] error:".curl_error($curl)."\n");
	}
	$info = curl_getinfo($curl);
	curl_close($curl);
	fclose($fp);
	
	/* cURL stats */
	$time = $info['total_time']-$info['namelookup_time']-$info['connect_time']-$info['pretransfer_time']-$info['starttransfer_time']-$info['redirect_time'];
	echo "Fetched '$src' @ ".abs(round(($info['size_download']*8/$time/1024/1024),2))."MBit/s.\n";
}

function format_size($size=0) {
	if($size < 1024){
		return $size.'b';
	}
	elseif($size < 1048576){
		return round($size/1024,2).'kb';
	}
	else {
		return round($size/1048576,2).'mb';
	}
}
?>