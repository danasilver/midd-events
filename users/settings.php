<?php
session_start();

// Redirect to homepage if not logged in
if (!isset($_SESSION["username"])) {
  header('Location: ../index.php');
  die();
}

define ('DB_SERVER', 'panther.cs.middlebury.edu');
define ('DB_USERNAME', 'dsilver');
define ('DB_PASSWORD', 'dsilver122193');
define ('DB_DATABASE', 'dsilver_EventsCalendar');

$con = mysqli_connect (DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE) or die ("Could not connect");

$uname = $_SESSION["username"];
$follow_orgs = $follow_cats = array();



if ($_SERVER["REQUEST_METHOD"] == "POST") {
  function clean_data($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
  }
}

$follow_orgs = array();
if (!empty($_POST['follow_orgs'])){
	$follow_orgs = $_POST['follow_orgs'];
}
$follow_cats = array();
if (!empty($_POST['follow_cats'])){
	$follow_cats = $_POST['follow_cats'];
}

// Insert follow orgs
$forgs_stmt = $con->prepare("INSERT INTO follow_org (user, org) VALUES (?, ?)");
if (!$forgs_stmt) {
	echo "Orgs insert statement failed: (" . $mysqli->errno . ") " . $mysqli->error;
}
foreach ($follow_orgs as $forg) {
	if (!$forgs_stmt->bind_param('ss', $uname, $forg)) {
		echo "Orgs insert binding failed: (" . $forgs_stmt->errno . ") " . $forgs_stmt->error;
	}
	
	if (!$forgs_stmt->execute()) {
		echo "Execute failed: " . $forgs_stmt->errno . $forgs_stmt->error;
	}
}
$forgs_stmt->close();

// Insert follow cats
$fcats_stmt = $con->prepare("INSERT INTO follow_cat (cat, user) VALUES (?, ?)");
if (!$fcats_stmt) {
	echo "Cats insert statement failed: (" . $mysqli->errno . ") " . $mysqli->error;
}
foreach ($follow_cats as $fcat) {
	if (!$fcats_stmt->bind_param('ss', $fcat, $uname)) {
		echo "Cats insert binding failed: (" . $fcats_stmt->errno . ") " . $fcats_stmt->error;
	}
	
	if (!$fcats_stmt->execute()) {
		echo "Execute failed: " . $fcats_stmt->errno . $fcats_stmt->error;
	}
}
$fcats_stmt->close();

// Make array of the categories this user follows
$my_cats_results = mysqli_query($con, "SELECT cat FROM follow_cat where user = '$uname'");
$my_cats = array();
if ($my_cats_results != False) {
	while ($row = mysqli_fetch_array($my_cats_results, MYSQLI_ASSOC)) {
	  $my_cats[] = $row['cat'];
	}
}
// Make array of the organizations this user follows
$my_orgs_results = mysqli_query($con, "SELECT org FROM follow_org where user = '$uname'");
$my_orgs = array();
if ($my_orgs_results != False) {
	while ($row = mysqli_fetch_array($my_orgs_results, MYSQLI_ASSOC)) {
	  $my_orgs[] = $row['org'];
	}
}
$con->close();

?>

<!DOCTYPE html>
<html>

<?php
$title = "Settings";
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
	<h2>Settings</h2>
	<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" class="form-horizontal col-sm-12 follow-form" role="form" method="POST">

		<!-- Organization -->
	<div class="row">
	<h3>Follow an Organization</h3>
    <div class="form-group form-group-category">
      
        <label class="col-sm-2 control-label" for="org">Organization</label>
        <div class="col-sm-4">
          <select id="newEventOrg" name="follow_orgs[]" multiple>
          <?php foreach ($orgs as $organization) { ?>
            <option <?php if (in_array($organization, $follow_orgs)) { echo "selected"; } ?>> <?php if (!in_array($organization, $my_orgs)){echo $organization;}?></option>
          	<?php } ?>
          </select>
        </div>
     </div>
  </div>

    

    <!-- Categories -->
    <div class="row">
    <h3>Follow a Category</h3>
    <div class="form-group form-group-category">
        <label class="col-sm-2 control-label" for="cats">Categories</label>
        <div class="col-sm-4">
          <select id="newEventCats" name="follow_cats[]" multiple>
          <?php foreach ($cats as $cat) { ?>
            <option <?php if (in_array($cat, $follow_cats)) { echo "selected"; } ?>><?php if (!in_array($cat, $my_cats)){echo $cat;}?></option>
          	<?php } ?>
          </select>
        </div>
      </div>
    </div>

    <!-- Follow -->
    <div class="form-group">
      <div class="row">
        <div class="col-sm-4 col-sm-offset-2">
          <input class="btn btn-primary" type="submit" value="Follow">
        </div>
      </div>
    </div>
  </form>
</div>

<!-- Show the Organizations the user follows -->
	<div>
			<h4><?php if (!empty($my_orgs)) { echo "Organizations I Follow"; } ?></h4>
      		<ul class="list-unstyled">
      		<?php foreach ($my_orgs as $my_org) { ?>
      	<li>
        	<a href="search.php?c%5B%5D=<?php echo $my_org ?>">
          	<?php echo $my_org ?>
        	</a>
      	</li>
      	<?php } ?>
      	</ul>
  	</div>

<!-- Show the Categories the user follows -->
  	<div>
			<h4><?php if (!empty($my_cats)) { echo "Categories I Follow"; } ?></h4>
      		<ul class="list-unstyled">
      		<?php foreach ($my_cats as $my_cat) { ?>
      	<li>
        	<a href="search.php?c%5B%5D=<?php echo $my_cat ?>">
          	<?php echo $my_cat ?>
        	</a>
      	</li>
      	<?php } ?>
      	</ul>
  	</div>
  	



</div>
<?php include '../templates/includes/scripts.php' ?>
</body>
</html>