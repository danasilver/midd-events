<?php
//start PHP section

//set up the connection to the database
define('DB_SERVER', 'panther.cs.middlebury.edu')
define('DB_USERNAME', 'dsilver')
define('DB_PASSWORD', 'dsilver122193')
define('DB_DATABASE', 'dsilver_EventsCalandar')

$con = msql_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE) or die("Could not connect");

?>

<html>
<body>

<!-- Insert into the database -->

<form name="insert.php" method="post">
ID: <input type="text" name="sid" required /> <br> <br>
Name: <input type="text" name="sid" required /> <br> <br>
Age: <input type="text" name="age" required /> <br> <br>
<input type="submit" value="Insert into Database" /> <br> <br>

</form>

</body>
</html>