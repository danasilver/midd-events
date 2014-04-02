<?php
session_start();
define('DB_SERVER', 'panther.cs.middlebury.edu');
define('DB_USERNAME', 'dsilver');
define('DB_PASSWORD', 'dsilver122193');
define('DB_DATABASE', 'dsilver_EventsCalendar');

$con = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE) or die("Could not connect.");

// Get all events
$events_results = mysqli_query($con, "SELECT *
                                      FROM Events
                                      WHERE end_date >= now() 
                                      AND flagged = '0'
                                      ORDER BY end_date ASC");

$events_array = array();
while ($row = mysqli_fetch_array($events_results, MYSQLI_ASSOC)) {
  $events_array[] = $row;
}

// Get all events with photos
$events_with_photos = array();
foreach ($events_array as $event) {
  if ($event['photo_url'] != '') {
    $events_with_photos[] = $event;
  }
}

// Finds the event with the most attendees and makes it the featured event
$max = 0;
$max_event = $events_with_photos[0];
$num_events = count($events_with_photos);
$index = 0;

for ($i = 0; $i < $num_events; $i++) {
  $current = $events_with_photos[$i];
  $cid = $current['id'];
  $max_attend_query = mysqli_query($con, "SELECT COUNT(user) FROM attend WHERE event = $cid");
  $max_attend = mysqli_fetch_array($max_attend_query);
  $max_attend = $max_attend[0];

  if ($max_attend >= $max) {
    $max = $max_attend;
    $max_event = $current;
    $index = $i;
  }
}
$first_element = $max_event;

//Removes the featured event from $events_with_photos
unset($events_with_photos[$index]);



?>
<!DOCTYPE html>
<html>
<?php
$title = "Midd Events";

include "templates/includes/head.php"
?>
<body>
<?php include "templates/includes/navbar.php" ?>
<div class="jumbotron">
      <div class="container">
        <div class="row">
          <div class="col-lg-4 col-md-4 col-sm-4">
            <a href="event.php?event=<?php echo $first_element['id'] ?>"> 
              <img width="100%" height="100%" src="<?php echo $first_element['photo_url'] ?>">
            </a>
          </div>
          <div class="col-lg-5 col-md-5 col-sm-5">
            <a href="event.php?event=<?php echo $first_element['id'] ?>"> 
              <h2><?php echo $first_element['title'] ?></h2>
            </a>
            <h4 class=""><?php echo date('F j, Y \a\t g:i a', strtotime($first_element['event_date'])); ?></h4>
            <h4 class=""><?php echo $first_element['location'] ?></h4>
            <h4 class=""><?php echo $max ?> attendees</h4>
            <h4>Created by: <?php echo $first_element['host'] ?></h4>
            <p><?php echo $first_element['description'] ?></p>
          </div>
        </div>
      </div>
</div>

<div class="container">
  <div class="row">
    <?php foreach ($events_with_photos as $thumbnail) { ?>
  <div class="col-sm-3">
    <div class="thumbnail thumbnail-home">
      <div class="home-image-wrapper">
        <a href="event.php?event=<?php echo $thumbnail['id'] ?>"> 
          <img src="<?php echo $thumbnail['photo_url'] ?>" alt="<?php echo $thumbnail['title'] ?>">
        </a>
      </div>
      <div class="caption">
          <a href="event.php?event=<?php echo $thumbnail['id'] ?>"> 
            <h3><?php echo $thumbnail['title']?> </h3> 
          </a>
            <h4 class=""><?php echo $thumbnail['location'] ?></h4>
            <h5 class=""><?php echo date('F j, Y \a\t g:i a', strtotime($thumbnail['event_date'])); ?></h5>
            <p> <?php 
            echo substr($thumbnail['description'], 0, 250);
            if (strlen($thumbnail['description']) > 250){
            echo '...';
            };
        ?></p>
      </div>
    </div>
  </div>
  <?php
  }
  ?>
  </div>
</div>
<?php include 'templates/includes/scripts.php' ?>
</body>
</html>