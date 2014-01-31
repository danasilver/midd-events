<?php
ini_set('display_errors', 'On');
session_start();

// Redirect to homepage if not logged in
if (!isset($_SESSION["username"])) {
    header('Location: index.php');
    die();
}

define('DB_SERVER', 'panther.cs.middlebury.edu');
define('DB_USERNAME', 'dsilver');
define('DB_PASSWORD', 'dsilver122193');
define('DB_DATABASE', 'dsilver_EventsCalendar');

$con = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE) or die("Could not connect.");

$event_id = htmlspecialchars($_GET["event"]);

// Get event info
$event_result = mysqli_query($con,"SELECT * FROM Events WHERE id='$event_id'");
$event = array();


if (mysqli_num_rows($event_result) > 0) {
    $event = mysqli_fetch_array($event_result, MYSQLI_ASSOC);
} else {
    // $event_result is false, redirect to 404
    header('Location: 404.php');
    die();
}

if ($_SESSION["username"] != $event["host"]) {
    header('Location: ' . 'event.php?event=' . $event_id);
    die();
}

$event["event_date"] = date('m/d/Y g:i A', strtotime($event["event_date"]));
$event["end_date"] = date('m/d/Y g:i A', strtotime($event["end_date"]));

$event_org_result=mysqli_query($con,"SELECT * FROM organizer WHERE event='$event_id'");
$event_org = mysqli_fetch_array($event_org_result, MYSQLI_ASSOC);

$event_cat_result = mysqli_query($con,"SELECT category FROM categorized_in WHERE event='$event_id'");
$event_cat = array();
while ($event_cat_row = mysqli_fetch_array($event_cat_result, MYSQLI_ASSOC)) {
    $event_cat[] = $event_cat_row['category'];
}

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

$errors = $categories = array();
$org_placeholder = $event_org["org"];

$event_title = $desc = $photo_url = $location = $date = $end_date = "";
$categories = $event_cat;
$photo_url = $event["photo_url"];

// POST request validation
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    function clean_data($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    $event_title = clean_data($_POST['title']);
    $desc = clean_data($_POST['description']);
    $photo_url = clean_data($_POST['photo_url']);
    $location = clean_data($_POST['location']);
    $date = clean_data($_POST['event_date']);
    $end_date = clean_data($_POST['end_date']);
    $org = $org_placeholder = clean_data($_POST["org"]);
    $categories = array();

    if (!empty($_POST['cats'])){
        $categories = $_POST['cats'];
    }

    $host = $_SESSION["username"];

    empty($event_title) && $errors["event_title"] = "This field is required.";
    empty($desc) && $errors["desc"] = "This field is required.";
    empty($location) && $errors["location"] = "This field is required.";
    empty($date) && $errors["date"] = "This field is required.";
    empty($org) && $errors["org"] = "This field is required.";
    empty($categories) && $errors["categories"] = "This field is required.";
    empty($end_date) && $errors["end_date"] = "This field is required.";

    if (empty($errors)) {
        // update event

        $update_stmt = $con->prepare("UPDATE Events SET title = ?,
                                                        description = ?,
                                                        location = ?,
                                                        event_date = ?,
                                                        end_date = ?
                                                    WHERE id = ?");
        $update_stmt->bind_param("sssssi", $event_title,
                                           $desc,
                                           $location,
                                           date('Y-m-d H:i:s', strtotime($date)),
                                           date('Y-m-d H:i:s', strtotime($end_date)),
                                           $event_id);
        $update_stmt->execute();
        $update_stmt->close();

        mysqli_query($con, "UPDATE organizer SET 'org'=? WHERE 'event'=$event_id");
        mysqli_query($con, "DELETE FROM categorized_in WHERE event=$event_id ");
        if(isset($photo_url)){
            mysqli_query($con, "UPDATE  `dsilver_EventsCalendar`.`Events` SET  photo_url = '$photo_url' WHERE  `Events`.`id` =$event_id");
        }

        // Insert categories
        $cats_stmt = $con->prepare("INSERT INTO categorized_in (event, category) VALUES (?, ?)");
        if (!$cats_stmt) {
            echo "Cats insert statement failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }
        foreach ($categories as $cat) {
            if (!$cats_stmt->bind_param('is', $event_id, $cat)) {
                echo "Cats insert binding failed: (" . $stmt->errno . ") " . $stmt->error;
            }

            if (!$cats_stmt->execute()) {
                echo "Execute failed: " . $cats_stmt->errno . $cats_stmt->error;
            }
        }
        $cats_stmt->close();

        header('Location: ' . 'event.php?event=' . $event_id);
        die();
    }
}
?>

<!DOCTYPE html>
<html>
<?php
$title = "Edit Event";
include "templates/includes/head.php"
?>
<body>
<?php include "templates/includes/navbar.php" ?>
<div class="container">
    <h2>Edit event</h2>
    <form action="<?php echo 'edit.php?event='.$event_id ; ?>" class="form-horizontal col-sm-12 event-form"  method="POST">
        <!-- Title -->
        <div class="form-group<?php if (array_key_exists("event_title", $errors)) { echo " has-error"; } ?>">
            <div class="row">
                <label class="col-sm-2 control-label" for="title">Title </label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" name="title" id="title" maxlength="30" value="<?php if (array_key_exists("event_title", $errors)) { echo $event_title; }
                    else { echo $event[ "title" ] ; } ?> ">
                    <?php if (array_key_exists("event_title", $errors)) { ?>
                        <span class="help-block"><?php echo $errors["event_title"]; ?></span>
                    <?php } ?>

                </div>
            </div>
        </div>

        <!-- Location -->
        <div class="form-group<?php if (array_key_exists("location", $errors)) { echo " has-error"; } ?>">
            <div class="row">
                <label class="col-sm-2 control-label" for="location">Location</label>
                <div class="col-sm-4">
                    <input type="text" name="location" id="location" class="form-control" maxlength="100" value="<?php if (array_key_exists("location", $errors)) { echo $location; }
                    else { echo $event[ "location" ] ; } ?> ">
                    <?php if (array_key_exists("location", $errors)) { ?>
                        <span class="help-block"> <?php echo $errors["location"]; ?></span>
                    <?php } ?>
                </div>
            </div>
        </div>

        <!-- Organization -->
        <div class="form-group">
            <div class="row">
                <label class="col-sm-2 control-label" for="org">Organization</label>
                <div class="col-sm-4">
                    <select id="newEventOrg" name="org">
                        <?php
                        foreach ($orgs as $organization) { ?>
                            <option <?php if ($organization == $org_placeholder ) { echo "selected"; } ?>> <?php echo $organization ?></option>
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
                            <option <?php if (in_array($cat, $categories)) { echo "selected"; } ?>><?php echo $cat ?></option>
                        <?php } ?>

                    </select>
                    <?php if (array_key_exists("categories", $errors)) { ?>
                        <span class="help-block"><?php echo $errors["categories"]; ?></span>
                    <?php } else?>
                </div>
            </div>
        </div>

        <!-- Start Date -->
        <div id="newEventDate" class="form-group<?php if (array_key_exists("date", $errors)) { echo " has-error"; } ?>">
            <div class="row">
                <label class="col-sm-2 control-label" for="date">Start Date</label>
                <div class="col-sm-4">
                    <div class="input-group">
                        <input data-format="MM/dd/yyyy HH:mm PP" type="text" name="event_date" id="date" class="form-control" maxlength="30" value="<?php if (array_key_exists("date", $errors)) { echo $date; }
                        else { echo $event[ "event_date" ] ; } ?>">
            <span class="input-group-btn">
              <button type="button" class="btn btn-default"><span class="glyphicon glyphicon-calendar"></span></button>
            </span>
                        <?php if (array_key_exists("date", $errors)) { ?>
                            <span class="help-block"><?php echo $errors["date"]; ?></span>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- End Date -->
        <div id="newEventEndDate" class="form-group<?php if (array_key_exists("end_date", $errors)) { echo " has-error"; } ?>">
            <div class="row">
                <label class="col-sm-2 control-label" for="end_date">End Date</label>
                <div class="col-sm-4">
                    <div class="input-group">
                        <input data-format="MM/dd/yyyy HH:mm PP" type="text" name="end_date" id="end_date" class="form-control" maxlength="30" value="<?php if (array_key_exists("end_date", $errors)) { echo $end_date; }
                        else { echo $event[ "end_date" ] ; } ?>">
            <span class="input-group-btn">
              <button type="button" class="btn btn-default"><span class="glyphicon glyphicon-calendar"></span></button>
            </span>
                        <?php if (array_key_exists("end_date", $errors)) { ?>
                            <span class="help-block"><?php echo $errors["end_date"]; ?></span>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Photo URL -->
        <div id="newEventImg" class="form-group">
            <div class="row">
                <label class="col-sm-2 control-label" for="photo">Photo URL</label>
                <div class="col-sm-4">
                    <input type="url" name="photo_url" id="photo" class="form-control" maxlength="2083" value="<?php echo $photo_url;?>" autocomplete="off">
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
                    <textarea id="description" name="description" class="form-control" rows="5"><?php if (array_key_exists("desc", $errors)) { echo $desc; }
                        else { echo $event[ "description" ] ; };?></textarea>
                    <?php if (array_key_exists("desc", $errors)) { ?>
                        <span class="help-block"><?php echo $errors["desc"]; ?></span>
                    <?php } ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <div class="col-sm-4 col-sm-offset-2">
                    <input class="btn btn-primary" type="submit" value="Submit Changes">
                </div>
            </div>
        </div>
    </form>
</div>
<?php include 'templates/includes/scripts.php' ?>
</body>
</html>