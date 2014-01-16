<?php
//start PHP section

//set up the connection to the database
define('DB_SERVER', 'panther.cs.middlebury.edu')
define('DB_USERNAME', 'dsilver')
define('DB_PASSWORD', 'dsilver122193')
define('DB_DATABASE', 'dsilver_EventsCalandar')

$con = msql_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE) or die("Could not connect");

<!-- Search the database -->
<form name="search_form" method="POST" action="query.php">
<p> Select : <select size="1" name="dropdown">
<option value="sid">ID</option>
<option value="sname"> name</option>
</select> </p>
<p> <input type="button" value="Submit" name="button_submit">
</p>
</form>

?>

