<?php
session_start();

define ('DB_SERVER', 'panther.cs.middlebury.edu');
define ('DB_USERNAME', 'dsilver');
define ('DB_PASSWORD', 'dsilver122193');
define ('DB_DATABASE', 'dsilver_EventsCalendar');

$con = mysqli_connect (DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE) or die ("Could not connect");

$uname = $_SESSION["username"];

// Redirect user if they are not an admin
 $is_admin = false;
 $isadmin_query = mysqli_query($con, "SELECT is_admin FROM Users WHERE username = '$uname'");
 $isadmin_result = mysqli_fetch_array($isadmin_query);
 if ($isadmin_result['is_admin'] == 0){
   $is_admin = false;
 } else {
   $is_admin = true;
 }
if (!$is_admin){
  header('Location: ../index.php');
  die();
}


$flagged_results = mysqli_query($con, "SELECT *
                                      FROM Events
                                      WHERE flagged = '1'
                                      AND end_date >= now()
                                      ORDER BY end_date ASC");

$flagged_array = array();
while ($row = mysqli_fetch_array($flagged_results, MYSQLI_ASSOC)) {
  $flagged_array[] = $row;
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
  $uname = $_SESSION["username"];
  $flag_action = $_POST["flag_action"];
    if ($flag_action == "unflag") {
      mysqli_query($con, "UPDATE Events SET flagged = '0', flagged_by = 'NULL' WHERE id = '$event_id'");
    }
}

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

	<h4><?php if (empty($flagged_array)) { echo "There are no flagged events"; } ?></h4>
      <ul class="list-unstyled">
      <?php foreach ($flagged_array as $f_event) { ?>
      <li>
        <a href="../event.php?event=<?php echo $f_event["id"] ?>">
          <?php echo $f_event["title"] ?>
        </a>
      </li>
      <?php } ?>
     
      </ul>

</div>
<?php include '../templates/includes/scripts.php'?>
</body>
</html>