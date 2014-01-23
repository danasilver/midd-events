<?php
define ('DB_SERVER', 'panther.cs.middlebury.edu');
define ('DB_USERNAME', 'dsilver');
define ('DB_PASSWORD', 'dsilver122193');
define ('DB_DATABASE', 'dsilver_EventsCalendar');

$event_id = htmlspecialchars($_GET["event"]);
$con = mysqli_connect (DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE) or die ("Could not connect");

$result = mysqli_query($con, "SELECT * FROM Events WHERE $event_id = id");
$event = mysqli_fetch_array($result);

$cat_results = mysqli_query($con, "SELECT category FROM categorized_in WHERE event = $event_id
ORDER BY category");
$cats = array();
while ($row = mysqli_fetch_array($cat_results, MYSQLI_ASSOC)) {
  $cats[] = $row['category'];
}
mysqli_close($con);

?>

<!DOCTYPE html>
<html>

<?php
$title = $event['title'];
include "templates/includes/head.php";
?>

<body>

<div class="container">
  <h2><?php echo $event['title'] ?></h2>
  <h4 class="hidden-lg hidden-md hidden-sm"><?php echo date('F j, Y \a\t g:i a', strtotime($event['event_date'])); ?></h4>
  <h4 class="hidden-lg hidden-md hidden-sm"><?php echo $event['location'] ?></h4>
  <a href="index.php" class="return-home">Back to search</a>
  <div class="row">
    <div class="col-lg-4 col-md-4 col-sm-4">
      <img width="100%" height="100%" src="<?php echo $event['photo_url'] ?>">
    </div>
    <div class="col-lg-8 col-md-8 col-sm-8">
      <h4 class="hidden-xs"><?php echo date('F j, Y \a\t g:i a', strtotime($event['event_date'])); ?></h4>
      <h4 class="hidden-xs"><?php echo $event['location'] ?></h4>
      <h4>Created by: <?php echo $event['host'] ?></h4>
      <p><?php echo $event['description'] ?></p>
      <h4>Categories</h4>
      <ul>
      <?php foreach ($cats as $cat) { ?>
      <li>
        <a href="search.php?q=<?php echo $cat ?>">
          <?php echo $cat ?>
        </a>
      </li>
      <?php } ?>
      </ul>

    </div>
  </div>
</div>
<?php

?>

</body>
</html>