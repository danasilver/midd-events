<?php
define('DB_SERVER', 'panther.cs.middlebury.edu')
define('DB_USERNAME', 'dsilver')
define('DB_PASSWORD', 'dsilver122193')
define('DB_DATABASE', 'dsilver_EventsCalendar')

$con = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE) or die("Could not connect.");

$sql = "SELECT * FROM Events";

mysql_close($con)
?>
<!DOCTYPE html>
<html>
<head>
<title>Midd Events</title>
<link href="static/bootstrap/css/bootstrap.css" rel="stylesheet">
</head>
<body>
<h2>Welcome to Midd Events</h2>
<h3>Recent Events</h3>
<ul>
<?php
  foreach ($sql as $value) {
    echo "<li>".$value."</li>";
  }
?>
</ul>
</body>
</html>