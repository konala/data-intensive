<?php
	$name = getenv("COMPUTERNAME");
	switch ($name) {
		case "db1":
			print "<p>You are visiting EU site! Is this not your region? Go to <a href='http://52.164.184.175/index2.php'>North America</a> or <a href='http://40.69.220.19/index2.php'>Asia</a> instead!</p>";
			break;
		case "db2":
			print "<p>You are visiting NA site! Is this not your region? Go to <a href='http://52.169.151.180/index2.php'>Europe</a> or <a href='http://40.69.220.19/index2.php'>Asia</a> instead!</p>";
			break;
		case "db3":
			print "<p>You are visiting AS site! Is this not your region? Go to <a href='http://52.164.184.175/index2.php'>North America</a> or <a href='http://52.169.151.180/index2.php'>Europe</a> instead!</p>";
			break;
	}
?>