<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>DOTA 2 MERCH</title>
   <?php require_once("navi.php"); ?>
  </head>
  <body>
    <form action = "handler.php?p=register" method = "post">
      <legend>Register now!</legend><br>
      <input type="text" name="Name" value="Name"><br><br>
      <input type="text" name="Address" value="Address"><br><br>
      <input type="text" name="Password" value="Password"><br><br>
      <input type="text" name="Password1" value="Password"><br><br>
	  <select name="region">
	<option value="EU">Europe</option>
    <option value="NA">North-America</option>
    <option value="AS">Asia</option>
    </select>
	<br><br>
      <input type="submit" value="Register"><br>
    </form>
  </body>
</html>
