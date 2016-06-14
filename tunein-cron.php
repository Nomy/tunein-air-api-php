<?php
// TuneIn Air API PHP Script for IceCast 2.4+ with AutoDJ support
// (c) 2016: Nomy - https://hellclan.co.uk/

// TuneIn Air API Docs: http://tunein.com/broadcasters/api/

// Add this script to crontab to run every 15 seconds (php-cli dependant):
// * * * * * for i in 0 1 2 3 ; do php /home/web/domains/domain.com/public_html/tunein-cron.php & sleep 15; done
// or (using wget to run a script on the web):
// * * * * * for i in 0 1 2 3 ; do wget http://domain.com/tunein-cron.php -O /dev/null & sleep 15; done

$debug = '0'; //Enable debugging

// TuneIn API settings
$StationID  = 'sXXXXXX'; // TuneIn station ID
$PartnerID  = 'XXXXXXXX'; // TuneIn partner ID
$PartnerKey = 'XXXXXXXXXXXX'; // TuneIn partner key

// Set filename and read file into string
$file = 'tunein-air-data.txt';
$data = file_get_contents($file);

// Get data from IceCast2 status-json.xsl
$jsonurl     = 'http://radio.domain.com:8000/status-json.xsl';
$jsonraw     = file_get_contents($jsonurl);
$json        = json_decode($jsonraw, true);

if ($debug){
	echo 'Data in file:';
	echo '<pre>';
	print_r($data);
	echo '</pre>';
	echo '</br></br>';
	echo 'Data in IceCast2 Status JSON:';
	echo '<pre>';
	print_r($json);
	echo '</pre>';
	echo '</br></br>';
}

// Check if Live stream is active
if (isset($json['icestats']['source']['1']['title'])) {
	$array = explode(' - ', $json['icestats']['source']['1']['title']); // Get song title, split artist and song name

	$artist = isset($array[0]) ? urlencode(stripcslashes($array[0])) : '';
	$title  = isset($array[1]) ? urlencode(stripcslashes($array[1])) : '';
	if ($debug){
		echo 'Live data used:';
		echo '<pre>';
		print_r($artist);
		echo '</br>';
		print_r($title);
		echo '</pre>';
		echo '</br></br>';
	}
} elseif (isset($json['icestats']['source']['0']['title'])) { // Else use AutoDJ info
	$array = explode(' - ', $json['icestats']['source']['0']['title']);

	$artist = isset($array[0]) ? urlencode(stripcslashes($array[0])) : '';
	$title  = isset($array[1]) ? urlencode(stripcslashes($array[1])) : '';
	if ($debug){
		echo 'AutoDJ data used:';
		echo '<pre>';
		print_r($artist);
		echo '</br>';
		print_r($title);
		echo '</pre>';
		echo '</br></br>';
	}
}

// Just exit if either infos are missing
if (empty($artist) || empty($title))
	exit;

// Contruct URL
$url = "http://air.radiotime.com/Playing.ashx?partnerId={$PartnerID}&partnerKey={$PartnerKey}&id={$StationID}&title={$title}&artist={$artist}";

// Only send when data has changed
if (strcmp($url, $data) !== 0) {
	// Send over to TuneIn API
	file_get_contents($url);

	// Write URL to file
	file_put_contents($file, $url);
	if ($debug){
		echo 'New URL formed (Sent)';
		echo '</br>';
		echo 'Contructed URL:';
		echo '<pre>';
		print_r($url);
		echo '</pre>';
		echo '</br></br>';
	}
} else {
	if ($debug){
		echo 'No difference (Not Sent)';
		echo '</br>';
		echo 'Contructed URL:';
		echo '<pre>';
		print_r($url);
		echo '</pre>';
		echo '</br></br>';
	}
	exit;
}
?>