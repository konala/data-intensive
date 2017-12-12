<?php 
$repl = Array(".", ":");
$userIP = str_replace($repl, "", $_SERVER["REMOTE_ADDR"]);
//mt_srand($userIP);
$regId = mt_rand(1, 3);
$servers = Array();
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
	"IP" => "40.69.220.19",
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
#Debug prints
#print_r($endPoints);
#print($regId);
# Priority EU, NA, AS
if ($regId == 1) {
	if($endPoints[0]["Online"] == "Yes") {
		header("Location: http://52.169.151.180/index2.php");
		exit();
	} elseif($endPoints[1]["Online"] == "Yes"){
		header("Location: http://52.164.184.175/index2.php"); # NA IP here
		exit();
	} elseif($endPoints[2]["Online"] == "Yes"){
		header("Location: http://40.69.220.19/index2.php"); # AS IP here
		print "debug";
		exit();
	} else {
		print "All servers down!"; # index.php needs to be run from load balancer server! As this cannot currently be reached.
	}
# Priority NA, AS, EU
} elseif ($regId == 2) {
	if($endPoints[1]["Online"] == "Yes") {
		header("Location: http://52.164.184.175/index2.php");
		exit();
	} elseif($endPoints[2]["Online"] == "Yes"){
		header("Location: http://40.69.220.19/index2.php"); 
		exit();
	} elseif($endPoints[0]["Online"] == "Yes"){
		header("Location: http://52.169.151.180/index2.php"); 
		exit();
	} else {
		print "All servers down!"; # index.php needs to be run from load balancer server!
	}
# Priority AS, NA, EU
} else {
	if($endPoints[2]["Online"] == "Yes") {
		header("Location: http://40.69.220.19/index2.php");
		exit();
	} elseif($endPoints[1]["Online"] == "Yes"){
		header("Location: http://52.164.184.175/index2.php"); 
		exit();
	} elseif($endPoints[0]["Online"] == "Yes"){
		header("Location: http://52.169.151.180/index2.php"); 
		exit();
	} else {
		print "All servers down!"; # index.php needs to be run from load balancer server!
	}
}
 ?>