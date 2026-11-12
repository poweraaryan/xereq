<?php
if(!isset($_COOKIE['cploggedin'])){
    header("Location:/controlpanel/");
}
else{
    //
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
        transition:0.25s linear;
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
    position:relative;
}
header h1{
    margin-left:20px;
}
.homebtn{
    background:#3f5373;
    padding:18px;
    font-size:25px;
    cursor:pointer;
    color:white;
    border-radius:12px;
    display:block;
    margin:20px auto; 
    width:400px;  
}
.homebtn:hover{
    background:black;
}
.container{
    display:block;
    margin:auto;
}
    </style>
</head>
<body>
    <header>
        <a href="/controlpanel/report.php" style="text-decoration:none;color:white;"><h1>XEREQ CP</h1></a>
        <button class="btn" onclick="window.location.href='logout.php'" style='position:absolute;right:0;margin-right:20px;'>Logout</button>
    </header>
    <div class="container">
        <button class="homebtn" onclick="window.location.href='visitors.php'">Visitors</button>
        <button class="homebtn" onclick="window.location.href='manageusers.php'">Manage Users</button>
        <button class="homebtn" onclick="window.location.href='sentotps.php'">View sent OTPs</button>
        <button class="homebtn" onclick="window.location.href='logins_logouts.php'">Logins and Logouts</button>
        <button class="homebtn" onclick="window.location.href='passresets.php'">View Password Resets</button>
        <button class="homebtn" onclick="window.location.href='emailsend/'">Send Emails</button>
    </div>
</body>
</html>