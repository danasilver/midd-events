<?php
define('DB_SERVER', 'panther.cs.middlebury.edu');
define('DB_USERNAME', 'dsilver');
define('DB_PASSWORD', 'dsilver122193');
define('DB_DATABASE', 'dsilver_EventsCalendar');

$con = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE) or die("Could not connect.");

$events_results = mysqli_query($con, "SELECT * FROM Events");

$events_array = array();
while ($row = mysqli_fetch_array($events_results, MYSQLI_ASSOC)) {
  $events_array[] = $row;
}

mysqli_close($con);

?>
<!DOCTYPE html>
<html>
<?php
$title = "Midd Events";
include "templates/includes/head.php"
?>
<body>
<div class="container">
  <h2>Welcome to Midd Events</h2>
  <p>
  <form role="form" action="search.php" method="GET">
    <div class="row">
      <div class="form-group col-lg-5 col-md-5 col-sm-5">
        <input name="event" type="search" class="form-control" id="search" placeholder="Search events">
      </div>
    </div>
  </form>
  </p>

  <p>
    <a href="new.php" class="btn btn-primary">Create an event</a>
  </p>
  <h3>Recent Events</h3>
  <ul>
  <?php
  foreach ($events_array as $event) {
    echo "<li><a href='event.php?event=" . $event['id'] . "'>"  . $event['title'] . "</a></li>";
  }
  ?>
  </ul>
</div>
</body>
</html>