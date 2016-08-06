<?php

///		 CloudFlare API script to change A/AAA record
///			by mumi - https://github.com/mumi/
///		https://github.com/mumi/CloudFlare-Dynamic-DNS

//Subdomain (true) or normal domain (false)?
$use_subdomain = true;

if ($use_subdomain) {
	$only_tld = explode(".", $_GET['domain']);
	$only_tld = $only_tld[1].".".$only_tld[2];
} else {
	$only_tld = $_GET['domain']
}

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, "https://api.cloudflare.com/client/v4/zones?name=$only_tld&status=active&page=1&per_page=20&order=status&direction=desc&match=all");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");

$headers = array();
$headers[] = "X-Auth-Email: {$_GET['email']}";
$headers[] = "X-Auth-Key: {$_GET['key']}";
$headers[] = "Content-Type: application/json";
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$result = curl_exec($ch);
if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
}

$zone_id = json_decode($result)->result[0]->id;


curl_setopt($ch, CURLOPT_URL, "https://api.cloudflare.com/client/v4/zones/$zone_id/dns_records?&name={$_GET['domain']}&page=1&per_page=20&order=!type&direction=desc&match=all");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");

$headers = array();
$headers[] = "X-Auth-Email: {$_GET['email']}";
$headers[] = "X-Auth-Key: {$_GET['key']}";
$headers[] = "Content-Type: application/json";
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$result = curl_exec($ch);
if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
}

$domain_id = json_decode($result)->result[0]->id;
$record_type = json_decode($result)->result[0]->type;


curl_setopt($ch, CURLOPT_URL, "https://api.cloudflare.com/client/v4/zones/$zone_id/dns_records/$domain_id");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, "{\"id\":\"$domain_id\",\"type\":\"$record_type\",\"name\":\"{$_GET['domain']}\",\"content\":\"{$_GET['ip']}\",\"data\":{}}");
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");

$headers = array();
$headers[] = "X-Auth-Email: {$_GET['email']}";
$headers[] = "X-Auth-Key: {$_GET['key']}";
$headers[] = "Content-Type: application/json";
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$result = curl_exec($ch);
if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
}
curl_close ($ch);
?>
