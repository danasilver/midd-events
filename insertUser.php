<?php
define('DB_SERVER', 'panther.cs.middlebury.edu');
define('DB_USERNAME', 'dsilver');
define('DB_PASSWORD', 'dsilver122193');
define('DB_DATABASE', 'dsilver_EventsCalendar');

$con = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE) or die("Could not connect");


date_default_timezone_set('America/New_York');
$date = date('Y/m/d h:i:s', time());

$sql = "INSERT INTO Users (username, full_name, is_admin, joined, password, email)
VALUES
('$_POST[username]','$_POST[full_name]','1','$date','$_POST[password]','$_POST[email]')";

if (!mysqli_query($con, $sql)) {
    die('Error: ' . mysqli_error($con));
}
?>

    <script>
        window.location.href = "index.php"
    </script>

<?php
mysql_close($con);
?>