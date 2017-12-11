<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>DOTA 2 MERCH</title>
    <?php
    require_once("navi.php");
     ?>
  </head>
  <body>
    <div>
      <h2>My Information</h2>
      <textarea><?php
      
       $_GET["p"] = "getAccountInfo";
       require("handler.php");
       unset($_GET["p"]);
       ?>
      </textarea>
    </div>
    <div>
      <h2>My Orders</h2>
      <textarea><?php 
      $_GET["p"] = "getOrders";
      db();
      unset($_GET["p"]);
      ?>
      </textarea>
    </div>
  </body>
</html>
