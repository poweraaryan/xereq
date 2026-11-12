<?php

$username = "aaryan";
$password = "QazxsW@12";

if(isset($_COOKIE['cploggedin'])){
    header("Location: report.php");
}

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $unameinp = $_POST['username'];
    $passinp = $_POST['password'];
    if($unameinp == $username && $passinp == $password){
        setcookie("cploggedin","yes",time()+86400*30,"/");
        header("Location: report.php");
    }
    else{
        echo "<script>alert('Invalid Credentials');</script>";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>XEREQ Control Panel</title>
    <style>
    *{
        margin:0;
        padding:0;
        box-sizing: border-box;
        font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
    }
    form{
        display:block;
        margin:auto;
        text-align:center;
        margin-top:60px;
        border:2px dashed black;
        padding:75px;
        width:fit-content;
        height:fit-content;
        border-radius:12px;
    }
    .inp{
        display:block;
        margin:20px auto;
        outline:none;
        border:2px solid black;
        padding:12px;
        font-size:20px;
        width:250px;
        border-radius:12px;
    }
    .btn{
        background:blueviolet;
        padding:12px;
        font-size:20px;
        cursor:pointer;
        color:white;
        border-radius:12px;
    }
header{
    width:100%;
    height:75px;
    display:flex;
    align-items:center;
    background:grey;
    color:white;
    font-size:15px;
}
header h1{
    margin-left:20px;
}
    </style>
</head>
<body>
    <header>
        <h1>XEREQ CP</h1>
    </header>   
    <form method="post">
        <h1>Login</h1>
        <input type="text" class="inp" name="username" placeholder="Username" required>
        <input type="password" class="inp" name="password" placeholder="Password" required>
        <button type="submit" class="btn">Login</button>
    </form>
</body>
</html>