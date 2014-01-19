<?php
define ('DB_SERVER', 'panther.cs.middlebury.edu');
define ('DB_USERNAME', 'dsilver');
define ('DB_PASSWORD', 'dsilver122193');
define ('DB_DATABASE', 'dsilver_EventsCalendar');

$event_id = $_GET["event"];
$con = mysqli_connect (DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE) or die ("Could not connect");

$result = mysqli_query($con, "SELECT * FROM Events WHERE $event_id = id");
$event = mysqli_fetch_array($result);

mysqli_close($con);

?>

<!DOCTYPE html>
<html>

<?php
$title = $event['title'];
include "templates/includes/head.php";
?>

<body>

<div class="container">
  <h1><?php echo $event['title'] ?></h1>
  <div class="row">
    <div class="col-lg-4 col-md-4">
      <img width="100%" height="100%" src="<?php echo $event['photo_url'] ?>">
    </div>
    <div class="col-lg-8 col-md-8">
      <h4>Where: <?php echo $event['location'] ?></h4>
      <h4>When: 
        <?php 
        $phpdate = strtotime($event['event_date']);
        echo date('g:i A, F j, Y', $phpdate);
        ?>
      </h4>
      <h4>Created by: <?php echo $event['host'] ?></h4>
      <p><?php echo $event['description'] ?></p>
    </div>
  </div>
</div>
<?php



/*Event name: echo $event -> title;
Description: echo $event -> description;
Where is it? echo $event -> location;
When? echo $event -> event_date;
Who is the host? echo $event -> host;
$cats = mysqli_query("SELECT i.category FROM categorized_in i, Events e WHERE $_GET["Eventid"] = e.id");

while ($row = mysqli_fetch_field($cats))
{
	echo $row['category'] . " "; 
	echo "<br>";
}
*/
?>

</body>
</html>