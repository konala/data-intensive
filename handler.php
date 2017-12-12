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
		"EU" => "52.169.151.180",
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

	$returnCode = 0;
	
	if($_GET["p"]=="getOrders"){
		$region = $_SESSION["loggedRegion"];
		$stmt = ${"db$region"}->prepare("SELECT orderID, productID, quantity FROM order1 WHERE customerID = :f1");
		$stmt->bindParam(":f1", $_SESSION["loggedId"]);
		$stmt->execute();
		$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
		
		foreach($orders as $order) {
			if($order["productID"] == 1) {
				
				print "Order Number: " . $order["orderID"] . "\nProduct: mug\nQuantity: " . $order["quantity"] . "\n";

			} else {
				print "Order Number: " . $order["orderID"] . "\nProduct: shirt\nQuantity: " . $order["quantity"] . "\n";

			}
			print "\n";
		}
		$returnCode = 0;
	} elseif($_GET["p"]=="getAllOrders") {
		$printedOrders = Array();
		foreach ($dbos as $key => $region) {
	    
		    $stmt = ${"db$key"}->prepare("SELECT orderID, productID, quantity, customerID FROM order1");
		    $stmt->execute();
		    $allOrders  = $stmt->fetchAll(PDO::FETCH_ASSOC);
		    
		    foreach($allOrders as $order) {
		    	
		    	if(!in_array($order["orderID"], $printedOrders)) {
			    	array_push($printedOrders, $order["orderID"]);
			    	print "Order Number: " . $order["orderID"] . "\nCustomer ID: " . $order["customerID"] . "\nProductID: " . $order["productID"] ."\nQuantity: " . $order["quantity"] . "\n\n";
		    	}
		    }
		}
		$returnCode = 0;
	} elseif($_GET["p"]=="getAccountInfo") {
		print "User: " . $_SESSION["loggedUser"] . "\nRegion: " . $_SESSION["loggedRegion"] . "\nUser ID: " . $_SESSION["loggedId"];
		$returnCode = 0;
	} elseif($_GET["p"]=="register") {
		/* Find the highest user ID */
  		$uid = 0;
	  	foreach ($dbos as $key => $region) {
	    
		    $stmt = ${"db$key"}->prepare("SELECT MAX(customerID) AS MaxID FROM customer");
		    $stmt->execute();
		    $newMax  = $stmt->fetchAll(PDO::FETCH_ASSOC);
		    
		    $i = 1;
		    
		    

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
		$userList = Array();
		foreach ($dbos as $key => $region) {
	    
		    $stmt = ${"db$key"}->prepare("SELECT name, customerID, region FROM customer WHERE name = :f1 AND password = :f2");
		    $stmt->bindParam(":f1", $_POST["Name"]);
			$stmt->bindParam(":f2", $_POST["Password"]);
		    $stmt->execute();
		    $userInfo = $stmt->fetchAll(PDO::FETCH_ASSOC);
		    print_r($userInfo);
		    array_push($userList, $userInfo);
		    
		    
		    
		}

		foreach($userList as $user) {
			foreach($user as $cred) {
				if($cred["name"] !== "") {
					$_SESSION["loggedUser"] = $_POST["Name"];
					$_SESSION["loggedRegion"] = $cred["region"];
					$_SESSION["loggedId"] = $cred["customerID"];
				}
			}
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
		print "<p>Action completed!</p><a href='index2.php'>Back to front page!</a>";
	}
	
}

?>