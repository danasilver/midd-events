<?php
session_start();
define('DB_SERVER', 'panther.cs.middlebury.edu');
define('DB_USERNAME', 'dsilver');
define('DB_PASSWORD', 'dsilver122193');
define('DB_DATABASE', 'dsilver_EventsCalendar');

$con = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE) or die("Could not connect.");

// Get organizations and categories for form
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

$errors = array();

// POST request validation
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  function clean_data($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
  }

  $titleVal = clean_data($_POST['title']);
  $desc = clean_data($_POST['description']);
  $photo_url = clean_data($_POST['photo_url']);
  $location = clean_data($_POST['location']);
  $date = clean_data($_POST['event_date']);
  $categories = array();
  $categories = $_POST['cats'];
  $org = clean_data($_POST["org"]);

  empty($titleVal) && $errors["title"] = "This field is required.";
  empty($desc) && $errors["desc"] = "This field is required.";
  empty($photo_url) && $errors["photo_url"] = "This field is required.";
  empty($location) && $errors["location"] = "This field is required.";
  empty($date) && $errors["date"] = "This field is required.";
  empty($categories) && $errors["categories"] = "This field is required.";

  if (empty($errors)) {
    $stmt = $con->prepare("INSERT INTO Events (title, description, photo_url, location, event_date, host)
      VALUES
      (?, ?, ?, ?, ?, ?)");

    if (!$stmt)  {
      echo "First prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }


    if (!$stmt->bind_param('ssssss', $title, $desc, $photo_url, $location, date("Y-m-d H:i:s", strtotime($date)), $host)) {
      echo "First binding failed: " . $stmt->errno . $stmt->error;
    }

    function insert_category($cat) {
      global $con;
      global $eventid;
      $cat = htmlspecialchars($cat);
      $stmt2 = $con->prepare("INSERT INTO categorized_in (event, category)
      VALUES
      (?, ?)");

      if (!$stmt2) {
        echo "Second prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
      }

      if (!$stmt2->bind_param('is', $eventid, $cat)) {
        echo "Second binding failed: " . $stmt2->errno . $stmt2->error;
      }

      if (!$stmt2->execute()) {
          echo "Execute failed: " . $stmt2->errno . $stmt2->error;
      }

      $stmt2->close();
    }

    $host = $_SESSION["username"];

    if (!$stmt->execute()) {
      echo "Execute failed: " . $stmt->errno . $stmt->error;
    }
    $eventid = $con->insert_id;
    $stmt->close();

    $org = htmlspecialchars($_POST["org"]);
    $stmt3 = $con->prepare("INSERT INTO organizer (org, event)
    VALUES
    (?, ?)");

    if (!$stmt3)  {
        echo "Third prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }
    if (!$stmt3->bind_param('si', $org, $eventid)) {
      echo "Third binding failed: " . $stmt3->errno . $stmt3->error;
    }
    if (!$stmt3->execute()) {
        echo "Execute failed: " . $stmt3->errno . $stmt3->error;
      }

    $stmt3->close();

    foreach ($categories as $category) {
      insert_category($category);
    }


    header('Location: ' . 'event.php?event=' . $eventid);
    die();
  }
}

$con->close();

?>

<!DOCTYPE html>
<html>
<?php
$title = "New Event";
include "templates/includes/head.php"
?>
<body>
<?php include "templates/includes/navbar.php" ?>
<div class="container">
  <h2>Create a new event</h2>
  <a href="index.php" class="btn btn-link" tabindex="-1">Back to search</a>
  <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" class="form-horizontal col-sm-12 event-form" role="form" method="POST">
    <!-- Title -->
    <div class="form-group<?php if (array_key_exists("title", $errors)) { echo " has-error"; } ?>">
      <div class="row">
        <label class="col-sm-2 control-label" for="title">Title</label>
        <div class="col-sm-4">
          <input type="text" class="form-control" name="title" id="title" maxlength="30" value="">
        </div>
      </div>
    </div>

    <!-- Location -->
    <div class="form-group<?php if (array_key_exists("location", $errors)) { echo " has-error"; } ?>">
      <div class="row">
        <label class="col-sm-2 control-label" for="location">Location</label>
        <div class="col-sm-4">
          <input type="text" name="location" id="location" class="form-control" maxlength="100" value="">
        </div>
      </div>
    </div>

    <!-- Organization -->
    <div class="form-group">
      <div class="row">
        <label class="col-sm-2 control-label" for="org">Organization</label>
        <div class="col-sm-4">
          <select id="newEventOrg" name="org">
          <?php foreach ($orgs as $org) { ?>
            <option><?php echo $org ?></option>
          <?php } ?>
          </select>
        </div>
      </div>
    </div>

    <!-- Categories -->
    <div class="form-group form-group-category <?php if (array_key_exists("categories", $errors)) { echo " has-error"; } ?>">
      <div class="row">
        <label class="col-sm-2 control-label" for="cats">Categories</label>
        <div class="col-sm-4">
          <select id="newEventCats" name="cats[]" multiple>
          <?php foreach ($cats as $cat) { ?>
            <option><?php echo $cat ?></option>
          <?php } ?>
          </select>
        </div>
      </div>
    </div>

    <!-- Date -->
    <div id="newEventDate" class="form-group<?php if (array_key_exists("date", $errors)) { echo " has-error"; } ?>">
      <div class="row">
        <label class="col-sm-2 control-label" for="date">Date</label>
        <div class="col-sm-4">
          <div class="input-group">
            <input data-format="MM/dd/yyyy HH:mm PP" type="text" name="event_date" id="date" class="form-control" maxlength="30" value="<?php echo $date;?>">
            <span class="input-group-btn">
              <button type="button" class="btn btn-default"><span class="glyphicon glyphicon-calendar"></span></button>
            </span>
          </div>
        </div>
      </div>
    </div>

    <!-- Photo URL -->
    <div id="newEventImg" class="form-group<?php if (array_key_exists("photo_url", $errors)) { echo " has-error"; } ?>">
      <div class="row">
        <label class="col-sm-2 control-label" for="photo">Photo URL</label>
        <div class="col-sm-4">
          <input type="url" name="photo_url" id="photo" class="form-control" maxlength="2083" value="" autocomplete="off">
        </div>
      </div>
    </div>

    <div class="form-group">
      <div class="row">
        <div class="col-sm-4 col-sm-offset-2">
          <div id="newEventImgPreview">
            <div>Photo Preview</div>
            <img src="">
          </div>
        </div>
      </div>
    </div>

    <!-- Description -->
    <div class="form-group<?php if (array_key_exists("desc", $errors)) { echo " has-error"; } ?>">
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
<?php include 'templates/includes/scripts.php' ?>
</body>
</html>