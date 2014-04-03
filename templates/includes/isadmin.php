<?php

$uname = $_SESSION["username"];
global $is_admin;
$isadmin_query = mysqli_query($con, "SELECT is_admin FROM Users WHERE username = '$uname'");
$isadmin_result = mysqli_fetch_array($isadmin_query);
if ($isadmin_result['is_admin'] == 0){
  $is_admin = false;
} else {
  $is_admin = true;
}
?>