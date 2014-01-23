<?php
define('DB_SERVER', 'panther.cs.middlebury.edu');
define('DB_USERNAME', 'dsilver');
define('DB_PASSWORD', 'dsilver122193');
define('DB_DATABASE', 'dsilver_EventsCalendar');

$con = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE) or die("Could not connect.");

mysqli_close($con);
?>
<!DOCTYPE html>
<html>
<?php
$title = "Registration";
include "templates/includes/head.php"
?>
<body>
<div class="container">
  <h2>Registration</h2>
  <a href="index.php" class="btn btn-link" tabindex="-1">Back to search</a>
  <form action="insertUser.php" class="form-horizontal col-sm-12 event-form" role="form" method="POST">


    <!-- Username -->
    <div class="form-group">
      <div class="row">
        <label class="col-sm-2 control-label" for="username">Username</label>
        <div class="col-sm-4">
          <input type="text" name="username" id="username" class="form-control" maxlength="20">
        </div>
      </div>
    </div>

    <!-- Full Name -->
    <div class="form-group">
      <div class="row">
        <label class="col-sm-2 control-label" for="full_name">Full Name</label>
        <div class="col-sm-4">
          <input type="text" class="form-control" name="full_name" id="full_name" maxlength="40">
        </div>
      </div>
    </div>

    <!-- must secure the password-->

    <!-- Password -->
    <div class="form-group">
      <div class="row">
        <label class="col-sm-2 control-label" for="pass">Password</label>
        <div class="col-sm-4">
          <input type="password" name="password" id="pass" class="form-control" maxlength="64">
        </div>
      </div>
    </div>

    <!-- Email -->
    <div class="form-group">
      <div class="row">
        <label class="col-sm-2 control-label" for="email">Email</label>
        <div class="col-sm-4">
          <div class="input-group">
            <input type="text" name="email" id="email" class="form-control" maxlength="45">
            <span class="input-group-addon">@middlebury.edu</span>
          </div>
        </div>
      </div>
    </div>

    <div class="form-group">
      <div class="row">
        <div class="col-sm-4 col-sm-offset-2">
          <input class="btn btn-primary" type="submit" value="Sign up">
        </div>
      </div>
    </div>
  </form>
</div>

</body>
</html>