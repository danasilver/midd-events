<?php 
session_start();

define('DB_SERVER', 'panther.cs.middlebury.edu');
define('DB_USERNAME', 'dsilver');
define('DB_PASSWORD', 'dsilver122193');
define('DB_DATABASE', 'dsilver_EventsCalendar');

$con = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE) or die("Could not connect.");

?>

<!DOCTYPE html>
<html>
<?php
$title = "Error: 404";
include "templates/includes/head.php"
?>
<body>
<?php include "templates/includes/navbar.php" ?>
<div class="container">
  <h2>404: Oops, we couldn't find that!</h2>
</div>
<?php include 'templates/includes/scripts.php' ?>
</body>
</html>