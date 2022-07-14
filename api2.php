<?php
// set location
$address = "Brooklyn+NY+USA";

//set map api url
//$url = "https://www.itexmo.com/php_api/serverstatus.php?apicode=TR-HEALT729430_UXRZ3";
$url = "https://www.itexmo.com/php_api/apicode_info.php?apicode=TR-HEALT729430_UXRZ3";


//call api
$json = file_get_contents($url);
$json = json_decode($json);
//echo $json;
//print_r($json);

$myString = print_r($json, TRUE); 
$array = explode(" => ",$myString); 
echo $array[5];

// output
// Latitude: 40.6781784, Longitude: -73.9441579
?>