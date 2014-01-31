<?php
session_start();
define('DB_SERVER', 'panther.cs.middlebury.edu');
define('DB_USERNAME', 'dsilver');
define('DB_PASSWORD', 'dsilver122193');
define('DB_DATABASE', 'dsilver_EventsCalendar');

$con = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE) or die("Could not connect.");

$query = htmlspecialchars($_GET["q"]);
$search_cats = $search_orgs = array();
$start_date = $end_date = "";
if (isset($_GET["c"])) {
  $search_cats = $_GET["c"];
}
if (isset($_GET["o"])) {
  $search_orgs = $_GET["o"];
}

if (isset($_GET["start"])) {
  $start_date = $_GET["start"];
}

if (isset($_GET["end"])) {
  $end_date = $_GET["end"];
}

$sql_query = "SELECT E.* 
          FROM Events E, categorized_in C, organizer O
          WHERE E.title LIKE '%$query%'
          [cats]
          [orgs]
          [dates]

          UNION
          SELECT E.*
          FROM Events E, categorized_in C, organizer O
          WHERE E.location LIKE '%$query%'
          [cats]
          [orgs]
          [dates]

          UNION
          SELECT E.*
          FROM Events E, categorized_in C, organizer O
          WHERE E.description LIKE '%$query%'
          [cats]
          [orgs]
          [dates]

          ORDER BY event_date ASC";

if (!empty($search_cats)) {
  $search_cats_str = "AND C.event = E.id AND C.category IN ('" . implode("','", $search_cats) . "')";
  $sql_query = str_replace("[cats]", $search_cats_str, $sql_query);
}
else {
  $sql_query = str_replace("[cats]", "", $sql_query);
}

if (!empty($search_orgs)) {
  $sql_query = str_replace("[orgs]", 
              "AND O.event = E.id AND O.org IN ('" . implode("','", $search_orgs) . "')",
              $sql_query);
}
else {
  $sql_query = str_replace("[orgs]", "", $sql_query);
}

if (!empty($start_date) && !empty($end_date)) {
  $start_date_sql = date("Y-m-d", strtotime(str_replace("%2F", "/", $start_date)));
  $end_date_sql = date("Y-m-d", strtotime(str_replace("%2F", "/", $end_date)));
  $sql_query = str_replace("[dates]", 
              "AND event_date >= '" . $start_date_sql . "' AND end_date <= '" . $end_date_sql . "'", 
              $sql_query);
}
else if (!empty($start_date)) {
  $start_date_sql = date("Y-m-d", strtotime(str_replace("%2F", "/", $start_date)));
  $sql_query = str_replace("[dates]", 
              "AND event_date >= '" . $start_date_sql . "'", 
              $sql_query);
}
else if (!empty($end_date)) {
  $end_date_sql = date("Y-m-d", strtotime(str_replace("%2F", "/", $end_date)));
  $sql_query = str_replace("[dates]", 
              "AND end_date <= '" . $end_date_sql . "'", 
              $sql_query);
}
else {
  $sql_query = str_replace("[dates]", 
              "AND end_date >= NOW()", 
              $sql_query);
}

// $search_stmt = $con->prepare($sql_query);

// if (!$search_stmt)  {
//   echo "Prepare failed: (" . $con->errno . ") " . $con->error;
// }

// if (!$search_stmt->bind_param('sss', $query, $query, $query)) {
//   echo "Binding failed: (" . $search_stmt->errno . ") " . $search_stmt->error;
// }

$starttime = microtime(true);

// if (!$search_stmt->execute()) {
//   echo "Execute failed: " . $search_stmt->errno . $search_stmt->error;
// }

$search_results = mysqli_query($con, $sql_query);

$endtime = microtime(true);
$duration = round($endtime - $starttime, 4);

$search_array = array();
while ($row = mysqli_fetch_array($search_results, MYSQLI_ASSOC)) {
  $search_array[] = $row;
}

$num_results = count($search_array);

// $search_results->close();

$con->close();
?>

<!DOCTYPE html>
<html>
<?php
$title = "Midd Events";
include "templates/includes/head.php"
?>

<body>
<?php include 'templates/includes/navbar.php'; ?>
<div class="container">
  <div class="row">
    <form method="GET">
    <input type="hidden" name="q" value="<?php echo $query ?>">
    <div class="col-md-3">
      <div id="navSearchStartDate" class="refine form-group">
        <label class="control-label">Start date</label>
        <div class="input-group date">
          <input name="start" class="form-control" data-format="MM/dd/yyyy" type="text" placeholder="Start date" value="<?php echo str_replace("%2F", "-", $start_date) ?>">
          <span class="input-group-btn">
            <button type="button" class="btn btn-default"><span class="glyphicon glyphicon-calendar"></span></button>
          </span>
        </div>
      </div>
      <div id="navSearchEndDate" class="refine form-group">
        <label class="control-label">End date</label>
        <div class="input-group date">
          <input name="end" class="form-control" data-format="MM/dd/yyyy" type="text" placeholder="End date" value="<?php echo str_replace("%2F", "-", $end_date) ?>">
          <span class="input-group-btn">
            <button type="button" class="btn btn-default"><span class="glyphicon glyphicon-calendar"></span></button>
          </span>
        </div>
      </div>
      <div class="form-group">
        <label class="control-label">Organizations</label>
        <select name="o[]" multiple class="searchOrg">
        <?php foreach ($orgs as $org) { ?>
          <option value="<?php echo $org ?>" <?php if (in_array($org, $search_orgs)) { echo "selected"; } ?>><?php echo $org ?></option>
        <?php } ?>
        </select>
      </div>

      <div class="form-group">
        <label class="control-label">Categories</label>
        <select name="c[]" multiple class="searchCat">
        <?php foreach ($cats as $cat) { ?>
          <option value="<?php echo $cat ?>" <?php if (in_array($cat, $search_cats)) { echo "selected"; } ?>><?php echo $cat ?></option>
        <?php } ?>
        </select>
      </div>
      <button type="submit" class="btn btn-primary">Refine search</button>
    </div>
    </form>
    <div class="col-md-9">
      <h2><?php if (!empty($query)) {echo "Results for ";}?> <a href="search.php?q=<?php echo $query ?>"><?php echo $query ?></a></h2>
      <p>
        <?php 
        if ($num_results == 0) {
          echo "0 results in " . $duration . " seconds.";
        } elseif ($num_results == 1){
          echo "1 result in " . $duration . " seconds.";
        } else {
          echo $num_results . " results in " . $duration . " seconds."; 
        }
        ?>       
          
      </p>

      <ul class="search-results col-lg-8 col-md-8">
      <?php
      foreach ($search_array as $event) {
      ?>
      <li class="search-item">
        <h3><a href="event.php?event=<?php echo $event['id'] ?>"><?php echo $event['title'] ?></a></h3>
          <div class="item-detail">
            <p>
              <div><?php echo date('F j, Y \a\t g:i a', strtotime($event['event_date'])) . " to " . date('F j, Y \a\t g:i a', strtotime($event['end_date'])) ?></div>
              <div><?php echo $event['location']; ?></div>
            </p>
            <p>
            <?php
            echo substr($event['description'], 0, 250);
            if (strlen($event['description']) > 250){
              echo '...';
            };
            ?>
            </p>

          </div>
      </li>
      <?php
      }
      ?>
      </ul>
    </div>
  </div>
</div>
<?php include 'templates/includes/scripts.php' ?>
</body>
</html>