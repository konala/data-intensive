<?php
require("connection.php");
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
	$endPoints = checkConnections();

	/*$dbos = array();
	foreach($endPoints as $endPoint) {
		if($endPoint["Online"] = "Yes") {
			$dbos[$endPoint["Region"]] = $endPoint["IP"];
		}
		
	}*/
	/*$dbos = array(
		"EU" => "52.169.151.180",
		"NA" => "52.164.184.175",
		"AS" => "40.69.220.19"
	);*/
	$i = 0;
	try {
		foreach ($endPoints as $endPoint) {
			if($endPoint["Online"] == "Yes") {
				$region = $endPoint["Region"];
				${"db$region"} = new PDO('mysql:host=' . $endPoint["IP"] . ';dbname=' . $endPoint["Name"] . ';charset=utf8', 'app', 'app');
				#array_push($connections, ${"db$region"});
				$endPoints[$i]["conn"] = ${"db$region"};
			} else {
				$endPoints[$i]["conn"] = NULL;
			}
			$i++;
		}

	} catch (PDOException $e){
		echo "Connection failed: " . $e->getMessage();
	}

	$returnCode = 0;
	#var_dump($endPoints);
	if($_GET["p"]=="getOrders"){
		getOrders($endPoints);
	}elseif($_GET["p"]=="getAllOrders") {
		getAllOrders($endPoints);
	} elseif($_GET["p"]=="getAccountInfo") {
		getAccountInfo();
	} elseif($_GET["p"]=="register") {
		register($endPoints);
 	} elseif($_GET["p"]=="login") {
		login($endPoints);
	} elseif($_GET["p"]=="getOrderDetails") {
		$returnCode = 0;
	} elseif($_GET["p"]=="makeOrder") {
		makeOrder($endPoints);
	} elseif($_GET["p"]=="logout") {
		logout();
	} elseif($_GET["p"]=="sync") {
		sync($endPoints);
	} else {
		handlerError();
	}
	
	
}

function getOrders($endPoints) {

	$stmt = $endPoints[0]["conn"]->prepare("SELECT orderID, productID, quantity FROM order1 WHERE customerID = :f1");

	
	$stmt->bindParam(":f1", $_SESSION["loggedId"]);
	$stmt->execute();
	print "#############\nDebug\nQuery executed: " . $stmt->queryString . "\n:f1 = " . $_SESSION["loggedId"] . "\n#############\n";
	$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
	
	foreach($orders as $order) {
		if($order["productID"] == 1) {
			
			print "Order Number: " . $order["orderID"] . "\nProduct: mug\nQuantity: " . $order["quantity"] . "\n";

		} else {
			print "Order Number: " . $order["orderID"] . "\nProduct: shirt\nQuantity: " . $order["quantity"] . "\n";

		}
		print "\n";
	}

}

function getAllOrders($endPoints) {
	$printedOrders = Array();
		foreach ($endPoints as $endPoint) {
	    	if($endPoint["Online"] == "Yes") {
			    $stmt = $endPoint["conn"]->prepare("SELECT orderID, productID, quantity, customerID FROM order1");
			    $stmt->execute();
			    $allOrders  = $stmt->fetchAll(PDO::FETCH_ASSOC);
			}
			    foreach($allOrders as $order) {
			    	
			    	if(!in_array($order["orderID"], $printedOrders)) {
				    	array_push($printedOrders, $order["orderID"]);
				    	print "Order Number: " . $order["orderID"] . "\nCustomer ID: " . $order["customerID"] . "\nProductID: " . $order["productID"] ."\nQuantity: " . $order["quantity"] . "\n\n";
			    	}
			    }	
		}

}

function getAccountInfo() {
	print "User: " . $_SESSION["loggedUser"] . "\nRegion: " . $_SESSION["loggedRegion"] . "\nUser ID: " . $_SESSION["loggedId"];
}
function register($endPoints) {
		/* Find the highest user ID */
  		$uid = 0;
	  	foreach ($endPoints as $endPoint) {
	    	if($endPoint["Online"] == "Yes") {
			    $stmt = $endPoint["conn"]->prepare("SELECT MAX(customerID) AS MaxID FROM customer");
			    $stmt->execute();
			    $newMax  = $stmt->fetchAll(PDO::FETCH_ASSOC);
			    
			    $i = 1;
			    
			    

			    if($uid < $newMax[0]["MaxID"]) {
			    	$uid = $newMax[0]["MaxID"];
			    }

			    foreach ($endPoints as $endPoint) {
		    		if($endPoint["Online"] == "Yes") {
					    $stmt = $endPoint["conn"]->prepare("SELECT `custidmax` AS `MaxID1` FROM `idlog` WHERE id = 1");
					    $stmt->execute();
					    $newMax1  = $stmt->fetchAll(PDO::FETCH_ASSOC);
					}
				    if($uid < $newMax1[0]["MaxID1"]) {
				    	$uid = $newMax1[0]["MaxID1"];
				    }
				}
			}

		}
		$uid++;	    
		$region = $_POST["region"];
		$queryExecuted = 0;
		foreach($endPoints as $endPoint) {
			if($endPoint["Region"] == $region && $endPoint["Online"] == "Yes") {
				
				$stmt = $endPoint["conn"]->prepare("INSERT INTO customer(customerID, name, password, region) VALUES (:f1, :f2, :f3, :f4)");
	
				$stmt->bindParam(":f1", $uid);
				$stmt->bindParam(":f2", $_POST["Name"]);
				$stmt->bindParam(":f3", $_POST["Password"]);
				$stmt->bindParam(":f4", $region);
				$stmt->execute();
				print "<p>#############\nDebug\nQuery executed: " . $stmt->queryString . "\n:f1 = " . $uid . "\n:f2 = " . $_POST["Name"] . "\n:f3 = " . $_POST["Password"] . "\n:f4 = " . $region . "\n#############\n</p>";
			   	$queryExecuted = 1;
			   	$stmt2 = $endPoint["conn"]->prepare("UPDATE `idlog` SET `custidmax` = :f1 WHERE id = 1");
			    $stmt2->bindParam(":f1", $uid);
			    $stmt2->execute();
			} elseif ($queryExecuted == 0 && $endPoint == end($endPoints)) {
				$returnCode = 2;
				finalMessage($returnCode);
				exit();
			}
		}

		$returnCode = 1;
		finalMessage($returnCode);
}

function login($endPoints) {
		$userList = Array();
		foreach ($endPoints as $endPoint) {
	    	if($endPoint["Online"] == "Yes") {
			    $stmt = $endPoint["conn"]->prepare("SELECT name, customerID, region FROM customer WHERE name = :f1 AND password = :f2");
			    $stmt->bindParam(":f1", $_POST["Name"]);
				$stmt->bindParam(":f2", $_POST["Password"]);
			    $stmt->execute();
			    $userInfo = $stmt->fetchAll(PDO::FETCH_ASSOC);
			    
			    array_push($userList, $userInfo);
			}
		    
		    
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
		finalMessage($returnCode);
}

function makeOrder($endPoints) {
	foreach ($endPoints as $endPoint) {
	   	if($endPoint["Online"] == "Yes") { 
		    $stmt = $endPoint["conn"]->prepare("INSERT INTO order1(customerID, productID, quantity) VALUES (:f1, :f2, :f3)");
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
	    
	}
	$returnCode = 1;
	finalMessage($returnCode);
}

function sync($endPoints) {
	foreach ($endPoints as $endPoint) {
	   	if($endPoint["Online"] !== "Yes") { 

	   		print "at least one database down, cannot sync";
	   		return;
	   	}

    }
    $i = 1;
    foreach ($endPoints as $endPoint) {
	    	
			    $stmt = $endPoint["conn"]->prepare("SELECT COUNT(orderID) AS MaxID FROM order1");
			    $stmt->execute();
			    $newOrdMax  = $stmt->fetchAll(PDO::FETCH_ASSOC);
			    if ($i==1){
			    	$db1max = $newOrdMax[0]["MaxID"];
			    } else if($i==2) {
			    	$db2max = $newOrdMax[0]["MaxID"];
			    } else {
			    	$db3max = $newOrdMax[0]["MaxID"];
			    }
			    $i++;
			    
	}
	if (($db1max !== $db2max) or ($db1max !== $db3max)) {

		if($db1max == max($db1max,$db2max,$db3max)){

			$stmt = $endPoints[1]["conn"]->prepare("DELETE `*` FROM order1");
			$stmt->execute();
			$stmt = $endPoints[2]["conn"]->prepare("DELETE `*` FROM order1");
			$stmt->execute();
			$stmt = $endPoints[0]["conn"]->prepare("SELECT `*` FROM order1");
			$stmt->execute();
			$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
			foreach($rows as $row) {
								
				$stmt = $endPoints[1]["conn"]->prepare("INSERT INTO order1(`orderID`, `customerID`,`productID`,`quantity`) VALUES (:f1, :f2, :f3, :f4)");
				$stmt->bindParam(":f1", $row["orderID"]);
				$stmt->bindParam(":f2", $row["customerID"]);
				$stmt->bindParam(":f3", $row["productID"]);
				$stmt->bindParam(":f4", $row["quantity"]);
				$stmt->execute();
				$stmt = $endPoints[2]["conn"]->prepare("INSERT INTO order1(`orderID`, `customerID`,`productID`,`quantity`) VALUES (:f1, :f2, :f3, :f4)");
				$stmt->bindParam(":f1", $row["orderID"]);
				$stmt->bindParam(":f2", $row["customerID"]);
				$stmt->bindParam(":f3", $row["productID"]);
				$stmt->bindParam(":f4", $row["quantity"]);
				$stmt->execute();
				
			}
			

		}else if($db2max == max($db1max,$db2max,$db3max)){

			$stmt = $endPoints[0]["conn"]->prepare("DELETE `*` FROM order1");
			$stmt->execute();
			$stmt = $endPoints[2]["conn"]->prepare("DELETE `*` FROM order1");
			$stmt->execute();
			$stmt = $endPoints[1]["conn"]->prepare("SELECT `*` FROM order1");
			$stmt->execute();
			$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
			foreach($rows as $row) {
								
				$stmt = $endPoints[0]["conn"]->prepare("INSERT INTO order1(`orderID`, `customerID`,`productID`,`quantity`) VALUES (:f1, :f2, :f3, :f4)");
				$stmt->bindParam(":f1", $row["orderID"]);
				$stmt->bindParam(":f2", $row["customerID"]);
				$stmt->bindParam(":f3", $row["productID"]);
				$stmt->bindParam(":f4", $row["quantity"]);
				$stmt->execute();
				$stmt = $endPoints[2]["conn"]->prepare("INSERT INTO order1(`orderID`, `customerID`,`productID`,`quantity`) VALUES (:f1, :f2, :f3, :f4)");
				$stmt->bindParam(":f1", $row["orderID"]);
				$stmt->bindParam(":f2", $row["customerID"]);
				$stmt->bindParam(":f3", $row["productID"]);
				$stmt->bindParam(":f4", $row["quantity"]);
				$stmt->execute();
				}
				
		}else { 

			$stmt = $endPoints[0]["conn"]->prepare("DELETE `*` FROM order1");
			$stmt->execute();
			$stmt = $endPoints[2]["conn"]->prepare("DELETE `*` FROM order1");
			$stmt->execute();
			$stmt = $endPoints[2]["conn"]->prepare("SELECT `*` FROM order1");
			$stmt->execute();
			$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
			foreach($rows as $row) {
								
				$stmt = $endPoints[0]["conn"]->prepare("INSERT INTO order1(`orderID`, `customerID`,`productID`,`quantity`) VALUES (:f1, :f2, :f3, :f4)");
				$stmt->bindParam(":f1", $row["orderID"]);
				$stmt->bindParam(":f2", $row["customerID"]);
				$stmt->bindParam(":f3", $row["productID"]);
				$stmt->bindParam(":f4", $row["quantity"]);
				$stmt->execute();
				$stmt = $endPoints[1]["conn"]->prepare("INSERT INTO order1(`orderID`, `customerID`,`productID`,`quantity`) VALUES (:f1, :f2, :f3, :f4)");
				$stmt->bindParam(":f1", $row["orderID"]);
				$stmt->bindParam(":f2", $row["customerID"]);
				$stmt->bindParam(":f3", $row["productID"]);
				$stmt->bindParam(":f4", $row["quantity"]);
				$stmt->execute();				
			}
			
		}
		

	}			    	   	
	finalMessage(1);
}

function logout() {
	unset($_SESSION["loggedUser"]);
	$returnCode = 1;
	finalMessage($returnCode);
}


function handlerError() {
	print "Error in handler.php\n";
}

function finalMessage($returnCode) {
	if ($returnCode == 1) {
		print "<p>Action completed!</p><a href='index2.php'>Back to front page!</a>";

	} elseif ($returnCode == 2) {

		print "DB down, account not added! Try again later. </p><a href='index2.php'>Back to front page!</a>";
	}
}
?>