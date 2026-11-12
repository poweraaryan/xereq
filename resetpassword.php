<?php
session_start();

include "pre_requisites/dbconn.php";

$error = false;
$success = false;

if(isset($_COOKIE['registerOTPsent'])){
    header("Location: enterotp.php");
}
elseif((!isset($_GET['email']) && !isset($_GET['identifier'])) || (!isset($_GET['email'])) || (!isset($_GET['identifier']))){
    header("Location: login.php");
}
else{
    $email = $_GET['email'];
    $getidentifier = $_GET['identifier'];

    $sql = "SELECT * FROM `resetpassword` WHERE `email`='$email' AND `identifier`!='linkused';";
    $res = mysqli_query($conn,$sql);
    if(mysqli_num_rows($res) > 0){
        $fth = mysqli_fetch_assoc($res);
        $identifier = $fth['identifier'];
    
        if($getidentifier == $identifier){
            if($_SERVER['REQUEST_METHOD'] == 'POST'){
                $password = $_POST['password'];
                $cpassword = $_POST['confirm_password'];
                if($password == $cpassword){
                    $sql2 = "UPDATE `users` SET `password`='$password' WHERE `email`='$email';";
                    $res2 = mysqli_query($conn,$sql2);
                    $sql3 = "UPDATE `resetpassword` SET `identifier`='linkused' WHERE `email`='$email';";
                    $res3 = mysqli_query($conn,$sql3);
                    setcookie("loggedin","yes",time()+2592000,"/");
                    setcookie("email",$email, time()+2592000,"/");
                    $success = true;
                }
                else{
                    $error = true;
                }
            }
        }
        else{
            header("Location: login.php");
        }
    }
    else{
        header("Location: login.php");
    }

}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>XEREQ | Forgot Password</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;800&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="assets/Logo_Image.png">
    <style>
        :root {
            /* Dark Neumorphic Palette */
            --bg-color: #1a1e23;
            --surface-color: #1a1e23;
            --text-main: #e2e8f0;
            --text-muted: #a0aec0;
            --accent-blue: #00c6ff;
            --accent-red: #ff3366;
            --shadow-light: #2c333a;
            --shadow-dark: #08090a;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Outfit', sans-serif;
            scroll-behavior: smooth;
        }
        .error{
            background:red;
            color:white;
            width:fit-content;
            display:block;
            margin:auto;
            padding:10px;
            border-radius:10px;
        }

        body {
            background-color: var(--bg-color);
            color: var(--text-main);
            overflow-x: hidden;
            line-height: 1.7;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* --- Scroll Animations --- */
        .hidden {
            opacity: 0;
            transform: translateY(40px);
            transition: all 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        }
        .show {
            opacity: 1;
            transform: translateY(0);
        }

        /* --- Fixed Header (Liquid Glass) --- */
        header {
            position: fixed;
            top: 0;
            width: 100%;
            padding: 15px 5%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 1000;
            background: var(--header-bg, rgba(26, 30, 35, 0.6));
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        .logo-container {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
        }

        .logo-img { height: 40px; width: auto; }
        
        .logo-text, .footer-logo { 
            height: 20px; 
            width: auto; 
            margin-top: 2px; 
         filter: var(--filter-logo, invert(0)); transition: filter 0.3s ease;  filter: var(--filter-logo, invert(0)); transition: filter 0.3s ease; }
        .footer-logo {
            height: 28px;
            margin-bottom: 10px;
         filter: var(--filter-logo, invert(0)); transition: filter 0.3s ease; }

        nav ul {
            list-style: none;
            display: flex;
            gap: 35px;
        }

        nav ul li a {
            color: var(--text-main);
            text-decoration: none;
            font-weight: 500;
            font-size: 1rem;
            transition: color 0.3s ease;
        }

        nav ul li a:hover { color: var(--accent-blue); }

        /* --- Main Content Layout --- */
        main {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 120px 20px 60px; /* Padding to account for fixed header */
        }

        /* --- Neumorphic Box --- */
        .login-box {
            background: var(--surface-color);
            border-radius: 20px;
            padding: 50px 40px;
            width: 100%;
            max-width: 450px;
            box-shadow: 10px 10px 20px var(--shadow-dark), 
                       -10px -10px 20px var(--shadow-light);
            text-align: center;
        }

        .login-box h2 {
            font-size: 2.2rem;
            margin-bottom: 10px;
            color: var(--text-main);
            font-weight: 700;
        }

        .login-box p {
            color: var(--text-muted);
            margin-bottom: 30px;
            font-size: 0.95rem;
        }

        .input-group {
            margin-bottom: 25px;
            text-align: left;
        }

        .input-group label {
            display: block;
            margin-bottom: 8px;
            color: var(--text-main);
            font-weight: 500;
            font-size: 0.9rem;
            padding-left: 5px;
        }

        /* Neumorphic Input Fields */
        .neu-input {
            width: 100%;
            padding: 15px 20px;
            border: none;
            border-radius: 12px;
            background: var(--bg-color);
            color: var(--text-main);
            font-size: 1rem;
            font-family: inherit;
            /* Inset shadow for depth */
            box-shadow: inset 5px 5px 10px var(--shadow-dark), 
                        inset -5px -5px 10px var(--shadow-light);
            transition: all 0.3s ease;
        }

        .neu-input:focus {
            outline: none;
            box-shadow: inset 7px 7px 14px var(--shadow-dark), 
                        inset -7px -7px 14px var(--shadow-light);
        }

        .neu-input::placeholder {
            color: #a0aec0;
        }

        /* --- Buttons --- */
        .neu-btn {
            background: var(--bg-color);
            color: var(--accent-blue);
            border: none;
            width: 100%;
            padding: 15px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 1.1rem;
            cursor: pointer;
            box-shadow: 6px 6px 12px var(--shadow-dark), 
                       -6px -6px 12px var(--shadow-light);
            transition: all 0.3s ease;
            margin-top: 10px;
        }

        .neu-btn:hover {
            box-shadow: inset 4px 4px 8px var(--shadow-dark), 
                        inset -4px -4px 8px var(--shadow-light);
            color: var(--accent-red);
        }
        
        .login-links {
            margin-top: 25px;
            font-size: 0.9rem;
        }
        
        .login-links a {
            color: var(--accent-blue);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }
        
        .login-links a:hover {
            color: var(--accent-red);
        }

        /* --- Footer --- */
        footer {
            background: var(--bg-color);
            padding: 60px 5% 30px;
            border-top: 1px solid rgba(255, 255, 255, 0.05);
            text-align: center;
        }

        .footer-content {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 20px;
            margin-bottom: 40px;
        }

        .contact-info {
            display: flex;
            gap: 40px;
            color: var(--text-muted);
            justify-content: center;
            flex-wrap: wrap;
        }
        
        .contact-item {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 1.05rem;
        }

        .contact-item a {
            color: var(--accent-blue);
            text-decoration: none;
            transition: color 0.3s ease;
            font-weight: 500;
        }

        .contact-item a:hover { color: var(--accent-red); }

        .copyright { color: var(--text-muted); font-size: 0.9rem; }

        /* --- Responsive Design --- */
        @media (max-width: 768px) {
            header { padding: 15px 20px; }
            nav ul { display: none; }
            .login-box { padding: 40px 25px; }
        }
        .success{
            display:block;
            background:lightgreen;
            color:black;
            padding:15px;
            border-radius:10px;
            width:fit-content;
            margin:auto;
            text-align:center;
        }
    
        [data-theme="light"] {
            --bg-color: #e6eef5;
            --surface-color: #e6eef5;
            --text-main: #1a202c;
            --text-muted: #4a5568;
            --accent-blue: #0077b6;
            --accent-red: #e53e3e;
            --shadow-light: #ffffff;
            --shadow-dark: #a0b0c5;
            --header-bg: rgba(255, 255, 255, 0.95);
            --border-subtle: rgba(0, 0, 0, 0.1);
            --glass-bg: rgba(255, 255, 255, 0.65);
            --glass-border: rgba(255, 255, 255, 0.8);
            --glass-shadow: 10px 10px 20px rgba(160, 176, 197, 0.6);
            --glass-btn-bg: rgba(255, 255, 255, 0.5);
            --glass-btn-hover: rgba(255, 255, 255, 0.9);
            --cta-shadow: inset 8px 8px 16px rgba(160, 176, 197, 0.6), inset -8px -8px 16px #ffffff;
            --filter-logo: brightness(0);
        }
        body {
            transition: background-color 0.3s ease, color 0.3s ease;
        }
        .floating-theme-btn {
            position: fixed;
            bottom: 30px;
            left: 30px;
            width: 55px;
            height: 55px;
            border-radius: 50%;
            background: var(--bg-color);
            color: var(--accent-blue);
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 6px 6px 15px var(--shadow-dark), -6px -6px 15px var(--shadow-light);
            z-index: 999;
            transition: all 0.3s ease;
        }
        .floating-theme-btn:hover {
            box-shadow: inset 3px 3px 6px var(--shadow-dark), inset -3px -3px 6px var(--shadow-light);
            color: var(--accent-red);
        }

    </style>
</head>
<body>

    <!-- Header -->
    <header>
        <a href="index.php" class="logo-container">
            <img src="assets/Logo_Image.png" alt="X Logo" class="logo-img">
            <img src="assets/Logo_Text.png" alt="XEREQ" class="logo-text">
        </a>
    </header>

    <!-- Main Content: Password reset form -->
    <?php if(!$success){ ?>
    <main>
        <div class="login-box hidden">
            <h2>Reset your Password</h2>
            <p>Reset your password for: <b><?php echo $email; ?></b></p>

            <?php if($error){
                echo '<div class="error"><h4>Passwords do not match</h4></div>';
            } ?>
            
            <!-- Standard form without PHP logic -->
            <form action="<?php echo "resetpassword.php?email=$email&identifier=$identifier"; ?>" method="POST">
                <div class="input-group">
                    <label for="password">Enter New Password</label>
                    <input type="password" id="password" name="password" class="neu-input" placeholder="Enter new password" required>
                </div>
                <div class="input-group">
                    <label for="confirm_password">Confirm Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" class="neu-input" placeholder="Confirm password" required>
                </div>
                
                <button type="submit" class="neu-btn">Reset Password</button>
            </form>
        </div>
    </main>
<?php  } ?>
<?php if($success){ ?>
        <main>
        <div class="login-box hidden">
            <div class="success">
                </h3><b>Password Reset successful<b/><br/>Redirecting to home...</h3>
            </div>
        </div>
    </main>
    <script>
        setInterval(() => {
            window.location.href = "home/";
        }, 1500);
    </script>

    <?php } ?>



    <!-- Scroll Animations Script -->
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add("show");
                    }
                });
            }, { threshold: 0.15, rootMargin: "0px 0px -50px 0px" });

            const hiddenElements = document.querySelectorAll(".hidden");
            hiddenElements.forEach((el) => observer.observe(el));
        });
    </script>

    <!-- Floating Theme Toggle Button -->
    <button id="floatingThemeToggle" class="floating-theme-btn" title="Toggle Theme">
        <svg class="sun-icon" style="display: none;" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="5"></circle><line x1="12" y1="1" x2="12" y2="3"></line><line x1="12" y1="21" x2="12" y2="23"></line><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line><line x1="1" y1="12" x2="3" y2="12"></line><line x1="21" y1="12" x2="23" y2="12"></line><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line></svg>
        <svg class="moon-icon" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path></svg>
    </button>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const floatingBtn = document.getElementById('floatingThemeToggle');
            if(floatingBtn) {
                const sunIcon = floatingBtn.querySelector('.sun-icon');
                const moonIcon = floatingBtn.querySelector('.moon-icon');

                function setTheme(isDark) {
                    if (isDark) {
                        document.documentElement.setAttribute('data-theme', 'dark');
                        localStorage.setItem('theme', 'dark');
                        if(sunIcon) sunIcon.style.display = 'none';
                        if(moonIcon) moonIcon.style.display = 'inline-block';
                    } else {
                        document.documentElement.setAttribute('data-theme', 'light');
                        localStorage.setItem('theme', 'light');
                        if(sunIcon) sunIcon.style.display = 'inline-block';
                        if(moonIcon) moonIcon.style.display = 'none';
                    }
                }

                const savedTheme = localStorage.getItem('theme');
                if (savedTheme === 'light') {
                    setTheme(false);
                } else {
                    setTheme(true);
                }

                floatingBtn.addEventListener('click', (e) => {
                    e.preventDefault();
                    const isCurrentlyDark = document.documentElement.getAttribute('data-theme') !== 'light';
                    setTheme(!isCurrentlyDark);
                });
            }
        });
    </script>

</body>
</html>