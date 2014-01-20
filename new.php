<?php
define('DB_SERVER', 'panther.cs.middlebury.edu');
define('DB_USERNAME', 'dsilver');
define('DB_PASSWORD', 'dsilver122193');
define('DB_DATABASE', 'dsilver_EventsCalendar');

$con = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE) or die("Could not connect.");

$org_results = mysqli_query($con, "SELECT name FROM Organizations ORDER BY name");
$orgs = array();
while ($row = mysqli_fetch_array($org_results, MYSQLI_ASSOC)) {
  $orgs[] = $row['name'];
}

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
$title = "New Event";
include "templates/includes/head.php"
?>
<body>
<div class="container">
  <h2>Create a new event</h2>
  <a href="index.php" class="btn btn-link" tabindex="-1">Back to search</a>
  <form action="insert.php" class="form-horizontal col-sm-12 event-form" role="form" method="POST">
    <!-- Title -->
    <div class="form-group">
      <div class="row">
        <label class="col-sm-2 control-label" for="title">Title</label>
        <div class="col-sm-4">
          <input type="text" class="form-control" name="title" id="title" maxlength="30">
        </div>
      </div>
    </div>

    <!-- Location -->
    <div class="form-group">
      <div class="row">
        <label class="col-sm-2 control-label" for="location">Location</label>
        <div class="col-sm-4">
          <input type="text" name="location" id="location" class="form-control" maxlength="100">
        </div>
      </div>
    </div>

    <!-- Organization -->
    <div class="form-group">
      <div class="row">
        <label class="col-sm-2 control-label" for="orgs">Organization</label>
        <div class="col-sm-4">
          <select name="orgs" class="form-control">
          <?php foreach ($orgs as $org) { ?>
            <option><?php echo $org ?></option>
          <?php } ?>
          </select>
        </div>
      </div>
    </div>

    <!-- Categories -->
    <div class="form-group form-group-category">
      <div class="row">
        <label class="col-sm-2 control-label" for="cats">Categories</label>
        <div class="col-sm-4" data-toggle="buttons">
          <?php foreach ($cats as $cat) { ?>
            <label for="cats" class="btn btn-default btn-category">
              <input name="cats" type="checkbox" id="cats" value="<?php echo $cat ?>">
              <?php echo $cat ?>
            </label>
          <?php } ?>
        </div>
      </div>
    </div>

    <!-- Photo URL -->
    <div class="form-group">
      <div class="row">
        <label class="col-sm-2 control-label" for="photo">Photo URL</label>
        <div class="col-sm-4">
          <input type="url" name="photo_url" id="photo" class="form-control" maxlength="100">
        </div>
      </div>
    </div>

    <!-- Date -->
    <div class="form-group">
      <div class="row">
        <label class="col-sm-2 control-label" for="date">Date</label>
        <div class="col-sm-4">
          <input type="date" name="event_date" id="date" class="form-control" maxlength="30">
        </div>
      </div>
    </div>

    <!-- Description -->
    <div class="form-group">
      <div class="row">
        <label class="col-sm-2 control-label" for="description">Description</label>
        <div class="col-sm-6">
          <textarea id="description" name="description" class="form-control" rows="5"></textarea>
        </div>
      </div>
    </div>

    <div class="form-group">
      <div class="row">
        <div class="col-sm-4 col-sm-offset-2">
          <input class="btn btn-primary" type="submit" value="Create event">
        </div>
      </div>
    </div>
  </form>
</div>

</body>
</html>