<?php

$ip_adress = $_SERVER['REMOTE_ADDR'];
$api_url = "http://ip-api.com/json/" . $ip_adress;
$response = file_get_contents($api_url);
$location_data = json_decode($response, true);
$city = '';
$country = '';
$city_country = '';
if ($location_data && $location_data['status'] === 'success') {
    $city = $location_data['city'];
    $country = $location_data['country'];
}

?>