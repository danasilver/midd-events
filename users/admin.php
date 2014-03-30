<?php
session_start();
define ('DB_SERVER', 'panther.cs.middlebury.edu');
define ('DB_USERNAME', 'dsilver');
define ('DB_PASSWORD', 'dsilver122193');
define ('DB_DATABASE', 'dsilver_EventsCalendar');

$con = mysqli_connect (DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE) or die ("Could not connect");

$con->close();

?>

<!DOCTYPE html>
<html>

<?php
$title = "Admin";
$static_prefix = "../";
include "../templates/includes/head.php";
?>

<body>
<?php 
$index_prefix = "../";
$in_users = true;
// need to validate if the user is an admin
include '../templates/includes/navbar.php'; 
?>


<div class="container">
	<h2>Admin Page</h2>
	<h3>Flagged Events</h3>

</div>
<?php include '../templates/includes/scripts.php'?>
</body>
</html>