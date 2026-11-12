<?php
include "../pre_requisites/dbconn.php";
if(!isset($_COOKIE['cploggedin'])){
    header("Location: /controlpanel/");
}
else{
    $sql = "SELECT * FROM `resetpassword`;";
    $res = mysqli_query($conn,$sql);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password resets</title>
    <style>
        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
            font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
        transition:0.25s linear;
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
.btn{
        background:blueviolet;
        padding:12px;
        font-size:20px;
        cursor:pointer;
        color:white;
        border-radius:12px;
        overflow-x:none;
    }
header h1{
    margin-left:20px;
}
.container{
    display:flex;
    justify-content:center;
}
th,td{
    border: 2px solid black;
    padding:10px;
    font-size:20px;
    overflow-x:auto;
}
table{
    max-width:90%;
    table-layout: fixed;
    width:100%;
}
.edit{
    position:absolute;
    top:50%;
    left:50%;
    transform:translate(-50%,-50%);
    display:flex;
    flex-direction:column;
    justify-content:center;
    padding:30px;
    background:#635b45;
    border-radius:12px;
    color:white;
    z-index:2;
}
.show{
    display:flex;
}
.hide{
    display:none;
}
.bgblack{
    position:fixed;
    width:100%;
    height:100vh;
    background:rgba(0,0,0,0.5);
    z-index:1;
    top:0;
    left:0;
}
.flexbuttons{
    display:flex;
    justify-content:center;
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
.pointernone{
    pointer-events:none;
}
    </style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
    <?php include "header.php"; ?>
    <h1 style="margin-top:50px;text-align:center;margin-bottom:20px;">Password Resets</h1>
    <div class="container">       
    <table>
        <thead>
            <tr>
                <th>S.no</th>
                <th>Email</th>
                <th>Identifier</th>
                <th>Link</th>
                <th>Link gen time</th>
            </tr>
        </thead>
        <tbody>
         <?php 
            while($row = mysqli_fetch_assoc($res)){
                echo "<tr>";
                echo "<td>".$row['s.no']."</td>";
                echo "<td>".$row['email']."</td>";
                echo "<td>".$row['identifier']."</td>";
                echo "<td>".$row['link']."</td>";
                echo "<td>".$row['time']."</td>";
                echo "</tr>";
            }

        ?>
        </tbody>
    </table>
    
</div>





</body>
</html>