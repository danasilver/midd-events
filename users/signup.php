<?php
define('DB_SERVER', 'panther.cs.middlebury.edu');
define('DB_USERNAME', 'dsilver');
define('DB_PASSWORD', 'dsilver122193');
define('DB_DATABASE', 'dsilver_EventsCalendar');

$con = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE) or die("Could not connect.");

$errors = array();

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $input_username = htmlspecialchars($_POST['username']);
    $input_fullName = htmlspecialchars($_POST['fullName']);
    $input_password = htmlspecialchars($_POST['password']);
    $confirm_password = htmlspecialchars($_POST['confirm_password']);
    $email = $_POST['email'];


    empty($input_username) && $errors["username"] = "This field is required.";
    empty($input_fullName) && $errors["fullName"] = "This field is required.";
    empty($input_password) && $errors["password"] = "This field is required.";
    empty($confirm_password) && $errors["confirm_password"] = "This field is required.";
    empty($email) && $errors["email"] = "This field is required.";


    if (!array_key_exists("password", $errors)){
        if ($input_password == $confirm_password){
        }else{
            $errors["confirm_password"]="Password does not match.";
        }
    }








    if (!$errors){


    $encrypted_txt = crypt($input_password);

    date_default_timezone_set('America/New_York');
    $date = htmlspecialchars(date('Y/m/d h:i:s', time()));


        $stmt = $con->prepare("INSERT INTO Users (username, full_name, joined, password, email)
        VALUES(?, ?, ?, ?, ?)");


        if (!$stmt) {
            echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }

        if (!$stmt->bind_param('sssss', $input_username, $input_fullName, $date, $encrypted_txt, $email)) {
            echo "Binding failed: " . $stmt->errno . $stmt->error;
        }



        if (!$stmt->execute()) {
            echo "Execute failed: " . $stmt->errno . $stmt->error;
        }

        $stmt->close();

        session_start();
        $_SESSION["username"] = $input_username;

        header('Location: ' . '../index.php');
        die();
    }
}

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
      <div class="form-group<?php if (array_key_exists("username", $errors)) { echo " has-error"; } ?>">
          <div class="row">
              <label class="col-sm-2 control-label" for="username">Username</label>
              <div class="col-sm-4">
                  <input type="text" name="username" id="username" class="form-control" maxlength="20" tabindex="1" autofocus value="<?php if (!empty($_POST['username'])) { echo $_POST['username']; } ?>">
                  <?php if (array_key_exists("username", $errors)) { ?>
                      <span class="help-block"><?php echo $errors["username"]; ?></span>
                  <?php } ?>
              </div>
          </div>
      </div>

      <!-- Full Name -->
      <div class="form-group<?php if (array_key_exists("fullName", $errors)) { echo " has-error"; } ?>">
          <div class="row">
              <label class="col-sm-2 control-label" for="fullName">Full Name</label>
              <div class="col-sm-4">
                  <input type="text" name="fullName" id="fullName" class="form-control" maxlength="20" tabindex="2" value="<?php if (!empty($_POST['fullName'])) { echo $_POST['fullName']; } ?>">
                  <?php if (array_key_exists("fullName", $errors)) { ?>
                      <span class="help-block"><?php echo $errors["fullName"]; ?></span>
                  <?php } ?>
              </div>
          </div>
      </div>

    <!-- Password -->
      <div class="form-group<?php if (array_key_exists("password", $errors)) { echo " has-error"; } ?>">
          <div class="row">
              <label class="col-sm-2 control-label" for="pass">Password</label>
              <div class="col-sm-4">
                  <input type="password" name="password" id="pass" class="form-control" maxlength="64" tabindex="2">
                  <?php if (array_key_exists("password", $errors)) { ?>
                      <span class="help-block"><?php echo $errors["password"]; ?></span>
                  <?php } ?>
              </div>
          </div>
      </div>


      <!-- Confirm Password -->

      <div class="form-group<?php if (array_key_exists("confirm_password", $errors)) { echo " has-error"; } ?>">
          <div class="row">
              <label class="col-sm-2 control-label" for="confirm_password">Verify Password</label>
              <div class="col-sm-4">
                  <input type="password" name="confirm_password" id="confirm_password" class="form-control" maxlength="64" tabindex="2">
                  <?php if (array_key_exists("confirm_password", $errors)) { ?>
                      <span class="help-block"><?php echo $errors["confirm_password"]; ?></span>
                  <?php } ?>
              </div>
          </div>
      </div>

    <!-- Email -->

      <div class="form-group<?php if (array_key_exists("email", $errors)) { echo " has-error"; } ?>">
          <div class="row">
              <label class="col-sm-2 control-label" for="email">Email</label>
              <div class="col-sm-4">
                  <div class="input-group">
                    <input type="text" name="email" id="email" class="form-control" maxlength="20" tabindex="2" >
                    <span class="input-group-addon">@middlebury.edu</span>
                  </div>
                    <value="<?php if (!empty($_POST['email'])) { echo $_POST['email']; } ?>">
                  <?php if (array_key_exists("email", $errors)) { ?>
                      <span class="help-block"><?php echo $errors["email"]; ?></span>
                  <?php } ?>
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