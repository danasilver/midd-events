<?php
define('DB_SERVER', 'panther.cs.middlebury.edu');
define('DB_USERNAME', 'dsilver');
define('DB_PASSWORD', 'dsilver122193');
define('DB_DATABASE', 'dsilver_EventsCalendar');

$con = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE) or die("Could not connect");

$sql = "INSERT INTO Events (title, description, is_approved, photo_url, location, event_date, host)
VALUES
('$_POST[title]','$_POST[description]','1','$_POST[photo_url]','$_POST[location]','$_POST[event_date]', 'chucknorris')";

if (!mysqli_query($con, $sql)) {
  die('Error: ' . mysqli_error($con));
}
?>

<script>
  window.location.href = "event.php?event=" + <?php echo mysqli_insert_id($con) ?>
</script>

<?php
mysql_close($con);
?>