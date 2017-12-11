<?php
if (session_status() !== 2) {
	session_start();

}

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
		$returnCode = 0;
	} elseif($_GET["p"]=="getAccountInfo") {

		print "getAccountInfo" . "User: " . $_SESSION["loggedUser"];
		$returnCode = 0;
	} elseif($_GET["p"]=="register") {
		/* Find the highest user ID */
  		$uid = 0;
	  	foreach ($dbos as $key => $region) {
	    
		    $stmt = ${"db$key"}->prepare("SELECT MAX(customerID) AS MaxID FROM customer");
		    $stmt->execute();
		    $newMax  = $stmt->fetchAll(PDO::FETCH_ASSOC);
		    $i = 1;
		    print $newMax[0]["MaxID"];
		    if($uid < $newMax[0]["MaxID"]) {
		    	$uid = $newMax[0]["MaxID"];
		    }
		}
		$uid++;
		$region = $_POST["region"];
		$stmt = ${"db$region"}->prepare("INSERT INTO `customer`(`customerID`,`name`,`password`,`region`) VALUES (:f1, :f2, :f3, :f4)");
	
		$stmt->bindParam(":f1", $uid);
		$stmt->bindParam(":f2", $_POST["Name"]);
		$stmt->bindParam(":f3", $_POST["Password"]);
		$stmt->bindParam(":f4", $region);
		$stmt->execute();
	   	$returnCode = 1;
 	} elseif($_GET["p"]=="login") {
		print "login";
		foreach ($dbos as $key => $region) {
	    
		    $stmt = ${"db$key"}->prepare("SELECT name, customerID, region FROM customer WHERE name = :f1 AND password = :f2");
		    $stmt->bindParam(":f1", $_POST["Name"]);
			$stmt->bindParam(":f2", $_POST["Password"]);
		    $stmt->execute();
		    $userInfo = $stmt->fetchAll(PDO::FETCH_ASSOC);
		    
		}
		if(count($userInfo) == 1) {
			$_SESSION["loggedUser"] = $_POST["Name"];
			$_SESSION["loggedRegion"] = $userInfo[0]["region"];
			$_SESSION["loggedId"] = $userInfo[0]["customerID"];
		} else {

		}
		
		$returnCode = 1;
	} elseif($_GET["p"]=="getOrderDetails") {
		$returnCode = 0;
	} elseif($_GET["p"]=="makeOrder") {
		foreach ($dbos as $key => $region) {
	    
		    $stmt = ${"db$key"}->prepare("INSERT INTO order1(customerID, productID, quantity) VALUES (:f1, :f2, :f3)");
		    $stmt->bindParam(":f1", $_SESSION["loggedId"]);
			if($_POST["product"] == "mug") {
				$pId = 1;
				$stmt->bindParam(":f2", $pId);
			} else {
				$pId = 2;
				$stmt->bindParam(":f2", $pId);
			}
			$stmt->bindParam(":f3", $_POST["quantity"]);
		    $stmt->execute();
		   
		    
		}

		$returnCode = 1;
	} elseif($_GET["p"]=="logout") {
		unset($_SESSION["loggedUser"]);
		$returnCode = 1;
	} else {
		print "Error in handler.php\n";
	}
	if ($returnCode == 1) {
		print "<p>Action completed!</p><a href='index.php'>Back to front page!</a>";
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