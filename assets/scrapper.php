<html>
<head>
<title>Scrapper</title>
</head>
<body>
<?php
$url="http://api.wordpress.org/stats/plugin/1.0/downloads.php?slug=share-on-diaspora&limit=730&callback=";
$data = json_decode(file_get_contents($url));

$weekdays = array("0" => 0, "1" => 0, "2" => 0, "3" => 0, "4" => 0, "5" => 0, "6" => 0);
echo "date,weekday,downloads<br/>";
foreach($data as $key => $value) {
	echo $key . "," . date('w', strtotime($key)). "," . $value . "<br/>";
	$weekdays[date('w', strtotime($key))] += $value;
}
/*
foreach($weekdays as $key => $value) {
	echo $key . ": " . $value . "<br/>";
}
*/
?>
</body>
</html>
