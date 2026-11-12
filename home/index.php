<?php
session_start();
include "../pre_requisites/dbconn.php";
include "../emailconfig.php";

if(!isset($_COOKIE['loggedin']) && !isset($_COOKIE['email'])){
    header("location:/");
}

else{

    $email = $_COOKIE['email'];
    $sql = "SELECT * FROM `users` WHERE `email`='$email';";
    $res = mysqli_query($conn,$sql);
    $info = mysqli_fetch_assoc($res);
    $fullname = $info['fullname'];
    $parts = explode(' ', $fullname);
    $firstname = $parts[0];
    $no_of_blogs_posted = $info['no_of_blogs'];
    $is_verified = $info['is_verified'];
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        if(isset($_POST['claimtick'])){
            $sql = "UPDATE `users` SET `is_verified` = 'yes' WHERE `email`='$email';";
            $res = mysqli_query($conn,$sql);
            header("Location: /home/profile.php");
        }
    }

    elseif(isset($_SESSION['userfirsttime'])&& ($_SESSION['userfirsttime'] == "yes")){
        $regsubject = 'Welcome to XEREQ';
                $regbody = "
                
                <!DOCTYPE html>
<html lang='en' xmlns='http://www.w3.org/1999/xhtml' xmlns:v='urn:schemas-microsoft-com:vml' xmlns:o='urn:schemas-microsoft-com:office:office'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>Welcome to XEREQ</title>
    <!-- Import Website Font -->
    <link href='https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap' rel='stylesheet'>
    <style>
        /* Base Reset */
        body, table, td, a { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
        table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
        img { -ms-interpolation-mode: bicubic; border: 0; outline: none; text-decoration: none; }
        
        body {
            margin: 0 !important;
            padding: 0 !important;
            width: 100% !important;
            background-color: #1a1e23;
            font-family: 'Outfit', Arial, sans-serif;
            color: #e2e8f0;
            -webkit-font-smoothing: antialiased;
        }

        /* --- XEREQ Dark Neumorphic Email Theme --- */
        .email-wrapper {
            width: 100%;
            background-color: #1a1e23;
            padding: 40px 20px;
        }

        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #1a1e23;
            border-radius: 20px;
            /* Neumorphic Shadow */
            box-shadow: 10px 10px 20px #08090a, -10px -10px 20px #2c333a;
            border: 1px solid rgba(255, 255, 255, 0.03);
            overflow: hidden;
        }

        .header {
            padding: 40px 30px 20px;
            text-align: center;
        }

        /* Fallback for logo if images are blocked */
        .brand-text {
            margin: 0;
            font-size: 32px;
            font-weight: 800;
            letter-spacing: 3px;
            background: linear-gradient(135deg, #00c6ff, #ff3366);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            color: #00c6ff; /* Fallback for clients not supporting gradient text */
        }

        .content {
            padding: 20px 40px 40px;
            text-align: center;
        }

        .content h1 {
            color: #e2e8f0;
            font-size: 24px;
            font-weight: 700;
            margin-top: 0;
            margin-bottom: 15px;
        }

        .content p {
            color: #a0aec0;
            font-size: 16px;
            line-height: 1.7;
            margin-bottom: 25px;
            font-weight: 400;
        }

        .btn-container {
            margin-top: 35px;
            margin-bottom: 15px;
        }

        .neu-btn {
            display: inline-block;
            background-color: #1a1e23;
            color: #00c6ff;
            text-decoration: none;
            padding: 16px 35px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 16px;
            /* Neumorphic Button Shadow */
            box-shadow: 6px 6px 12px #08090a, -6px -6px 12px #2c333a;
            border: 1px solid rgba(255, 255, 255, 0.02);
            transition: all 0.3s ease;
        }

        .neu-btn:hover {
            color: #ff3366 !important;
        }

        .footer {
            background-color: #1a1e23;
            padding: 30px;
            text-align: center;
            border-top: 1px solid rgba(255, 255, 255, 0.05);
        }

        .footer p {
            margin: 5px 0;
            color: #718096;
            font-size: 13px;
            line-height: 1.5;
        }

        .footer a {
            color: #00c6ff;
            text-decoration: none;
        }

        /* --- Mobile Responsiveness --- */
        @media screen and (max-width: 600px) {
            .email-wrapper { padding: 20px 10px; }
            .content { padding: 20px 25px 30px; }
            .content h1 { font-size: 22px; }
            .content p { font-size: 15px; }
            .neu-btn { padding: 14px 25px; width: 80%; box-sizing: border-box; }
        }
    </style>
</head>
<body>
    <table role='presentation' border='0' cellpadding='0' cellspacing='0' class='email-wrapper'>
        <tr>
            <td align='center'>
                <!-- Main Card Container -->
                <table role='presentation' border='0' cellpadding='0' cellspacing='0' class='email-container'>
                    
                    <!-- Header / Logo -->
                    <tr>
                        <td class='header'>
                            <!-- Optional: You can replace this text with your actual logo image by using an <img> tag pointing to a hosted absolute URL of your logo -->
                            <h2 class='brand-text'>XEREQ</h2>
                        </td>
                    </tr>

                    <!-- Body Content -->
                    <tr>
                        <td class='content'>
                            <h1>Welcome to the Community!</h1>
                            <p>Hi <strong>$fullname</strong>,</p>
                            <p>Thank you for registering! We are thrilled to have you on board. Dive into a world where words meet aesthetic design, and start exploring or publishing breathtaking blogs today.</p>
                            
                            <!-- Neumorphic CTA Button -->
                            <div class='btn-container'>
                                <!-- Replace with your actual live domain URL -->
                                <a href='https://xereq.ct.ws/login.php' class='neu-btn'>Go to Dashboard</a>
                            </div>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td class='footer'>
                            <p>Read, write, and inspire on the most aesthetic platform.</p>
                            <p>Need help? Contact us at <a href='mailto:aaryanofc@gmail.com'>aaryanofc@gmail.com</a></p>
                            <p style='margin-top: 15px; opacity: 0.6;'>&copy; 2026 XEREQ. All rights reserved.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
                
                
                
                
                
                
                ";
        sendemail($email,$regsubject,$regbody);
        $_SESSION['userfirsttime'] = "no";
    }



}



?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>XEREQ | Home</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;800&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="assets/Logo_Image.png">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        .modal-btn {
    flex: 1;
    padding: 14px;
    border-radius: 12px;
    font-weight: 600;
    font-size: 1rem;
    cursor: pointer;
    border: none;
    transition: all 0.3s ease;
    font-family: 'Outfit', sans-serif;
}
.btn-save {
    background: var(--bg-color);
    color: var(--accent-blue);
    box-shadow: 4px 4px 8px var(--shadow-dark), -4px -4px 8px var(--shadow-light);
}
.btn-save:hover {
    box-shadow: inset 2px 2px 4px var(--shadow-dark), inset -2px -2px 4px var(--shadow-light);
    color: var(--accent-blue);
}
    </style>
</head>
<body>

    <!-- Header (No links, only toggle button) -->
    <header>
        <a href="index.php" class="logo-container">
            <img src="assets/Logo_Image.png" alt="X Logo" class="logo-img">
            <img src="assets/Logo_Text.png" alt="XEREQ" class="logo-text">
        </a>
        <button class="menu-btn" id="openSidebar">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="3" y1="12" x2="21" y2="12"></line>
                <line x1="3" y1="6" x2="21" y2="6"></line>
                <line x1="3" y1="18" x2="21" y2="18"></line>
            </svg>
        </button>
    </header>

    <!-- Sidebar Overlay & Menu -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    <aside class="sidebar" id="sidebar">
        <button class="close-sidebar" id="closeSidebar">&times;</button>
        
        <div class="sidebar-profile">
            <!-- Dynamic initial goes here -->
            <div class="avatar" style="text-transform: capitalize;"><?php echo $firstname[0]; ?></div> 
            <div style="display:flex;justify-content:center;align-items:center;"><h3 style="color: var(--text-main); font-size: 1.4rem;"><?php echo $firstname; ?></h3>
            <?php if($is_verified == 'yes'){echo '<img src="assets/verified.png" style="width:25px;height:25px;">';} ?>
            </div>
            <p style="color: var(--text-muted); font-size: 0.9rem;"><?php echo $email; ?></p>
        </div>

        <ul class="sidebar-links">
            <li>
                <a href="profile.php">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                    My Profile
                </a>
            </li>
            <li>
                <a href="myblogs.php">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
    <polyline points="14 2 14 8 20 8"></polyline>
    <line x1="16" y1="13" x2="8" y2="13"></line>
    <line x1="16" y1="17" x2="8" y2="17"></line>
    <polyline points="10 9 9 9 8 9"></polyline>
</svg>
                    My Blogs
                </a>
            </li>
            <!-- THEME TOGGLE ADDED HERE -->
            <li>
                <a href="#" id="themeToggleBtn">
                    <svg class="sun-icon" style="display: none;" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="5"></circle><line x1="12" y1="1" x2="12" y2="3"></line><line x1="12" y1="21" x2="12" y2="23"></line><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line><line x1="1" y1="12" x2="3" y2="12"></line><line x1="21" y1="12" x2="23" y2="12"></line><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line></svg>
                    <svg class="moon-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path></svg>
                    <span id="themeToggleText">Switch to Light Mode</span>
                </a>
            </li>
            <li style="margin-top: 40px; border-top: 1px solid rgba(255,255,255,0.05); padding-top: 20px;">
                <a href="../logout.php" class="logout">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
                    Logout
                </a>
            </li>
        </ul>
    </aside>

    <!-- Main Content -->
    <main>

        <!-- Trending Blogs Header -->
        <div class="page-header hidden">
            <h1>Top Stories</h1>
        </div>

        <div class="blog-grid">
            
            <?php

                $sql2 = "SELECT * FROM `blogs` ORDER BY `blogid` DESC LIMIT 4";
                $res2 = mysqli_query($conn,$sql2);
                while($row = mysqli_fetch_assoc($res2)){
                    $title = $row['title'];
                    $desp = $row['description'];
                    $datetime = $row['datetimeofupload'];
                    $blogemail = $row['email'];
                    $blogid = $row['blogid'];
                    $namesql = "SELECT * from `users` WHERE `email`='$blogemail';";
                    $nameres = mysqli_query($conn,$namesql);
                    $fetchdetails = mysqli_fetch_assoc($nameres);
                    $fullname_blog_card = $fetchdetails['fullname'];
                    $userid = $fetchdetails['userid'];
                    $checkverified = $fetchdetails['is_verified'];
                    if($checkverified == 'yes'){
                        $verifiedimg = "<img src='assets/verified.png' style='height:20px;width:20px;'>";
                    }
                    else{
                        $verifiedimg = "";
                    }
                    
                    echo '<div class="blog-card hidden">
                <div>
                    <div class="blog-meta">
                        <div class="author-info">
                            <div class="author-avatar">'.strtoupper($fullname_blog_card[0]).'</div>
                            <div style="display:flex;justify-content:center;align-items:center;cursor:pointer;">
                            <span onclick="window.location.href=\'viewprofile.php?userid='.$userid.'\'">'.explode(' ', $fullname_blog_card)[0].'</span>
                            '.$verifiedimg.'
                            </div>
                        </div>
                        
                        <span class="blog-date">'.$datetime.'</span>
                    </div>
                    <h3>'.$title.'</h3>
                    <p>'.$desp.'</p>
                </div>
                <div class="card-actions">
                    <a href="blog_post.php?blogid='.$blogid.'" class="neu-btn">Read Now</a>
                </div>
            </div>';
                }

            ?>

        </div>
        
        <!-- Read More Button Container -->
        <div class="load-more-container hidden">
            <a href="explore.php" class="neu-btn load-more-btn">Read More Blogs</a>
        </div>

        <!-- Writer Progress Bar Section (Moved Below Blogs) -->
          <?php if($is_verified == 'no'){ ?>
        <section class="progress-section hidden">
            <div class="progress-header">
                <div class="progress-title">
                    <h2>Publisher Journey</h2>
                    <p>Level: <strong style="color: var(--text-main);"><?php
                    if($no_of_blogs_posted == 0){
                        echo "Not started";
                    }
                    elseif(($no_of_blogs_posted < 3 || $no_of_blogs_posted == 3) && $no_of_blogs_posted != 0){
                        echo "Novice";
                    }
                    elseif(($no_of_blogs_posted > 3 && $no_of_blogs_posted < 6 ) || $no_of_blogs_posted == 6){
                        echo "Emerging Writer";
                    }
                    elseif(($no_of_blogs_posted > 6 && $no_of_blogs_posted < 9 ) || $no_of_blogs_posted == 9){
                        echo "Enthusiast";
                    }
                    else{
                        echo "Verified Veteran";
                    }
                    
                    ?></strong></p>
                </div>
                <div class="progress-stats"><?php echo $no_of_blogs_posted; ?> / 10 Blogs</div>
            </div>
            <div class="progress-track">
                <div class="progress-fill" style="width: <?php if($no_of_blogs_posted == 0){echo 1;}else{echo $no_of_blogs_posted*10;} ?>%;"></div>
            </div>
            <p style="color: var(--text-muted); font-size: 0.9rem; margin-top: 15px;">Write 10 blogs on XEREQ to be rewarded with a verified badge on your profile.</p>
            <?php if($no_of_blogs_posted < 10){ ?>
            <a href="new_blog.php" class="neu-btn" style="margin-top:20px;">Write Now</a>
            <?php } ?>
            <?php if($no_of_blogs_posted > 9){ ?>
            <form method="post" action="profile.php">
                <button type="submit" class="modal-btn btn-save" name='claimtick' style="margin-top:20px;">Claim blue tick</button>
            </form>
            <?php } ?>
        </section>
        <?php } ?>

    </main>

    <!-- Floating Action Button (New Blog) -->
    <a href="new_blog.php" class="fab hidden" title="Add New Blog">
        <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <line x1="12" y1="5" x2="12" y2="19"></line>
            <line x1="5" y1="12" x2="19" y2="12"></line>
        </svg>
    </a>

    <!-- Footer -->
    <footer id="contact">
        <div class="footer-content hidden">
            <img src="assets/Logo_Text.png" alt="XEREQ" class="footer-logo">
            <p style="max-width: 400px; margin: 0 auto;">Read, write, and inspire on the web's most aesthetic blogging platform.</p>
            
            <div class="contact-info">
                <div class="contact-item">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                    <a href="mailto:aaryanofc@gmail.com">aaryanofc@gmail.com</a>
                </div>
                <div class="contact-item">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                    <a href="tel:+918696444111">+91 8696444111</a>
                </div>
            </div>
        </div>
        <p class="copyright">© 2026 XEREQ. All rights reserved.</p>
    </footer>

    <!-- Scripts -->
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            // Scroll Animation Observer
            const observer = new IntersectionObserver((entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add("show");
                    }
                });
            }, { threshold: 0.15, rootMargin: "0px 0px -50px 0px" });

            const hiddenElements = document.querySelectorAll(".hidden");
            hiddenElements.forEach((el) => observer.observe(el));

            // Sidebar Toggle Logic
            const openSidebarBtn = document.getElementById('openSidebar');
            const closeSidebarBtn = document.getElementById('closeSidebar');
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');

            function toggleSidebar() {
                sidebar.classList.toggle('active');
                overlay.classList.toggle('active');
                // Prevent background scrolling when sidebar is open on mobile
                document.body.style.overflow = sidebar.classList.contains('active') ? 'hidden' : 'auto';
            }

            openSidebarBtn.addEventListener('click', toggleSidebar);
            closeSidebarBtn.addEventListener('click', toggleSidebar);
            overlay.addEventListener('click', toggleSidebar);

            // Theme Toggle Logic
            const themeToggleBtn = document.getElementById('themeToggleBtn');
            const themeToggleText = document.getElementById('themeToggleText');
            const sunIcon = document.querySelector('.sun-icon');
            const moonIcon = document.querySelector('.moon-icon');

            // Function to set theme
            function setTheme(isDark) {
                if (isDark) {
                    document.documentElement.setAttribute('data-theme', 'dark');
                    localStorage.setItem('theme', 'dark');
                    sunIcon.style.display = 'none';
                    moonIcon.style.display = 'inline-block';
                    themeToggleText.textContent = 'Switch to Light Mode';
                } else {
                    document.documentElement.setAttribute('data-theme', 'light');
                    localStorage.setItem('theme', 'light');
                    sunIcon.style.display = 'inline-block';
                    moonIcon.style.display = 'none';
                    themeToggleText.textContent = 'Switch to Dark Mode';
                }
            }

            // Check for saved theme preference, default to dark
            const savedTheme = localStorage.getItem('theme');
            if (savedTheme === 'light') {
                setTheme(false);
            } else {
                setTheme(true); // Default
            }

            themeToggleBtn.addEventListener('click', (e) => {
                e.preventDefault();
                const isCurrentlyDark = document.documentElement.getAttribute('data-theme') !== 'light';
                setTheme(!isCurrentlyDark);
            });
        });
    </script>
</body>
</html>
