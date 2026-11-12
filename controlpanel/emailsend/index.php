<?php

if(!isset($_COOKIE['cploggedin'])){
    header("Location:/controlpanel/");
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Send Emails</title>
    <style>
    *{
        margin:0;
        padding:0;
        box-sizing: border-box;
        font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
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
.email_body{
    display:block;
    margin:0 auto 20px auto;
    width:400px;
    height:300px;
    font-size:18px;
    padding:5px;
}
.send_single{
    display:block;
    margin: 20px auto 0 auto;
    text-align:center;
    border:2px solid black;
    padding:20px;
    border-radius:10px;
    min-width:600px;
    max-width:fit-content;
    height:fit-content;
}
.send_multiple{
    display:block;
    margin: 40px auto 40px auto;
    text-align:center;
    border:2px solid black;
    padding:20px;
    border-radius:10px;
    min-width:600px;
    max-width:fit-content;
    height:fit-content;
}
.loading{
            position:fixed;
            width:100%;
            height:100vh;
            justify-content:center;
            align-items:center;
            z-index: 1;
            background:rgba(0,0,0,0.7);
            font-size:30px;        
            color:white;    
        }
.dp-flex{
    display:flex;
}
.dp-none{
    display:none;
}
</style>
</head>
<body>
    <div class="loading dp-none">Please Wait...</div>
    <header>
        <a href="/controlpanel/report.php" style="text-decoration:none;color:white;"><h1>XEREQ CP</h1></a>
        <button class="btn" onclick="window.location.href='logout.php'" style='position:absolute;right:0;margin-right:20px;'>Logout</button>
    </header>

    <div class="container">
        <div class='send_single'>
            <h2>Send email to single recipent</h2>
            <input type="email" class="inp" placeholder="Enter Email" id='single_email'>
            <input type="text" class="inp" placeholder="Enter Subject" id='single_subject'>
            <textarea class='email_body' id='single_email_body' placeholder='Type Email Body'></textarea>
            <button class="btn" id='single_send_btn'>Send</button>
        </div>

        <div class="send_multiple">
            <h2>Send email to all users</h2>
            <input type="text" class="inp" placeholder="Enter Subject" id='multiple_subject'>
            <textarea class='email_body' id='multiple_email_body' placeholder='Type Email Body'></textarea>
            <button class="btn" id='multiple_send_btn'>Send</button>
        </div>
    </div>
<script src="https://code.jquery.com/jquery-4.0.0.js" integrity="sha256-9fsHeVnKBvqh3FB2HYu7g2xseAZ5MlN6Kz/qnkASV8U=" crossorigin="anonymous"></script>
    <script>    
    
    const single_send_btn = document.getElementById('single_send_btn');
    const multiple_send_btn = document.getElementById('multiple_send_btn');
    

    single_send_btn.addEventListener('click',()=>{
        const single_email = document.getElementById('single_email');
        const single_subject = document.getElementById('single_subject');
        const single_email_body = document.getElementById('single_email_body');
        if(single_email.value !== "" && single_subject.value !== "" && single_email_body.value !== ""){
            document.querySelector('.loading').classList.remove('dp-none');
            document.querySelector('.loading').classList.add('dp-flex');
            $.post('emailexec.php',{email:single_email.value,subject:single_subject.value,body:single_email_body.value,type:'single'},function(data){
          
                alert(data);
                document.querySelector('.loading').classList.remove('dp-flex');
                document.querySelector('.loading').classList.add('dp-none');
                single_email.value = "";
                single_subject.value = "";
                single_email_body.value = "";
                
            })
        }
    })

    multiple_send_btn.addEventListener('click',()=>{
        const multiple_subject = document.getElementById('multiple_subject');
        const multiple_email_body = document.getElementById('multiple_email_body');
        if(multiple_subject.value !== "" && multiple_email_body.value !== ""){
            document.querySelector('.loading').classList.remove('dp-none');
            document.querySelector('.loading').classList.add('dp-flex');
            $.post('emailexec.php',{email:'NA',subject:multiple_subject.value,body:multiple_email_body.value,type:'multiple'},function(data){
                alert(data);
                document.querySelector('.loading').classList.remove('dp-flex');
            	document.querySelector('.loading').classList.add('dp-none');
                multiple_subject.value = "";
                multiple_email_body.value = "";
            })
        }
    })


    </script>

</body>
</html>