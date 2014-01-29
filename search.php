<?php
session_start();
define('DB_SERVER', 'panther.cs.middlebury.edu');
define('DB_USERNAME', 'dsilver');
define('DB_PASSWORD', 'dsilver122193');
define('DB_DATABASE', 'dsilver_EventsCalendar');

$con = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE) or die("Could not connect.");

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
          WHERE E.title LIKE CONCAT('%',?,'%')
          [cats]
          [orgs]
          [dates]

          UNION
          SELECT E.*
          FROM Events E, categorized_in C, organizer O
          WHERE E.location LIKE CONCAT('%',?,'%')
          [cats]
          [orgs]
          [dates]

          UNION
          SELECT E.*
          FROM Events E, categorized_in C, organizer O
          WHERE E.description LIKE CONCAT('%',?,'%')
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
              "AND event_date >= " . $start_date_sql . " AND end_date <= " . $end_date_sql, 
              $sql_query);
}
else if (!empty($start_date)) {
  $start_date_sql = date("Y-m-d", strtotime(str_replace("%2F", "/", $start_date)));
  $sql_query = str_replace("[dates]", 
              "AND event_date >= " . $start_date_sql, 
              $sql_query);
}
else if (!empty($end_date)) {
  $end_date_sql = date("Y-m-d", strtotime(str_replace("%2F", "/", $end_date)));
  $sql_query = str_replace("[dates]", 
              "AND end_date <= " . $end_date_sql, 
              $sql_query);
}
else {
  $sql_query = str_replace("[dates]", 
              "AND end_date >= NOW()", 
              $sql_query);
}

$search_stmt = $con->prepare($sql_query);

$query = htmlspecialchars($_GET["q"]);

if (!$search_stmt)  {
  echo "Prepare failed: (" . $con->errno . ") " . $con->error;
}

if (!$search_stmt->bind_param('sss', $query, $query, $query)) {
  echo "Binding failed: (" . $search_stmt->errno . ") " . $search_stmt->error;
}

$starttime = microtime(true);

if (!$search_stmt->execute()) {
  echo "Execute failed: " . $search_stmt->errno . $search_stmt->error;
}

$search_results = $search_stmt->get_result();

$endtime = microtime(true);
$duration = round($endtime - $starttime, 4);

$search_array = array();
while ($row = mysqli_fetch_array($search_results, MYSQLI_ASSOC)) {
  $search_array[] = $row;
}

$num_results = count($search_array);

$search_stmt->close();

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

    <h2>Results for <a href="search.php?q=<?php echo $query ?>"><?php echo $query ?></a></h2>
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
<?php include 'templates/includes/scripts.php' ?>
</body>
</html>