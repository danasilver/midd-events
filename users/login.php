<?php
define('DB_SERVER', 'panther.cs.middlebury.edu');
define('DB_USERNAME', 'dsilver');
define('DB_PASSWORD', 'dsilver122193');
define('DB_DATABASE', 'dsilver_EventsCalendar');

function bind_array($stmt, &$row) {
  $md = $stmt->result_metadata();
  $params = array();
  while($field = $md->fetch_field()) {
        $params[] = &$row[$field->name];
  }
  call_user_func_array(array($stmt, 'bind_result'), $params);
}

$errors = array();

if ($_SERVER['REQUEST_METHOD'] == "POST") {
  $input_username = $_POST['username'];
  $input_password = $_POST['password'];

  empty($input_username) && $errors["username"] = "This field is required.";
  empty($input_password) && $errors["password"] = "This field is required.";

  if (empty($errors)) {
    $con = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE) or die("Could not connect.");

    $stmt = $con->prepare("SELECT password
                           FROM Users
                           WHERE username = (?)");

    $stmt->bind_param('s', $input_username);
    
    if (!$stmt->execute()) {
      echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
    }
    else {
      bind_array($stmt, $row);
      $stmt->fetch();
      print_r($row);
    }

    $stmt->close();
    $con->close();

    // if (crypt($input_password, $fetched_password) == $fetched_password) {
    //   session_start();
    //   $_SESSION["username"] = $input_username;
    //   header('Location: ../index.php');
    //   die();
    // }
    // else {
    //   $errors["password"] = "The password you entered didn't match your username.";
    // }
  }
}


?>

<!DOCTYPE html>
<html>
<?php
$title = "Login";
$static_prefix = "../";
include '../templates/includes/head.php';
?>
<body>
<?php
$index_prefix = "../";
$in_users = true;
include "../templates/includes/navbar.php";
?>
<div class="container">
  <h2>Log in</h2>
  <form class="form-horizontal col-sm-12 event-form" role="form" method="POST">

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

    <div class="form-group">
      <div class="row">
        <div class="col-sm-4 col-sm-offset-2">
          <input class="btn btn-primary" type="submit" value="Login">
        </div>
      </div>
    </div>
  </form>
</div>
<?php include '../templates/includes/scripts.php' ?>
</body>
</html>