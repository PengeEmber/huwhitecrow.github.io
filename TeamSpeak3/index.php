<?php
/*
 Author: XARON
 File: TeamSpeak3DynamicBanner: Main
 Last Update: 05.06.2017 13:50

www.facebook.com/XARONNN
*/
header('Content-type: image-png; charset=utf-8');
error_reporting(E_ALL);
require_once('ts3admin.class.php');
require_once('TeamSpeak3/TeamSpeak3.php');

$ayarlar = array();
$resimler = array();
$ayarlar['host'] = '80.211.210.179'; //Server IP (recommended localserver)
$ayarlar['queryAdi'] = 'serveradmin'; //Query Name
$ayarlar['querySifre'] = 'jArxegDF'; //Query Password
$ayarlar['queryPort'] = 10011; //Query Port
$ayarlar['sunucuPort'] = 9987; //Server Port
$ayarlar['botAdi'] = 'WhiteCrow | Banner'; //Bot Name
$ayarlar['apiKey'] = 'b464096716b347ff91602144182306'; // "apixu.com" Api Key
$ayarlar['dil'] = 'english'; // Language ("turkish", "english") etc..
$ayarlar['ipApi'] = 'apixu'; // Api ("blazinglayer", "apixu") support ip api. (If blazinglayer api don't work, change to apixu.)
$ayarlar['ipApiKey'] = 'free'; // Ip api key
$resimler = ['1.png','2.png','3.png','4.png','5.png','6.png','7.png','8.png','9.png']; // Background's

setlocale(LC_ALL, $ayarlar['dil']);
$ts3Baglan = TeamSpeak3::factory('serverquery://'.$ayarlar['queryAdi'].':'.$ayarlar['querySifre'].'@'.$ayarlar['host'].':'.$ayarlar['queryPort'].'/?server_port='.$ayarlar['sunucuPort'].'&blocking=0&nickname='.urlencode($ayarlar['botAdi']));
$clientListe = $ts3Baglan->clientList(array('connection_client_ip' => $_SERVER['REMOTE_ADDR']));

foreach($clientListe as $clientListeYaz)
{
  $clientAdYaz = $clientListeYaz['client_nickname'];
}

function rastgeleSec($x) {
 return $x[rand(0, sizeof($x)-1)];
}
function ipAdresindenSehir($ip = null){

    if($ip == null){
        if(getenv('HTTP_CLIENT_IP')){
            $ip = getenv('HTTP_CLIENT_IP');
        }elseif(getenv('HTTP_X_FORWARDED_FOR')){
            $ip = getenv('HTTP_X_FORWARDED_FOR');
            if(strstr($ip, ',')){
                $gec = explode (',', $ip);
                $ip = trim($gec[0]);
            }
        }else{
            $ip = getenv('REMOTE_ADDR');
        }
    }
	$apiUrl = ($ayarlar['ipApi'] == 'blazinglayer') ? $apiUrl = 'http://api.blazinglayer.co.uk/ip/json/'.$ip.'/'.$ayarlar['ipApiKey'] : $apiUrl = 'http://ipinfo.io/'.$ip ; 
    $json = file_get_contents($apiUrl);
    $detaylar = json_decode($json);
    return $detaylar;
}

$resim = imagecreatefrompng(rastgeleSec($resimler));
$gri = imagecolorallocate($resim, 255, 255, 255);
$beyaz = imagecolorallocate($resim, 255, 255, 255);
$query = new ts3admin($ayarlar['host'], $ayarlar['queryPort']);
$ip = ipAdresindenSehir(null);

$url = file_get_contents('http://api.apixu.com/v1/current.json?key='.$ayarlar['apiKey'].'&q='.$ip->city);
$json = json_decode($url, true);
date_default_timezone_set($json['location']['tz_id']);

$havaDurumuResimUrl = $json['current']['condition']['icon'];
$havaDurumuResimUrl = str_replace('//', 'http://', $havaDurumuResimUrl);
$fligram = imagecreatefrompng($havaDurumuResimUrl);

$konum_x = imagesx($fligram);
$konum_y = imagesy($fligram);

if($query->getElement('success', $query->connect()))
{
	$query->login($ayarlar['queryAdi'],$ayarlar['querySifre']);
    $query->selectServer($ayarlar['sunucuPort']);
	$query->setName($ayarlar['botAdi']);

    $sunucuBilgi = $query->getElement('data', $query->serverInfo());
    $queryClient = $sunucuBilgi['virtualserver_queryclientsonline'];
	$normalClient = $sunucuBilgi['virtualserver_clientsonline'];
	$sunucuKapasite = $sunucuBilgi['virtualserver_maxclients'];
	$sunucuAdi = $sunucuBilgi['virtualserver_name'];
	$sehirAdi = $json['location']['name'];
	$sehirKacDerece = $json['current']['temp_c'];
	$aktifKullanici = ($normalClient - $queryClient);
	
	//imagettftext($resim, 25, 0, 285, 55, $beyaz, 'CaviarDreams.ttf', $sunucuAdi);
	imagettftext($resim, 29, 0, 85, 199, $beyaz, './CaviarDreams.ttf', 'Üdvözöllek '.$clientAdYaz.'!');
	imagettftext($resim, 14, 5, 689, 297, $gri, './CaviarDreams_Bold.ttf', strftime('%e %B %Y'));
	imagettftext($resim, 33, 5, 696, 273, $gri, './CaviarDreams_Bold.ttf', date('H:i'));
	imagettftext($resim, 30, 0, 80, 277, $gri, './CaviarDreams_Bold.ttf', ''.$aktifKullanici.'/'.$sunucuKapasite);
	imagettftext($resim, 25, 0, 65, 65, $beyaz, './CaviarDreams.ttf', $sehirAdi);
	imagettftext($resim, 20, 0, 115, 105, $beyaz, './CaviarDreams.ttf', $sehirKacDerece.'°');
	imagecopy($resim, $fligram, imagesx($resim) - $konum_x - 786, imagesy($resim) - $konum_y - 205, 0, 0, imagesx($fligram), imagesy($fligram));
	
}
imagepng($resim);
imagedestroy($resim);
?>
