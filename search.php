<?php
define('DB_SERVER', 'panther.cs.middlebury.edu');
define('DB_USERNAME', 'dsilver');
define('DB_PASSWORD', 'dsilver122193');
define('DB_DATABASE', 'dsilver_EventsCalendar');

$con = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE) or die("Could not connect.");

$search_stmt = $con->prepare("SELECT * 
                              FROM Events
                              WHERE title LIKE CONCAT('%',?,'%')
                              AND event_date >= now()

                              UNION
                              SELECT *
                              FROM Events
                              WHERE location LIKE CONCAT('%',?,'%')
                              AND event_date >= now()

                              UNION
                              SELECT *
                              FROM Events
                              WHERE description LIKE CONCAT('%',?,'%')
                              AND event_date >= now()

                              -- UNION
                              -- SELECT E.*
                              -- FROM Events E, organizer O
                              -- WHERE E.id = O.event

                              -- UNION 
                              -- SELECT 

                              ORDER BY event_date ASC");

$query = htmlspecialchars($_GET["q"]);
$query_orgs = htmlspecialchars($_GET["o"]);
$query_cats = htmlspecialchars($_GET["c"]);

if (!$search_stmt)  {
  echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
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

    <p><a href="index.php">Back to search</a></p>

    <ul class="search-results col-lg-8 col-md-8">
    <?php
    foreach ($search_array as $event) {
    ?>
    <li class="search-item">
      <h3><a href="event.php?event=<?php echo $event['id'] ?>"><?php echo $event['title'] ?></a></h3>
        <div class="item-detail">
          <p>
            <div><?php echo date('F j, Y \a\t g:i a', strtotime($event['event_date'])) ?></div>
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

</html>