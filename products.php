<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>DOTA 2 MERCH</title>
   <?php require_once("navi.php"); ?>
  </head>
  <body>

    
    <div style="display: inline-block;">
      <img src="dota2muki.png" alt="mug" style="width:300px;height:300px;">
      <ul>
        <li>Nice mug for coffee</li>
        <li>Picture of Rhasta, the Shadow Shaman</li>
        <li>Price: 10 €</li>
      </ul>
      <?php if(isset($_SESSION["loggedUser"])) { ?>
      <form action="order.php?product=mug" method="post">
      <select name="DropDown" >
        <option value="1">1</option>
        <option value="2">2</option>
        <option value="3">3</option>
        <option value="4">4</option>
        <option value="5">5</option>
        <option value="6">6</option>
        <option value="7">7</option>
        <option value="8">8</option>
        <option value="9">9</option>
        <option value="10">10</option>
      </select>
      <input type="submit" value="Buy now"><br><br>
    </form><?php } else { ?>
    <p>Register or log in to buy products!</p><?php } ?>
    </div>
    
    <div style="display: inline-block;">
      <img src="dota2shirt.png" alt="shirt" style="width:300px;height:300px;">
      <ul>
        <li>Cool shirt for summer holidays</li>
        <li>Show off to those League of legends fan boys</li>
        <li>Price: 20 €</li>
      </ul>
      <?php if(isset($_SESSION["loggedUser"])) { ?>
      <form action="order.php?product=shirt" method="post">
      <select name="DropDown">
        <option value="1">1</option>
        <option value="2">2</option>
        <option value="3">3</option>
        <option value="4">4</option>
        <option value="5">5</option>
        <option value="6">6</option>
        <option value="7">7</option>
        <option value="8">8</option>
        <option value="9">9</option>
        <option value="10">10</option>
      </select>
      <input type="submit" value="Buy now"><br>
    </form><?php } else { ?>
    <p>Register or log in to buy products!</p><?php } ?>
    </div>

  </body>
</html>
