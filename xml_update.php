<?php
$xml = file_get_contents('movies_list.xml');

$sxml = simplexml_load_string($xml);

$info = 'New info';
$buggies = 'New buggies';
$trolleys = 'New trolleys';

$sxml->movie->Title = $info;
$sxml->movie->Year = $buggies;

file_put_contents('movies_list.xml', $sxml->asXML());
?>