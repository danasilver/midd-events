<?php

$con = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE) or die("Could not connect.");
//Get all organizations
$org_results = mysqli_query($con, "SELECT name FROM Organizations ORDER BY name");
$orgs = array();
while ($row = mysqli_fetch_array($org_results, MYSQLI_ASSOC)) {
  $orgs[] = $row['name'];
}
// Get all categories
$cat_results = mysqli_query($con, "SELECT name FROM Categories ORDER BY name");
$cats = array();
while ($row = mysqli_fetch_array($cat_results, MYSQLI_ASSOC)) {
  $cats[] = $row['name'];
}


//Check to see if user is an admin
$uname = $_SESSION["username"];
$is_admin = false;
$isadmin_query = mysqli_query($con, "SELECT is_admin FROM Users WHERE username = '$uname'");
$isadmin_result = mysqli_fetch_array($isadmin_query);
if ($isadmin_result['is_admin'] == 0){
  $is_admin = false;
} else {
  $is_admin = true;
}

if (!isset($index_prefix)) {
  $index_prefix = "";
}

if (!isset($in_users)) {
  $in_users = false;
}
?>
<nav class="navbar navbar-default" role="navigation">
  <div class="navbar-header">
    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-collapse">
      <span class="sr-only">Toggle navigation</span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
    </button>
    <a class="navbar-brand visible-xs" href="<?php echo $index_prefix; ?>index.php">Midd Events</a>
  </div>

  <div class="collapse navbar-collapse" id="navbar-collapse">
    <div class="container">
      <div class="row index-header">
        <div class="col-md-3">
          <a class="navbar-brand-link" href="<?php echo $index_prefix; ?>index.php"><span class="h2 hidden-xs">Midd Events</span></a>
        </div>

        <form role="form" class="" action="<?php echo $index_prefix; ?>search.php" method="GET">
          <div class="col-md-4 col-md-offset-1">
            <div class="form-group">
              <div class="input-group">
                <input name="q" type="text" class="form-control" id="search" placeholder="Search events" autocomplete="off">
                <span class="input-group-btn">
                  <button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-search"></span></button>
                </span>
              </div>
            </div>
          </div>

          <div class="col-sm-1 col-xs-3">
            <button id="searchFilterToggle" type="button" data-toggle="button" class="btn btn-default">
              <span class="glyphicon glyphicon-filter"></span>
            </button>
          </div>

          <?php if (isset($_SESSION["username"])) { ?>
          <div class="col-md-3">
            <ul class="list-unstyled list-inline pull-right">
              <li><a href="<?php echo $index_prefix; ?>new.php" class="btn btn-primary">New Event</a></li>
              <li> 
                <!-- Drop down -->
                <div class="btn-group">
                  <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                    <?php echo $_SESSION["username"]; ?>
                    <span class="caret"></span>
                  </button>
                  <ul class="dropdown-menu" role="menu">
                    <li><a href="<?php if (!$in_users) { echo "users/"; } ?>settings.php">Settings</a></li> 
                    <li><a href="<?php if (!$in_users) { echo "users/"; } ?>logout.php">Logout</a></li>
                    <?php if ($is_admin) { ?>
                    <li><a href="<?php if (!$in_users) { echo "users/"; } ?>admin.php">Admin</a></li>
                    <?php }?>
                  </ul>
                </div>
            </ul>
          </div>
          <?php } else { ?>
          <div class="col-md-3">
            <ul class="list-unstyled list-inline pull-right">
              <li><a href="<?php if (!$in_users) { echo "users/"; } ?>login.php" class="btn btn-default">Login</a></li>
              <li><a href="<?php if (!$in_users) { echo "users/"; } ?>signup.php" class="btn btn-primary">Sign up</a></li>
            </ul>
          </div>
          <?php } ?>

          </div>

          <div id="searchFilter" class="form-group hide">
            <div class="row">

              <div id="navSearchStartDate" class="form-group col-md-2 col-md-offset-1">
                <div class="input-group date">
                  <input name="start" class="form-control" data-format="MM/dd/yyyy" type="text" placeholder="Start date">
                  <span class="input-group-btn">
                    <button type="button" class="btn btn-default"><span class="glyphicon glyphicon-calendar"></span></button>
                  </span>
                </div>
              </div>

              <div id="navSearchEndDate" class="form-group col-md-2">
                <div class="input-group date">
                  <input name="end" class="form-control" data-format="MM/dd/yyyy" type="text" placeholder="End date">
                  <span class="input-group-btn">
                    <button type="button" class="btn btn-default"><span class="glyphicon glyphicon-calendar"></span></button>
                  </span>
                </div>
              </div>

              <div class="form-group col-md-3">
                <select name="o[]" multiple class="form-control searchOrg">
                <?php foreach ($orgs as $org) { ?>
                  <option value="<?php echo $org ?>"><?php echo $org ?></option>
                <?php } ?>
                </select>
              </div>

              <div class="form-group col-md-3">
                <select name="c[]" multiple class="form-control searchCat">
                <?php foreach ($cats as $cat) { ?>
                  <option value="<?php echo $cat ?>"><?php echo $cat ?></option>
                <?php } ?>
                </select>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</nav>