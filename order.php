<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>DOTA 2 MERCH</title>
   <?php require_once("navi.php"); ?>
  </head>
  <body>
    <h1>Make an order</h1>
    <div>
    </div>
    <h3>Order details<h2>
    <textarea><?php
      
      $_GET["p"] = "getOrderDetails";
      require_once("handler.php");
      unset($_GET["p"]);
      print "\nQuantity: " . $_GET["DropDown"];
     ?>
    </textarea><br>
    <img src="paypal.jpg" alt="paypal" style="width:200px;height:200px;">
    <form action="handler.php?p=makeOrder" method="post">
      <input type="text" name="Name" value="Name"><br>
      <input type="text" name="Password" value="Password"><br><br>
      <input type="submit" value="Pay now!"><br>
    </form>
  </body>
</html>
