<?php
define('DB_SERVER', 'panther.cs.middlebury.edu');
define('DB_USERNAME', 'dsilver');
define('DB_PASSWORD', 'dsilver122193');
define('DB_DATABASE', 'dsilver_EventsCalendar');

$con = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE) or die("Could not connect.");

// Get all events
$events_results = mysqli_query($con, "SELECT *
                                      FROM Events
                                      WHERE event_date >= now()
                                      ORDER BY event_date ASC");

$events_array = array();
while ($row = mysqli_fetch_array($events_results, MYSQLI_ASSOC)) {
  $events_array[] = $row;
}

//Get all organizations
$org_results = mysqli_query($con, "SELECT name FROM Organizations ORDER BY name");
$orgs = array();
while ($row = mysqli_fetch_array($org_results, MYSQLI_ASSOC)) {
  $orgs[] = $row['name'];
}

// Get all events with photos
$events_with_photos = array();
foreach ($events_array as $event) {
  if ($event['photo_url'] != '') {
    $events_with_photos[] = $event;
  }
}

// Get all categories
$cat_results = mysqli_query($con, "SELECT name FROM Categories ORDER BY name");
$cats = array();
while ($row = mysqli_fetch_array($cat_results, MYSQLI_ASSOC)) {
  $cats[] = $row['name'];
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
  <div class="row index-header">
    <div class="col-lg-6 col-md-6">
      <span class="h2">Welcome to Midd Events</span>
    </div>

    <div class="row">
      <div class="col-lg-4 col-md-4">
        <form role="form" class="" action="search.php" method="GET">
          <div class="form-group">
            <div class="input-group">
              <input name="q" type="text" class="form-control" id="search" placeholder="Search events" autocomplete="off">
              <span class="input-group-btn">
                <button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-search"></span></button>
            </span>
          </div>
        </div>
      </div>  

      <div class="col-lg-2 col-md-2">
      <a href="new.php" class="btn btn-primary">Create an event</a>
    </div>

    </div>

    <div class="form-group">
      <div class="row">
        <label class="col-sm-1 col-sm-offset-6 control-label" for="orgs">Organization</label>
        <div class="col-sm-3">
          <select name="o[]" multiple class="form-control">
          <?php foreach ($orgs as $org) { ?>
            <option><?php echo $org ?></option>
          <?php } ?>
          </select>
        </div>
      </div>
    </div>

    <div class="form-group">
      <div class="row">
        <label class="col-sm-1 col-sm-offset-6 control-label" for="orgs">Categories</label>
        <div class="col-sm-3">
          <select name="c[]" multiple class="form-control">
          <?php foreach ($cats as $cat) { ?>
            <option><?php echo $cat ?></option>
          <?php } ?>
          </select>
        </div>
      </div>
    </div>

    </form>

  </div>

  <div id="events-carousel" class="carousel slide hidden-sm hidden-xs">
    <ol class="carousel-indicators">
    <?php
    foreach (array_slice($events_with_photos, 0, 5) as $i=>$event) {
    ?>
      <li data-target="#events-carousel" data-slide-to="<?php echo $i; ?>" class="<?php if ($i == 0) { echo " active"; }; ?>"></li>
    <?php
    }
    ?>
    </ol>

    <div class="carousel-inner">
      <?php
      foreach (array_slice($events_with_photos, 0, 5) as $i=>$event) {
      ?>
      <div class="item row<?php if ($i == 0) { echo " active"; }; ?>">
        <div class="carousel-img-wrapper col-lg-6 col-md-6">
          <img src="<?php echo $event['photo_url'] ?>" alt="<?php echo $event['title'] ?>">
          <div class="img-overlay"></div>
        </div>
        <div class="carousel-description col-lg-6 col-md-6">
          <h1><?php echo $event['title'] ?></h1>
          <h2><?php echo date('F j, Y \a\t g:i a', strtotime($event['event_date'])) ?></h2>
          <h2><?php echo $event['location'] ?></h2>
        </div>
      </div>
      <?php
      }
      ?>
    </div>
  </div>
  <script>
  $("#events-carousel").carousel({
    interval: 10000
  });
  </script>

  <div class="row">
    <div class="col-lg-4 col-md-4">
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
    <div class="col-lg-4 col-md-4 col-md-offset-4 col-md-offset-4">
      <h3>Categories</h3>
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
</body>
</html>