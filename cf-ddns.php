<?php

///		 CloudFlare API script to change A/AAA record
///			by mumi - https://github.com/mumi/
///		https://github.com/mumi/CloudFlare-Dynamic-DNS

//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

//Subdomain (true) or normal domain (false)?
$use_subdomain = true;

if ($use_subdomain) {
	$only_tld = explode(".", $_GET['domain']);
	$only_tld = $only_tld[1].".".$only_tld[2];
} else {
	$only_tld = $_GET['domain'];
}

echo 'Domain: ' . $_GET['domain'] . '<br />';
echo 'Token: ' . $_GET['token'] . '<br />';
echo 'IP: ' . $_GET['ip'] . '<br />';

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, "https://api.cloudflare.com/client/v4/zones?name=$only_tld&status=active&page=1&per_page=20&order=status&direction=desc&match=all");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");

$headers = array();
$headers[] = "Authorization: Bearer {$_GET['token']}";
$headers[] = "Content-Type: application/json";
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$result = curl_exec($ch);
if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
} elseif (!json_decode($result)->success) {
	echo '<br />Errors while processing the request:<br />';
	foreach (json_decode($result, TRUE)['errors'] as $error) {
		echo 'Code: ' . $error['code'] . ', Error: ' . $error['message'] . '<br />';
	}
	return;
}

$zone_id = json_decode($result)->result[0]->id;
echo '<br />Found Zone ID: ' . $zone_id . '<br />';

curl_setopt($ch, CURLOPT_URL, "https://api.cloudflare.com/client/v4/zones/$zone_id/dns_records?&name={$_GET['domain']}&page=1&per_page=20&order=type&direction=desc&match=all");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");

$headers = array();
$headers[] = "Authorization: Bearer {$_GET['token']}";
$headers[] = "Content-Type: application/json";
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$result = curl_exec($ch);
if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
} elseif (!json_decode($result)->success) {
	echo '<br />Errors while processing the request:<br />';
	foreach (json_decode($result, TRUE)['errors'] as $error) {
		echo 'Code: ' . $error['code'] . ', Error: ' . $error['message'] . '<br />';
	}
	return;
}

$domain_id = json_decode($result)->result[0]->id;
$record_type = json_decode($result)->result[0]->type;
echo 'Found Domain ID: ' . $domain_id . '<br />';
echo 'Found Record Type: ' . $record_type . '<br />';


curl_setopt($ch, CURLOPT_URL, "https://api.cloudflare.com/client/v4/zones/$zone_id/dns_records/$domain_id");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, "{\"id\":\"$domain_id\",\"type\":\"$record_type\",\"name\":\"{$_GET['domain']}\",\"content\":\"{$_GET['ip']}\",\"data\":{}}");
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");

$headers = array();
$headers[] = "Authorization: Bearer {$_GET['token']}";
$headers[] = "Content-Type: application/json";
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$result = curl_exec($ch);
if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
} elseif (!json_decode($result)->success) {
	echo '<br />Errors while processing the request:<br />';
	foreach (json_decode($result, TRUE)['errors'] as $error) {
		echo 'Code: ' . $error['code'] . ', Error: ' . $error['message'] . '<br />';
	}
} else {
	echo '<br />Request processed successfully!';
}
curl_close ($ch);
?>
