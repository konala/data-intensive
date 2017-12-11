<?php


if(isset($_GET["p"])) {
	db();

} else {
	print "p not set!";

}

/* Check for required db operation and run it */
function db() {
	$dbos = array(
		"EU" => "localhost",
		"NA" => "52.164.184.175",
		"AS" => "40.69.220.19"
	);
	$i = 1;
	try {
		foreach ($dbos as $key => $region) {
			${"db$key"} = new PDO('mysql:host=' . $region . ';dbname=db' . $i . ';charset=utf8', 'app', 'app');
			$i++;
		}

	} catch (PDOException $e){
		echo "Connection failed: " . $e->getMessage();
	}

	
	if($_GET["p"]=="getOrders"){
		print "getOrders";
		
	} elseif($_GET["p"]=="getAccountInfo") {

		print "getAccountInfo" . "User: " . $_SESSION["loggedUser"];
	} elseif($_GET["p"]=="register") {
		print "register";
	} elseif($_GET["p"]=="login") {
		print "login";
		$_SESSION["loggedUser"] = $_POST["Name"];
	} elseif($_GET["p"]=="getOrderDetails") {
		print "getOrderDetails";
	} elseif($_GET["p"]=="makeOrder") {
		print "<p>makeOrder</p>";
	} else {
		print "Error in handler.php\n";
	}

}



/*if(!isset($_POST["Name"]) && $_GET["p"]=="register"){
	print "<p>" . $_POST["region"] . "</p>";
	
	$db = new PDO('mysql:host=localhost;dbname=db1;charset=utf8', 'app', 'app');
	$stmt = $db->prepare("INSERT INTO `customer`(`customerID`,`username`,`region`,`name`,`address`) VALUES (1, :f2, :f3, :f4, :f5)");
	
	$stmt->bindParam(":f2", $_POST["Name"]);
	$stmt->bindParam(":f3", $_POST["region"]);
	$stmt->bindParam(":f4", $_POST["Name"]);
	$stmt->bindParam(":f5", $_POST["Password"]);
	$stmt->execute();
	
  } if ($_POST["Name"]=="db2") {
	  $db = new PDO('mysql:host=52.164.184.175;dbname=db2;charset=utf8', 'app', 'app');
	  
	  $stmt = $db->prepare("INSERT INTO `customer`(`customerID`,`username`,`region`,`name`,`address`) VALUES (1, :f2, :f3, :f4, :f5)");
	  $stmt->bindParam(":f2", $_POST["Name"]);
		$stmt->bindParam(":f3", $_POST["region"]);
		$stmt->bindParam(":f4", $_POST["Name"]);
		$stmt->bindParam(":f5", $_POST["Password"]);
		$stmt->execute();
	  
  }*/




?>