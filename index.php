<?php
 
session_start();
function ipogrenme(){
if(getenv("HTTP_CLIENT_IP")){
$ip = getenv("HTTP_CLIENT_IP");
}else if(getenv("HTTP_X_FORWARDED_FOR")){
$ip = getenv("HTTP_X_FORWARDED_FOR");
if(strstr($ip, ',')) {
$tmp = explode (',', $ip);
$ip = trim($tmp[0]);
}
}else{
$ip = getenv("REMOTE_ADDR");
}
return $ip;
}


// siteniz.com/visitors.html ile ulaşabilirsiniz

function logla($logmetin){
$dosya = 'visitors.html';
$tarayici= $_SERVER["HTTP_USER_AGENT"];
$metin = date('d-m-Y h:i - ').$logmetin;
$fh = fopen($dosya, 'a+') or die('Hata!');
fwrite($fh, $metin . " " . $tarayici.'<br/>');
fclose($fh);
$_SESSION['ipadreskayit'] = true;
}
 

if(!isset($_SESSION['ipadreskayit'])){
function sehir_bul($ip){
$default = 'Bilinmiyor';
if (!is_string($ip) || strlen($ip) < 1 || $ip == '127.0.0.1' || $ip == 'localhost'){
$ip = '8.8.8.8';
}
$curlopt_useragent = 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/46.0.2490.71 Safari/537.36';
$url = 'http://www.ipsorgu.com/?ip=' . urlencode($ip) . '#sorgu';
$ch = curl_init();
$curl_opt = array(
CURLOPT_FOLLOWLOCATION => 1,
CURLOPT_HEADER => 0,
CURLOPT_RETURNTRANSFER => 1,
CURLOPT_USERAGENT => $curlopt_useragent,
CURLOPT_URL => $url,
CURLOPT_TIMEOUT => 1,
CURLOPT_REFERER => 'http://' . $_SERVER['HTTP_HOST'],
);
curl_setopt_array($ch, $curl_opt);
$content = curl_exec($ch);
curl_close($ch);
 
if(preg_match('#<title>(.*?)</title>#', $content, $regs)){
$city = $regs[1];
}
if($city != ''){
$metin = explode('-', $city);
logla($metin[0]);
}else{
return $default;
}
}
sehir_bul(ipogrenme());
}
 
$ipadresss = $_SERVER["REMOTE_ADDR"];
$yazirenk = "#ffffff";
$yaziboyut = "3";
$yazitipi = "Verdana";
echo"<font face=$yazitipi size=$yaziboyut color=$yazirenk>IP Adresiniz Kayıt Altındadır IP : $ipadresss</font>";



?>
