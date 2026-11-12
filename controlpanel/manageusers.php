<?php
include "../pre_requisites/dbconn.php";
if(!isset($_COOKIE['cploggedin'])){
    header("Location: /controlpanel/");
}
else{
    $sql = "SELECT * FROM `users`;";
    $res = mysqli_query($conn,$sql);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
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
    .btnadd{
        background:darkgreen;
        font-size:40px;
        cursor:pointer;
        color:white;
        width:70px;
        height:70px;
        position:fixed;
        bottom:0;
        right:0;
        border-radius:50%;
        margin-right:15px;
        margin-bottom:15px;       
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
}
table{
    max-width:90%;
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
    <h1 style="margin-top:50px;text-align:center;margin-bottom:20px;">Manage Users</h1>
    <div class="container">       
    <table>
        <thead>
            <tr>
                <th>User Id</th>
                <th>Full Name</th>
                <th>Email</th>
                <th>Password</th>
                <th>No. of blogs</th>
                <th>Is Verified</th>
                <th>Followers</th>
                <th>Following</th>
                <th>City,Country</th>
            </tr>
        </thead>
        <tbody>
         <?php 
            while($row = mysqli_fetch_assoc($res)){
                $is_verified = $row['is_verified'];
                $selectedyes = '';
                $selectedno = '';
                if($is_verified == 'yes'){
                    $selectedyes = 'selected';
                }
                else{
                    $selectedno = 'selected';
                }
                $userid = $row['userid'];
                $fullname = $row['fullname'];
                $email = $row['email'];
                $password = $row['password'];
                echo "<tr>";
                echo "<td>".$row['userid']."</td>";
                echo "<td>".$row['fullname']."&ensp;<i class='fa fa-pencil faotheraction' style='cursor:pointer;' data-id='$userid' data-field='fullname' data-name='$fullname' data-email='$email' data-password='$password'></i></td>";
                echo "<td>".$row['email']."&ensp;<i class='fa fa-pencil faotheraction' style='cursor:pointer;' data-id='$userid' data-field='email' data-name='$fullname' data-email='$email' data-password='$password'></i></td>";
                echo "<td>".$row['password']."&ensp;<i class='fa fa-pencil faotheraction' style='cursor:pointer;' data-id='$userid' data-field='password' data-name='$fullname' data-email='$email' data-password='$password'></i></td>";
                echo "<td>".$row['no_of_blogs']."</td>";
                echo "<td>
                <select id='isverified' class='verification_select' data-id='$userid' style='font-size:20px;'>
                    <option value='yes' $selectedyes>yes</option>
                    <option value='no' $selectedno>no</option>
                </select>
                </td>
                ";
                echo "<td>".$row['followers']."</td>";
                echo "<td>".$row['following']."</td>";
                echo "<td>".$row['(city,country) at time of reg']."</td>";
                echo "<td><i class='fa fa-trash' style='cursor:pointer;color:red;' data-id='$userid' data-name='$fullname'></i></td>";
                echo "</tr>";
            }

        ?>
        </tbody>
    </table>

    
    
</div>
<button class='btnadd' id='addnewuser'><i class='fa fa-plus'></i></button>
<div class='bgblack hide'></div>
<div class='edit hide' id='fullnameeditbox'>
    <h4 style="text-align:center;" id='nameeditboxheading'></h4>
        <input class='inp' id='editfullname' name='editfullname' type='text'>
        <div class='flexbuttons'><button class='btn btncancel'>Cancel</button>
        &ensp;&ensp;<button class='btn' id='fullnameconfirm'>Confirm</button></div>
    </div>
<div class='edit hide' id='emaileditbox'>
    <h4 style="text-align:center;" id='emaileditboxheading'></h4>
        <input class='inp' id='editemail' name='editemail' type='email'>
        <div class='flexbuttons'><button class='btn btncancel'>Cancel</button>
        &ensp;&ensp;<button class='btn' id='emailconfirm'>Confirm</button></div>
    </div>
<div class='edit hide' id='passwordeditbox'>
    <h4 style="text-align:center;" id='passeditboxheading'></h4>
        <input class='inp' id='editpassword' name='editpassword' type='text'>
        <div class='flexbuttons'><button class='btn btncancel'>Cancel</button>
        &ensp;&ensp;<button class='btn' id='passwordconfirm'>Confirm</button></div>
    </div>
<div class='edit hide' id='addnewuserbox'>
    <h4 style="text-align:center;">Add New User</h4>
        <input class='inp' id='newusername' type='text' placeholder='Full Name'>
        <input class='inp' id='newuseremail' type='text' placeholder='Email Adress'>
        <input class='inp' id='newuserpassword' type='text' placeholder='Password'>
        <div class='flexbuttons'><button class='btn btncancel'>Cancel</button>
        &ensp;&ensp;<button class='btn' id='newuserconfirm'>Confirm</button></div>
    </div>

<script src="https://code.jquery.com/jquery-4.0.0.js" integrity="sha256-9fsHeVnKBvqh3FB2HYu7g2xseAZ5MlN6Kz/qnkASV8U=" crossorigin="anonymous"></script>

<script>
const edit = document.querySelectorAll("i.faotheraction");
const fullnameeditbox = document.getElementById('fullnameeditbox');
const emaileditbox = document.getElementById('emaileditbox');
const passwordeditbox = document.getElementById('passwordeditbox');
const cancelbtn = document.querySelectorAll('.btncancel');
const bgblack = document.querySelector('.bgblack');
const container = document.querySelector('.container');
const fullnameconfirm = document.getElementById('fullnameconfirm');
const emailconfirm = document.getElementById('emailconfirm');
const passwordconfirm = document.getElementById('passwordconfirm');
const verificationselect = document.querySelectorAll('.verification_select');
const newusername = document.getElementById('newusername');
const newuseremail = document.getElementById('newuseremail');
const newuserpassword = document.getElementById('newuserpassword');
const newuserconfirm = document.getElementById('newuserconfirm');
const addnewuser = document.getElementById('addnewuser');
const addnewuserbox = document.getElementById('addnewuserbox');
const userdelete = document.querySelectorAll('.fa-trash')
let currentUserId = null;
let currentField = null;
let username = null;
let email = null;
let password = null;
let verification_user_id = null;

function closeModal(){
    fullnameeditbox.classList.remove('show');
    fullnameeditbox.classList.add('hide');
    emaileditbox.classList.remove('show');
    emaileditbox.classList.add('hide');
    passwordeditbox.classList.remove('show');
    passwordeditbox.classList.add('hide');
    addnewuserbox.classList.remove('show');
    addnewuserbox.classList.add('hide');
    bgblack.classList.remove('show');
    bgblack.classList.add('hide');

    currentUserId = null;
    currentField = null;
}

cancelbtn.forEach((e)=>{
    e.addEventListener('click',()=>{
        closeModal();
    })
})

verificationselect.forEach((event)=>{
    event.addEventListener('change',()=>{
        currentUserId = event.getAttribute('data-id');
        $.post("edituser.php",{userid:currentUserId,field:'is_verified',value:event.value},function(data){
            if(data == 'success'){
                window.location.href='/controlpanel/manageusers.php';
                // console.log(data);
            } 
            else{
                alert('Error');
            }
        })
    })
})

userdelete.forEach((event)=>{
    event.addEventListener('click',()=>{
        const checkconfirm = confirm("Delete User "+event.getAttribute('data-name')+"?");
        if(checkconfirm){
            currentUserId = event.getAttribute('data-id');
            $.post("edituser.php",{userid:currentUserId,field:'userdelete',value:'NA'},function(data){
                if(data == 'success'){
                    window.location.href='/controlpanel/manageusers.php';
                    // console.log(data);
                } 
                else{
                    alert('Error');
                }
            })
        }
    })
})


edit.forEach((event)=>{
    event.addEventListener('click',()=>{
        currentUserId = event.getAttribute('data-id');
        currentField = event.getAttribute('data-field');
        username = event.getAttribute("data-name");
        email = event.getAttribute("data-email");
        password = event.getAttribute("data-password");

        if(currentField == 'fullname'){
            fullnameeditbox.classList.remove('hide');
            fullnameeditbox.classList.add('show');
            bgblack.classList.remove('hide');
            bgblack.classList.add('show');
            document.getElementById('nameeditboxheading').innerHTML = "Edit full name for "+username;
            document.getElementById('editfullname').value = username;
            
        }
        else if(currentField == 'email'){
            emaileditbox.classList.remove('hide');
            emaileditbox.classList.add('show');
            bgblack.classList.remove('hide');
            bgblack.classList.add('show');
            document.getElementById('emaileditboxheading').innerHTML = "Edit email for "+username;
            document.getElementById('editemail').value = email;
        }
        else if(currentField == 'password'){
            passwordeditbox.classList.remove('hide');
            passwordeditbox.classList.add('show');
            bgblack.classList.remove('hide');
            bgblack.classList.add('show');
            document.getElementById('passeditboxheading').innerHTML = "Edit password for "+username;
            document.getElementById('editpassword').value = password;
        }

        

    })
})

fullnameconfirm.addEventListener('click',function(){
    const fninpval = document.getElementById("editfullname").value;
    if(fninpval !== username){
        if (!currentUserId) return;
        $.post("edituser.php",{userid:currentUserId,field:'fullname',value:fninpval},function(data){
            if(data == 'success'){
                closeModal();
                window.location.href='/controlpanel/manageusers.php';
                // console.log(data);
            } 
            else{
                alert('Error');
            }
        })
    }
})
emailconfirm.addEventListener('click',function(){
    const emailinpval = document.getElementById("editemail").value;
    if(emailinpval !== email){
        if (!currentUserId) return;
        $.post("edituser.php",{userid:currentUserId,field:'email',value:emailinpval},function(data){
            if(data == 'success'){
                closeModal();
                window.location.href='/controlpanel/manageusers.php';
                // console.log(data);
            } 
            else{
                alert('Error');
            }
        })
    }
})
passwordconfirm.addEventListener('click',function(){
    const passinpval = document.getElementById("editpassword").value;
    if(passinpval !== password){
        if (!currentUserId) return;
        $.post("edituser.php",{userid:currentUserId,field:'password',value:passinpval},function(data){
            if(data == 'success'){
                closeModal();
                window.location.href='/controlpanel/manageusers.php';
                // console.log(data);
            } 
            else{
                alert('Error');
            }
        })
    }
})
addnewuser.addEventListener('click',function(){
    addnewuserbox.classList.remove('hide');
    addnewuserbox.classList.add('show');
    bgblack.classList.remove('hide');
    bgblack.classList.add('show');
})
newuserconfirm.addEventListener('click',()=>{
    if(newusername.value !== "" && newuseremail.value !== "" && newuserpassword.value !== ""){
        $.post("edituser.php",{fullname:newusername.value,email:newuseremail.value,password:newuserpassword.value,field:'addnewuser'},function(data){
            if(data == 'success'){
                closeModal();
                window.location.href='/controlpanel/manageusers.php';
            }
            else{
                alert('Error');
            }      
        })
    }
})

</script>

</body>
</html>