<?php
session_start();

define ('DB_SERVER', 'panther.cs.middlebury.edu');
define ('DB_USERNAME', 'dsilver');
define ('DB_PASSWORD', 'dsilver122193');
define ('DB_DATABASE', 'dsilver_EventsCalendar');

$con = mysqli_connect (DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE) or die ("Could not connect");

$uname = $_SESSION["username"];
include "../templates/includes/isadmin.php";

if (!$is_admin){
  header('Location: ../index.php');
  die();
}


$flagged_results = mysqli_query($con, "SELECT *
                                      FROM Events
                                      WHERE flagged = '1'
                                      AND end_date >= now()
                                      AND flagged_mod IS NULL
                                      ORDER BY end_date ASC");

$flagged_array = array();
while ($row = mysqli_fetch_array($flagged_results, MYSQLI_ASSOC)) {
  $flagged_array[] = $row;
}

$errors = array();
$org_title = $org_desc = $org_photo_url = $cat_name = '';

function clean_data($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
  }
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $org_key = "add_org";
  $cat_key = "add_cat";
  $t_error = "org_title";
  $d_error = "org_desc";
  $n_error = "cat_name";
  if (array_key_exists($org_key, $_POST)) {
    $org_title = clean_data($_POST['o_title']);
    $org_desc = clean_data($_POST['o_desc']);
    $org_photo_url = clean_data($_POST['o_photo_url']);
    empty($org_title) && $errors["org_title"] = "This field is required.";
    empty($org_desc) && $errors["org_desc"] = "This field is required.";

    if (!array_key_exists($t_error, $errors) || !array_key_exists($d_error, $errors)) {
      // Insert Org
      $org_stmt = $con->prepare("INSERT INTO Organizations 
                                 (name, description, photourl)
                                 VALUES
                                 (?, ?, ?)");

    if (!$org_stmt)  {
      echo "Org insert prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }
    if (!$org_stmt->bind_param('sss', $org_title, 
                                           $org_desc, 
                                           $org_photo_url))
    {
      echo "Org insert binding failed: (" . $stmt->errno . ") " . $stmt->error;
    }

    if (!$org_stmt->execute()) {
      echo "Execute failed: " . $org_stmt->errno . $org_stmt->error;
    }

    $org_stmt->close();
    }

  } else if (array_key_exists($cat_key, $_POST)) {
      $cat_name = clean_data($_POST['c_name']);
      empty($cat_name) && $errors["cat_name"] = "This field is required.";
  
      if (!array_key_exists($n_error, $errors)) {
      // Insert CAT
      $cat_stmt = $con->prepare("INSERT INTO Categories 
                                 (name)
                                 VALUES
                                 (?)");

    if (!$cat_stmt)  {
      echo "Cat insert prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }
    if (!$cat_stmt->bind_param('s', $cat_name))
    {
      echo "Cat insert binding failed: (" . $stmt->errno . ") " . $stmt->error;
    }

    if (!$cat_stmt->execute()) {
      echo "Execute failed: " . $cat_stmt->errno . $cat_stmt->error;
    }

    $cat_stmt->close();
    }
  }
}


$con->close();

?>

<!DOCTYPE html>
<html>

<?php
$title = "Admin";
$static_prefix = "../";
include "../templates/includes/head.php";
?>

<body>
<?php 
$index_prefix = "../";
$in_users = true;
include '../templates/includes/navbar.php'; 
?>


<div class="container">
	<h2>Admin Page</h2>
	<h3>Flagged Events</h3>

	<h4><?php if (empty($flagged_array)) { echo "There are no flagged events"; } ?></h4>
      <ul class="list-unstyled">
      <?php foreach ($flagged_array as $f_event) { ?>
      <li>
        <a href="../event.php?event=<?php echo $f_event["id"] ?>">
          <?php echo $f_event["title"] ?>
        </a>
      </li>
      <?php } ?>
     
      </ul>

  <h3>Add a New Organization<h3>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" class="form-horizontal col-sm-12 org-form" role="form" method="POST">
          
          <!-- Org Name -->
    <div class="form-group<?php if (array_key_exists("org_title", $errors)) { echo " has-error"; } ?>">
      <div class="row">
        <label class="col-sm-2 control-label" for="o_title">Name</label>
        <div class="col-sm-4">
          <input type="text" name="o_title" id="o_title" class="form-control" maxlength="75" value="<?php echo $org_title;?>">
          <?php if (array_key_exists("org_title", $errors)) { ?>
          <span class="help-block"><?php echo $errors["org_title"]; ?></span>
          <?php } ?>
        </div>
      </div>
    </div>

          <!-- Org Desc -->
    <div class="form-group<?php if (array_key_exists("org_desc", $errors)) { echo " has-error"; } ?>">
      <div class="row">
        <label class="col-sm-2 control-label" for="o_desc">Description</label>
        <div class="col-sm-6">
          <textarea id="o_desc" name="o_desc" class="form-control" rows="5"><?php echo $org_desc;?></textarea>
          <?php if (array_key_exists("org_desc", $errors)) { ?>
          <span class="help-block"><?php echo $errors["org_desc"]; ?></span>
          <?php } ?>
        </div>
      </div>
    </div>

        <!-- Org Photo URL -->
    <div id="OrgImg" class="form-group">
      <div class="row">
        <label class="col-sm-2 control-label" for="photo">Photo URL</label>
        <div class="col-sm-4">
          <input type="url" name="o_photo_url" id="o_photo_url" class="form-control" maxlength="2083" value="<?php echo $org_photo_url;?>" autocomplete="off">
        </div>
      </div>
    </div>

    <div class="form-group">
      <div class="row">
        <div class="col-sm-4 col-sm-offset-2">
          <div id="OrgImgPreview">
            <div>Photo Preview</div>
            <img src="">
          </div>
        </div>
      </div>
    </div>

    <div class="form-group">
      <div class="row">
        <div class="col-sm-4 col-sm-offset-2">
          <input class="btn btn-primary" type="submit" name = "add_org" value="Add Organization">
        </div>
      </div>
    </div>
  </form>

   <h3>Add a New Category<h3>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" class="form-horizontal col-sm-12 cat-form" role="form" method="POST">
    

              <!-- Cat Name -->
    <div class="form-group<?php if (array_key_exists("cat_name", $errors)) { echo " has-error"; } ?>">
      <div class="row">
        <label class="col-sm-2 control-label" for="o_title">Name</label>
        <div class="col-sm-4">
          <input type="text" name="c_name" id="c_name" class="form-control" maxlength="75" value="<?php echo $cat_name;?>">
          <?php if (array_key_exists("cat_name", $errors)) { ?>
          <span class="help-block"><?php echo $errors["cat_name"]; ?></span>
          <?php } ?>
        </div>
      </div>
    </div>


    <div class="form-group">
      <div class="row">
        <div class="col-sm-4 col-sm-offset-2">
          <input class="btn btn-primary" type="submit" name ="add_cat" value="Add Category">
        </div>
      </div>
    </div>
  </form>


</div>
<?php include '../templates/includes/scripts.php'?>
</body>
</html>