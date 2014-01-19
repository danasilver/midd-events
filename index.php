<?php
define('DB_SERVER', 'panther.cs.middlebury.edu');
define('DB_USERNAME', 'dsilver');
define('DB_PASSWORD', 'dsilver122193');
define('DB_DATABASE', 'dsilver_EventsCalendar');

$con = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE) or die("Could not connect.");

$events_query = mysqli_query($con, "SELECT * FROM Events");
$events = mysqli_fetch_all($events_query);

mysql_close($con);
?>
<!DOCTYPE html>
<html>
<head>
<title>Midd Events</title>
<link href="static/bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container">
  <h2>Welcome to Midd Events</h2>
  <h3>Recent Events</h3>
  <ul>
  <?php
    foreach ($events as $event) {
      echo "<li>".$event -> title."</li>";
    }
  ?>
  </ul>
</div>
</body>
</html>