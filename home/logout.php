<?php

session_start();
session_unset();
session_destroy();

include "../pre_requisites/dbconn.php";

if(isset($_COOKIE['email'])){
    $email = $_COOKIE['email'];
    $sql = "UPDATE `logins_and_logouts` SET `logged_out`='yes',`time_of_logout`=CURRENT_TIMESTAMP WHERE `email`='$email';";
    $res = mysqli_query($conn,$sql);
}

setcookie("loggedin","yes",time()-3600,"/");
setcookie("email",'NA', time()-3600,"/");
setcookie("registerOTPsent","yes",time()-3600,"/");

header("Location:../login.php");

?>