<?php

include '../pre_requisites/dbconn.php';

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    if(isset($_POST['userid']) && isset($_POST['field']) && isset($_POST['value'])){
        $userid = $_POST['userid'];
        $field = $_POST['field'];
        $value = $_POST['value'];
        if($field == 'fullname'){
            $sql = "UPDATE `users` SET `fullname`='$value' WHERE `userid`='$userid';";
            $res = mysqli_query($conn,$sql);
            if($res){echo 'success';}       
        }
        elseif($field == 'email'){
            $sql = "UPDATE `users` SET `email`='$value' WHERE `userid`='$userid';";
            $res = mysqli_query($conn,$sql);
            if($res){echo 'success';}       
        }
        elseif($field == 'password'){
            $sql = "UPDATE `users` SET `password`='$value' WHERE `userid`='$userid';";
            $res = mysqli_query($conn,$sql);
            if($res){echo 'success';}
        }
        elseif($field == 'is_verified'){
            $sql = "UPDATE `users` SET `is_verified`='$value' WHERE `userid`='$userid';";
            $res = mysqli_query($conn,$sql);
            if($res){echo 'success';}
        }
        elseif($field == 'userdelete'){
            $sql = "DELETE FROM `users` WHERE userid='$userid';";
            $res = mysqli_query($conn,$sql);
            if($res){echo 'success';}
        }
    }
    elseif(isset($_POST['fullname']) && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['field'])){
        $field = $_POST['field'];
        if($field == 'addnewuser'){
            $fullname = $_POST['fullname'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $sql = "INSERT INTO `users` (`fullname`,`email`,`password`,`is_verified`,`(city,country) at time of reg`) VALUES ('$fullname','$email','$password','no','unknown(manually added user)');";
            $res = mysqli_query($conn,$sql);
            if($res){echo 'success';}
        }
    }
}
?>