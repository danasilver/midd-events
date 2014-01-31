<?php
session_start();
define ('DB_SERVER', 'panther.cs.middlebury.edu');
define ('DB_USERNAME', 'dsilver');
define ('DB_PASSWORD', 'dsilver122193');
define ('DB_DATABASE', 'dsilver_EventsCalendar');

$con = mysqli_connect (DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE) or die ("Could not connect");

$event_id = htmlspecialchars($_GET["event"]);

// If attend/unattend post request
if ($_SERVER['REQUEST_METHOD'] == "POST") {
  $uname = $_SESSION["username"];
  $rsvp_action = $_POST["rsvp_action"];

  if ($rsvp_action == "attend") {
    mysqli_query($con, "INSERT INTO attend (user, event) VALUES ('$uname', $event_id)");
    header("Location: event.php?event=" . $event_id);
  }
  else if ($rsvp_action == "unattend") {
    mysqli_query($con, "DELETE FROM attend WHERE user = '$uname' AND event = '$event_id' LIMIT 1");
    header("Location: event.php?event=" . $event_id);
  }
}

$attend_count_query = mysqli_query($con, "SELECT COUNT(user) FROM attend WHERE event = $event_id");
$attend_count = mysqli_fetch_array($attend_count_query);
$attend_count = $attend_count[0];

$user_attending = false;
if (isset($_SESSION["username"])) {
  $uname = $_SESSION['username'];
  $user_attending_query = mysqli_query($con, "SELECT user FROM attend WHERE event = '$event_id' AND user = '$uname'");
  $user_attending_result = mysqli_fetch_array($user_attending_query);
  if (!empty($user_attending_result)) {
    $user_attending = true;
  }
}

$result = mysqli_query($con, "SELECT * FROM Events WHERE $event_id = id");
$event = array();

if (mysqli_num_rows($result) > 0) {
    $event = mysqli_fetch_array($result, MYSQLI_ASSOC);
} else {
    // $event_result is false, redirect to 404
    header('Location: 404.php');
    die();
}

$cat_results = mysqli_query($con, "SELECT category FROM categorized_in WHERE event = $event_id
ORDER BY category");
$cates = array();
while ($row = mysqli_fetch_array($cat_results, MYSQLI_ASSOC)) {
  $cates[] = $row['category'];
}
$event_result = mysqli_query($con, "SELECT org FROM organizer WHERE event = $event_id");
$e_org = mysqli_fetch_array($event_result);


for ($i = 0; $i < count($cates); $i++) {
  if ($i == 0) {
    $related_query = "SELECT * 
                      FROM Events E, categorized_in C 
                      WHERE E.id = C.event 
                      AND C.category = '$cates[$i]' 
                      AND E.event_date >= now() 
                      AND E.id <> $event_id";
  } else {
    $related_query = $related_query . " UNION 
                      SELECT * 
                      FROM Events E, categorized_in C 
                      WHERE E.id = C.event 
                      AND C.category = '$cates[$i]' 
                      AND E.event_date >= now() 
                      AND E.id <> $event_id";
  }
}

$related_events = array();
$related = mysqli_query($con, $related_query);
while ($row = mysqli_fetch_array($related, MYSQLI_ASSOC)) {
$related_events[] = $row;
  
}

$related_with_photos = array();
foreach ($related_events as $revent) {
  if ($revent['photo_url'] != '' && $revent['id']!= $event_id) {
    $related_with_photos[] = $revent;
  }
}
$related_with_photos = array_slice($related_with_photos, 0, 5);

?>

<!DOCTYPE html>
<html>

<?php
$title = $event['title'];

include "templates/includes/head.php"
?>

<body>
<?php include 'templates/includes/navbar.php' ?>
<div class="container">
  <form method="POST">
  <h2>
    <?php echo $event['title'] ?>
    <?php if (isset($_SESSION["username"])) { ?>
      <?php if (!$user_attending) { ?>
        <input type="hidden" name="rsvp_action" value="attend">
        <button type="submit" class="btn btn-primary pull-right">Click to Attend</button>
      <?php } else { ?>
        <input type="hidden" name="rsvp_action" value="unattend">
        <button type="submit" class="btn btn-default pull-right">Click to Unattend</button>
      <?php } ?>
      <?php if ($_SESSION["username"] == $event["host"]) { ?>
        <a class="btn btn-link pull-right" href="edit.php?event=<?php echo $event['id']; ?>">Edit event</a>
      <?php } ?>
    <?php } else { ?>
      <a class="btn btn-default pull-right" href="users/login.php">Login to RSVP</a>
    <?php } ?>
  </h2>
  </form>
  <h4 class="hidden-lg hidden-md hidden-sm">Starts <?php echo date('F j, Y \a\t g:i a', strtotime($event['event_date'])); ?></h4>
  <h4 class="hidden-lg hidden-md hidden-sm"><?php echo $event['location']; ?></h4></h4>
  <div class="row">
    <div class="col-lg-4 col-md-4 col-sm-4">
      <img width="100%" height="100%" src="<?php echo $event['photo_url'] ?>">
    </div>
    <div class="col-lg-8 col-md-8 col-sm-8">
      <h4 class="hidden-xs">Starts <?php echo date('F j, Y \a\t g:i a', strtotime($event['event_date'])); ?></h4>
      <h4 class="hidden-xs">Ends <?php echo date('F j, Y \a\t g:i a', strtotime($event['end_date'])); ?></h4>
      <h4 class="hidden-xs"><?php echo $event['location'] ?></h4>
      <h4><?php echo $attend_count; ?> attendees</h4>
      <h4>Created by: <?php echo $event['host'] ?></h4>
      <h4>Organized by: <?php echo $e_org['org'] ?></h4>
      <p><?php echo $event['description'] ?></p>

      <h4><?php if (!empty($cates)) { echo "Categories"; } ?></h4>
      <ul class="list-unstyled list-inline">
      <?php foreach ($cates as $cat) { ?>
      <li>
        <a href="search.php?c%5B%5D=<?php echo $cat ?>">
          <?php echo $cat ?>
        </a>
      </li>
      <?php } ?>
      </ul>
    </div>
  </div>

  <!-- Thumbnails for related events -->
  <h3><?php if (!empty($related_with_photos)) { echo "Related Events"; } ?></h3>

  <div class="row">
    <?php foreach ($related_with_photos as $thumbnail) { ?>
  <div class="col-sm-3">
    <div class="thumbnail thumbnail-home">
      <div class="home-image-wrapper">
        <a href="event.php?event=<?php echo $thumbnail['id'] ?>"> 
          <img src="<?php echo $thumbnail['photo_url'] ?>" alt="<?php echo $thumbnail['title'] ?>">
        </a>
      </div>
      <div class="caption">
          <a href="event.php?event=<?php echo $thumbnail['id'] ?>"> 
            <h3><?php echo $thumbnail['title']?> </h3> </a>
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

</body>
<?php include 'templates/includes/scripts.php' ?>
</html>
<?php
mysqli_close($con);
?>
