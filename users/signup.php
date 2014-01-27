<?php
define('DB_SERVER', 'panther.cs.middlebury.edu');
define('DB_USERNAME', 'dsilver');
define('DB_PASSWORD', 'dsilver122193');
define('DB_DATABASE', 'dsilver_EventsCalendar');

$con = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE) or die("Could not connect.");


if (isset($_POST['register']) && !empty($_POST['register'])){


    $encrypted_txt = crypt($_POST['password']);

    date_default_timezone_set('America/New_York');
    $date = date('Y/m/d h:i:s', time());

    $sql = "INSERT INTO Users (username, full_name, is_admin, joined, password, email)
                VALUES('$_POST[username]','$_POST[full_name]','1','$date','$encrypted_txt','$_POST[email]')";



    if (!mysqli_query($con, $sql)) {
        die('Error: ' . mysqli_error($con));
    }
    ?>

    <script>
        window.location.href = "index.php"
    </script>

<?php
}


?>

mysqli_close($con);
?>
<!DOCTYPE html>
<html>
<?php
$title = "Registration";
$static_prefix = "../";
include "../templates/includes/head.php"
?>
<body>
<?php
$index_prefix = "../";
$in_users = true;
include "../templates/includes/navbar.php";
?>
<div class="container">
  <h2>Registration</h2>
  <form action="signup.php" class="form-horizontal col-sm-12 event-form"  method="POST">


    <!-- Username -->
    <div class="form-group">
      <div class="row">
        <label class="col-sm-2 control-label" for="username">Username</label>
        <div class="col-sm-4">
          <input type="text" name="username" id="username" class="form-control" maxlength="20" tabindex="1" autofocus>
        </div>
      </div>
    </div>

    <!-- Full Name -->
    <div class="form-group">
      <div class="row">
        <label class="col-sm-2 control-label" for="full_name">Full Name</label>
        <div class="col-sm-4">
          <input type="text" class="form-control" name="full_name" id="full_name" maxlength="40" tabindex="2">
        </div>
      </div>
    </div>

    <!-- Password -->
    <div class="form-group">
      <div class="row">
        <label class="col-sm-2 control-label" for="pass">Password</label>
        <div class="col-sm-4">
          <input type="password" name="password" id="pass" class="form-control" maxlength="64" tabindex="3">
        </div>
      </div>
    </div>

      <!-- Confirm Password -->
      <div class="form-group">
          <div class="row">
              <label class="col-sm-2 control-label" for="confirm_pass">Password</label>
              <div class="col-sm-4">
                  <input type="password" name="confirm_password" id="confirm_pass" class="form-control" maxlength="64"
              </div>
          </div>
      </div>

    <!-- Email -->
    <div class="form-group">
      <div class="row">
        <label class="col-sm-2 control-label" for="email">Email</label>
        <div class="col-sm-4">
          <div class="input-group">
            <input type="text" name="email" id="email" class="form-control" maxlength="45" tabindex="4" autocomplete="off">
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
<?php include '../templates/includes/scripts.php' ?>
</body>
</html>