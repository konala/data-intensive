<?php session_start(); ?>
<nav>
  <ul>
    <li>
      <a href="index2.php">What's New</a>
    </li>
    <li>
      <a href="products.php">Products</a>
    </li>
    <?php if(isset($_SESSION["loggedUser"])) { ?>
    <li>
      <a href="account.php">My Account</a>
    </li> <?php } if (!isset($_SESSION["loggedUser"])) { ?>
    <li>
      <a href="register.php">Register</a>
    </li>
    <li>
      <a href="login.php">Log in</a>
    </li>
    <?php } ?>
  </ul>
  </nav>
  <?php
    if(isset($_SESSION["loggedUser"])) {
  ?>
  <div>
    <p>Logged in as: <?php print $_SESSION["loggedUser"]; ?> Region: <?php print $_SESSION["loggedRegion"] ?> </p>
    <a href="handler.php?p=logout">Log out</a>
  </div>
  <?php } ?>

