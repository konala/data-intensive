<?php 

function checkConnections() {
	$endPoints = Array(
		Array(
		"Region" => "EU",
		"IP" => "52.169.151.180",
		"Online" => "No",
		"Name" => "db1"),
		Array(
		"Region" => "NA",
		"IP" => "52.164.184.175",
		"Online" => "No",
		"Name" => "db2"),
		Array(
		"Region" => "AS",
		"IP" => "52.169.20.75",
		"Online" => "No",
		"Name" => "db3"
		));
	$port = 80;
	$i = 0;
	foreach($endPoints as $endPoint) {
		
		$isOnline = @fsockopen($endPoint["IP"], $port, $errno, $errstr, 1);
		if(!$isOnline) {
			#print $errstr;
		} else {
			$endPoints[$i]["Online"] = "Yes";
		}
		$i++;
		
	}
	return $endPoints;
}

?>