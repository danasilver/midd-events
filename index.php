<?php
define('DB_SERVER', 'panther.cs.middlebury.edu');
define('DB_USERNAME', 'dsilver');
define('DB_PASSWORD', 'dsilver122193');
define('DB_DATABASE', 'dsilver_EventsCalendar');

$con = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE) or die("Could not connect.");

$events_results = mysqli_query($con, "SELECT *
                                      FROM Events
                                      WHERE event_date >= now()
                                      ORDER BY event_date ASC");

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
  <form role="form" class="form-search" action="search.php" method="GET">
    <div class="row">
      <div class="form-group col-lg-4 col-md-4 col-sm-4">
        <div class="input-group">
          <input name="q" type="text" class="form-control" id="search" placeholder="Search events">
          <span class="input-group-btn">
            <button type="submit" class="btn btn-default">Search</button>
          </span>
        </div>
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