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

$result = mysqli_query("SELECT * FROM Events WHERE $_GET["Eventid"] = id");
$event = mysqli_fetch_array($result);

/*Event name: echo $event -> title;
Description: echo $event -> description;
Where is it? echo $event -> location;
When? echo $event -> event_date;
Who is the host? echo $event -> host;
$cats = mysqli_query("SELECT i.category FROM categorized_in i, Events e WHERE $_GET["Eventid"] = e.id");

while ($row = mysqli_fetch_field($cats))
{
	echo $row['category'] . " "; 
	echo "<br>";
}
*/
mysqli_close($con);
?>

</body>
</html>