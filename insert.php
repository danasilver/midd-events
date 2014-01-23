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
  echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
}

if (!$stmt->bind_param('ssssss', $title, $desc, $photo_url, $location, $date, $host)) {
  echo "Binding failed: " . $stmt->errno . $stmt->error;
}

$title = htmlspecialchars($_POST['title']);
$desc = htmlspecialchars($_POST['description']);
$photo_url = htmlspecialchars($_POST['photo_url']);
$location = htmlspecialchars($_POST['location']);
$date = htmlspecialchars($_POST['event_date']);
$host = "chucknorris"; # change to current user

// if (isset($_POST["cats"])) 
// {
//     print_r($_POST["cats"]); 
// }

if (!$stmt->execute()) {
  echo "Execute failed: " . $stmt->errno . $stmt->error;
}

$stmt->close();

header('Location: ' . 'event.php?event=' . $con->insert_id);
die();

$con->close();
?>