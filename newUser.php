<?php
define('DB_SERVER', 'panther.cs.middlebury.edu');
define('DB_USERNAME', 'dsilver');
define('DB_PASSWORD', 'dsilver122193');
define('DB_DATABASE', 'dsilver_EventsCalendar');

$con = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE) or die("Could not connect.");

$sent = false;
//$password="";
//$username="";
//$full_name="";
//$email="";
/*
if (isset($_POST['register']) && !empty($_POST['register'])){
    //Create errors array
    $errors = array();


    //check to make sure the fields are not empty

    if (empty($_POST["username"]) && !isset($_POST["username"])){
        $errors[] = "Please enter a username";
    }
    else{
        $username = ($_POST['username']);
    }
    if (empty($_POST["password"])){
        $errors[] = "Please enter a password";
    }
    else{
        $password = $_POST['password'];
    }
    if ( count($errors) > 0)
    {
        foreach ($errors as $output) {
            echo "{$output} <br>";
        }
    } else {
        $sent = true;
    }
}*/



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
    <form action="newUser.php" class="form-horizontal col-sm-12 event-form"  method="POST">



        <!-- Username -->
        <div class="form-group">
            <div class="row">
                <label class="col-sm-2 control-label" for="username">Username</label>
                <div class="col-sm-4">
                    <input type="text" name="username" id="username" class="form-control" maxlength="20" required="">
                </div>
            </div>
        </div>


        <!-- Full Name -->
        <div class="form-group">
            <div class="row">
                <label class="col-sm-2 control-label" for="full_name">Full Name</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" name="full_name" id="full_name" maxlength="40" >
                </div>
            </div>
        </div>


        <!-- must secure the password-->

        <!-- Password -->
        <div class="form-group">
            <div class="row">
                <label class="col-sm-2 control-label" for="pass">Password</label>
                <div class="col-sm-4">
                    <input type="password" name="password" id="pass" class="form-control" maxlength="64" >
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
                    <input type="text" name="email" id="email" class="form-control" maxlength="45">
                </div>
            </div>
        </div>






        <div class="form-group">
            <div class="row">
                <div class="col-sm-4 col-sm-offset-2">
                    <input class="btn btn-primary" type="submit" name="register" value="Register">
                </div>
            </div>
        </div>
    </form>
</div>

</body>
</html>



