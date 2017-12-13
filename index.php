<?php 
require("connection.php");
$regId = mt_rand(1, 3);
$endPoints = checkConnections();
#Debug prints
#print_r($endPoints);
#print($regId);
# Priority EU, NA, AS
#$regId = 1; #For dev
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