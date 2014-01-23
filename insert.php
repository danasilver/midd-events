<?php
define('DB_SERVER', 'panther.cs.middlebury.edu');
define('DB_USERNAME', 'dsilver');
define('DB_PASSWORD', 'dsilver122193');
define('DB_DATABASE', 'dsilver_EventsCalendar');

$con = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE) or die("Could not connect");

$stmt = $con->prepare("INSERT INTO Events (title, description, photo_url, location, event_date, host)
  VALUES
  (?, ?, ?, ?, ?, ?)");

if (!$stmt)  {
  echo "First prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
}


if (!$stmt->bind_param('ssssss', $title, $desc, $photo_url, $location, $date, $host)) {
  echo "First binding failed: " . $stmt->errno . $stmt->error;
}


function insert_category($cat) {
	global $con;
	global $eventid;
	$cat = htmlspecialchars($cat);
	$stmt2 = $con->prepare("INSERT INTO categorized_in (event, category)
	VALUES
	(?, ?)");

	if (!$stmt2) {
	echo "Second prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
	}

	if (!$stmt2->bind_param('is', $eventid, $cat)) {
	echo "Second binding failed: " . $stmt2->errno . $stmt2->error;
	}

	if (!$stmt2->execute()) {
  	echo "Execute failed: " . $stmt2->errno . $stmt2->error;
	}

	$stmt2->close();
}


$title = htmlspecialchars($_POST['title']);
$desc = htmlspecialchars($_POST['description']);
$photo_url = htmlspecialchars($_POST['photo_url']);
$location = htmlspecialchars($_POST['location']);
$date = htmlspecialchars($_POST['event_date']);
$host = "chucknorris"; # change to current user
$categories = array();
$categories = $_POST['cats'];

// if (isset($_POST["cats"])) 
// {
//     print_r($_POST["cats"]); 
// }

if (!$stmt->execute()) {
  echo "Execute failed: " . $stmt->errno . $stmt->error;
}
$eventid = $con->insert_id;
$stmt->close();

foreach ($categories as $category) {
	insert_category($category);
}


header('Location: ' . 'event.php?event=' . $eventid);
die();

$con->close();
?>