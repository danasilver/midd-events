<?php
define('DB_SERVER', 'panther.cs.middlebury.edu');
define('DB_USERNAME', 'dsilver');
define('DB_PASSWORD', 'dsilver122193');
define('DB_DATABASE', 'dsilver_EventsCalendar');

$con = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE) or die("Could not connect.");

$events_results = mysqli_query($con, "SELECT * FROM Events ORDER BY event_date DESC");

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
        <input name="q" type="text" class="form-control" id="search" placeholder="Search events">
        
      </div>

      <div class="col-lg-5 col-md-5 col-sm-5">  
          <button type="submit" class="btn btn-primary">Search</button>
      </div>

    </div>
  </form>
  </p>
  <p>
    <a href="new.php" class="btn btn-primary">Create an event</a>
  </p>
  <h3>Upcoming Events</h3>
  <ul>
  <?php
  foreach ($events_array as $event) {
  ?>
  <li>
    <a href="event.php?event=<?php echo $event['id'] ?>">
      <?php $phpdate = strtotime($event['event_date']) ?>

      <strong><?php echo date('M j, Y', $phpdate) ?></strong>
      &nbsp;<?php echo $event['title'] ?>
    </a>
  </li>
  <?php
  }
  ?>
  </ul>
</div>
</body>
</html>