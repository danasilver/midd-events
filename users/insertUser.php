<?php
define('DB_SERVER', 'panther.cs.middlebury.edu');
define('DB_USERNAME', 'dsilver');
define('DB_PASSWORD', 'dsilver122193');
define('DB_DATABASE', 'dsilver_EventsCalendar');

$con = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE) or die("Could not connect");

$stmt = $con->prepare("INSERT INTO Users (username, full_name, joined, password, email)
VALUES
(?, ?, ?, ?, ?)");

if (!$stmt) {
	echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
}

if (!$stmt->bind_param('sssss', $username, $full_name, $joined, $password, $email)) {
	echo "Binding failed: " . $stmt->errno . $stmt->error;
}
$username = htmlspecialchars($_POST['username']);
$full_name = htmlspecialchars($_POST['full_name']);
$joined = htmlspecialchars(date('Y/m/d h:i:s', time()));
$password = htmlspecialchars($_POST['password']);
$email = htmlspecialchars($_POST['email'] . '@middlebury.edu');

if (!$stmt->execute()) {
	echo "Execute failed: " . $stmt->errno . $stmt->error;
}

$stmt->close();

header('Location: ' . '../index.php');
die();

$con->close();
?>