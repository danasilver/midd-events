<!DOCTYPE html>
<html>
<head>
	This is an Event
</head>


<body>
<?php

//set up connection to database
define ('DB_SERVER', 'panther.cs.middlebury.edu');
define ('DB_USERNAME', 'dsilver');
define ('DB_PASSWORD', 'dsilver122193');
define ('DB_DATABASE', 'dsilver_EventsCalendar');

$con = mysqli_connect (DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE) or die ("Could not connect");

$result = mysql_query("SELECT * FROM Events WHERE $_GET["Eventid"] = id");
$event = mysql_fetch_object($result);

echo $event -> title;
echo $event -> description;
echo $event -> location;
echo $event -> event_date;
echo $event -> host;
$cats = mysql_query("SELECT i.category FROM categorized_in i, Events e WHERE $_GET["Eventid"] = e.id");

$catset = mysql_fetch_field($cats);

echo $catset -> category;

mysql_close($con)
?>

</body>
</html>