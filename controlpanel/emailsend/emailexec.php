<?php

include "../../pre_requisites/dbconn.php";
include "emailconfig.php";

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    if(isset($_POST['email']) && isset($_POST['subject']) && isset($_POST['body']) && isset($_POST['type'])){
        $fullname = '';        
        $email = $_POST['email'];
        $subject = $_POST['subject'];
        $body = $_POST['body'];
        $type = $_POST['type'];
        if($type == 'single'){
            if(sendemail($email,$subject,$body)){
                echo 'Email sent!';
                $mail->smtpClose();
            }
            else{
                echo "Error";
            }
        }
        elseif($type == 'multiple'){
            $email = '';
            $fullname = '';
            $password = '';
            $sql = "SELECT * FROM `users`;";
            $res = mysqli_query($conn,$sql);
            $count = 0;
            $totalemails = mysqli_num_rows($res);
            while($row = mysqli_fetch_assoc($res)){
                $fullname = $row['fullname'];
                $email = $row['email'];
                $newbody = str_replace(['{{fullname}}','{{email}}'], [$fullname,$email], $body);
                $fullname = $row['fullname'];
                $password = $row['password'];
                if(sendemail($row['email'],$subject,$newbody)){
                    $count++;
                }
            }
                $mail->smtpClose(); 
            	$fails = $totalemails - $count;
            	if($count != 0){
                    echo "Sent $count emails out of $totalemails total emails.\n$fails failed";
                }
            	else{
                    echo "Error sending emails";
                }
                
        }
    }
}


?>