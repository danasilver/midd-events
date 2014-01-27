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

function clean_data($data)
{
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
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


$title = clean_data($_POST['title']);
$desc = clean_data($_POST['description']);
$photo_url = clean_data($_POST['photo_url']);
$location = clean_data($_POST['location']);
$date = clean_data($_POST['event_date']);
$host = "chucknorris"; # change to current user
$categories = array();
$categories = $_POST['cats'];
$org = htmlspecialchars($_POST['orgs']);




if (!$stmt->execute()) {
  echo "Execute failed: " . $stmt->errno . $stmt->error;
}
$eventid = $con->insert_id;
$stmt->close();

$stmt3 = $con->prepare("INSERT INTO organizer (org, event)
VALUES
(?, ?)");

if (!$stmt3)  {
  	echo "Third prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
}
if (!$stmt3->bind_param('si', $org, $eventid)) {
	echo "Third binding failed: " . $stmt3->errno . $stmt3->error;
}
if (!$stmt3->execute()) {
  	echo "Execute failed: " . $stmt3->errno . $stmt3->error;
	}

$stmt3->close();

foreach ($categories as $category) {
	insert_category($category);
}





header('Location: ' . 'event.php?event=' . $eventid);
die();

$con->close();
?>