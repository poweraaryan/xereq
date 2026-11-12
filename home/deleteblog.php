<?php
include "../pre_requisites/dbconn.php";

if(isset($_POST['blogid'])){
    $blogid = $_POST['blogid'];
    $email = $_POST['email'];
    $sql = "DELETE FROM `blogs` WHERE blogid=$blogid";
    $sql2 = "UPDATE `users` SET no_of_blogs = no_of_blogs-1 WHERE email='$email';";
    if(mysqli_query($conn,$sql) && mysqli_query($conn,$sql2)){
        echo "success";
    }
}


?>