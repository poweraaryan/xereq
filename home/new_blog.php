<?php
include "../pre_requisites/dbconn.php";

if(!isset($_COOKIE['loggedin']) && !isset($_COOKIE['email'])){
    header("location:/");
}
else{
    $email = $_COOKIE['email'];
    $sql = "SELECT * FROM `users` WHERE email='$email';";
    $res = mysqli_query($conn,$sql);
    $fetch = mysqli_fetch_assoc($res);
    $no_of_blogs = $fetch['no_of_blogs'];
    $userfirstname = explode(" ",$fetch['fullname'])[0];
    $user_is_verified = $fetch['is_verified'];
    if($_SERVER['REQUEST_METHOD'] == "POST"){
        $title = $_POST['title'];
        $desp = $_POST['description'];
        $content = $_POST['content'];
        $datetimeofupload = date('j F Y');
        $no_of_blogs += 1;
        if($title != "" && $desp != "" && $content != ""){
            $email = mysqli_real_escape_string($conn, $email);
            $title = mysqli_real_escape_string($conn, $title);
            $desp = mysqli_real_escape_string($conn, $desp);
            $content = mysqli_real_escape_string($conn, $content);
            $sql2 = "INSERT INTO `blogs` (`email`,`title`,`description`,`content`,`likes`,`datetimeofupload`) VALUES ('$email','$title','$desp','$content',0,'$datetimeofupload');";
            $res2 = mysqli_query($conn,$sql2);
            $sql3 = "UPDATE `users` SET `no_of_blogs`=$no_of_blogs WHERE `email`='$email';";
            $res3 = mysqli_query($conn,$sql3);
        }
    }
}



?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>XEREQ | New Blog</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;800&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="../assets/Logo_Image.png">
    <!-- Global Stylesheet -->
    <link rel="stylesheet" href="style.css">

    <style>
        
        /* --- New Blog Specific Styles --- */
        .blog-form-wrapper {
            display: flex;
            justify-content: center;
            margin-bottom: 50px;
            width: 100%;
        }
        .blog-form-card {
            background: var(--surface-color);
            border-radius: 20px;
            padding: 50px 40px;
            width: 100%;
            max-width: 800px;
            box-shadow: 10px 10px 20px var(--shadow-dark), -10px -10px 20px var(--shadow-light);
            position: relative;
            transition: all 0.3s ease;
        }
        .form-header {
            text-align: center;
            margin-bottom: 40px;
        }
        .form-header h2 {
            font-size: 2.2rem;
            font-weight: 700;
            color: var(--text-main);
            margin-bottom: 10px;
        }
        .form-header p {
            color: var(--text-muted);
            font-size: 1rem;
        }
        .form-group {
            margin-bottom: 25px;
            text-align: center; /* Center align labels too for balance */
        }
        .form-group label {
            display: block;
            margin-bottom: 15px;
            font-weight: 700;
            color: var(--text-main);
            font-size: 1.2rem;
        }
        
        /* Deep Neumorphic Inputs & Textareas */
        .blog-form-card .neu-input {
            text-align: center;
            padding: 18px 20px;
            font-size: 1.15rem;
            font-weight: 600;
            border-radius: 15px;
            box-shadow: inset 6px 6px 12px var(--shadow-dark), 
                        inset -6px -6px 12px var(--shadow-light);
            transition: all 0.3s ease;
            background:none;
            color:white;
            width:300px;
        }
        .blog-form-card .neu-input:focus {
            box-shadow: inset 8px 8px 16px var(--shadow-dark), 
                        inset -8px -8px 16px var(--shadow-light);
            color: var(--accent-blue);
        }
        
        .neu-textarea {
            resize: auto;
            min-height: 120px;
            line-height: 1.6;
            max-width:750px;
        }
        .main-content-textarea {
            min-height: 350px;
        }
        
        .publish-btn-container {
            text-align: center;
            margin-top: 40px;
        }
        .btn-publish {
            background: transparent;
            color: var(--accent-blue);
            box-shadow: 6px 6px 12px var(--shadow-dark), -6px -6px 12px var(--shadow-light);
            padding: 16px 40px;
            font-size: 1.1rem;
            font-weight: 700;
            width: 100%;
            max-width: 300px;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-family: 'Outfit', sans-serif;
        }
        .btn-publish:hover {
            box-shadow: inset 4px 4px 8px var(--shadow-dark), inset -4px -4px 8px var(--shadow-light);
            color: var(--accent-red);
        }

        @media (max-width: 768px) {
            .blog-form-card { padding: 30px 20px; }
            .form-header h2 { font-size: 1.8rem; }
            .btn-publish { max-width: 100%; }
        }
    </style>
</head>
<body>

    <!-- Header -->
    <header>
        <a href="index.php" class="logo-container">
            <img src="../assets/Logo_Image.png" alt="X Logo" class="logo-img">
            <img src="../assets/Logo_Text.png" alt="XEREQ" class="logo-text">
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
            <div class="avatar" style="text-transform: capitalize;"><?php echo $userfirstname[0]; ?></div> 
            <div style="display:flex;justify-content:center;align-items:center;"><h3 style="color: var(--text-main); font-size: 1.4rem;"><?php echo $userfirstname; ?></h3>
            <?php if($user_is_verified == 'yes'){echo '<img src="assets/verified.png" style="width:25px;height:25px;">';} ?>
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
            <li style="margin-top: 40px; border-top: 1px solid rgba(255,255,255,0.05); padding-top: 20px;">
                <a href="logout.php" class="logout">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
                    Logout
                </a>
            </li>
        </ul>
    </aside>

    <!-- Main Content -->
    <main>
        
        <!-- Blog Form Section -->
        <div class="blog-form-wrapper" style="flex-direction: column; align-items: center;">
            <div style="width: 100%; max-width: 800px; display: flex; justify-content: flex-start; margin-bottom: 15px;">
                <a href="index.php" class="edit-btn" title="Go back to home" style="text-decoration: none;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                </a>
            </div>
            <div class="blog-form-card hidden">
                
                <div class="form-header">
                    <h2>Draft a New Masterpiece</h2>
                    <p>Share your thoughts, stories, and ideas with the world.</p>
                </div>
                
                <form action="#" method="POST">
                    
                    <!-- Title Input -->
                    <div class="form-group">
                        <label for="blogTitle">Blog Title</label>
                        <input type="text" id="blogTitle" name="title" class="neu-input wide-input" placeholder="Enter an engaging title..." maxlength="70" required>
                    </div>

                    <!-- Description Input -->
                    <div class="form-group">
                        <label for="blogDescription">Short Description (Put headings in "&lt;h2&gt;heading&lt;/h2&gt;")</label>
                        <textarea id="blogDescription" name="description" class="neu-input neu-textarea" placeholder="Write a compelling summary (max 200 characters)..." maxlength="200" required></textarea>
                    </div>

                    <!-- Main Content Input -->
                    <div class="form-group">
                        <label for="blogContent">Main Content</label>
                        <textarea id="blogContent" name="content" class="neu-input neu-textarea main-content-textarea" placeholder="Write your full blog post here. Let your creativity flow..." required></textarea>
                    </div>

                    <!-- Submit Button -->
                    <div class="publish-btn-container">
                        <button type="submit" class="btn-publish">Publish Blog</button>
                    </div>

                </form>

            </div>
        </div>

    </main>

    <!-- Floating Theme Toggle Button -->
    <button id="floatingThemeToggle" class="floating-theme-btn" title="Toggle Theme">
        <svg class="sun-icon" style="display: none;" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="5"></circle><line x1="12" y1="1" x2="12" y2="3"></line><line x1="12" y1="21" x2="12" y2="23"></line><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line><line x1="1" y1="12" x2="3" y2="12"></line><line x1="21" y1="12" x2="23" y2="12"></line><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line></svg>
        <svg class="moon-icon" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path></svg>
    </button>

    <!-- Footer -->
    <footer id="contact">
        <div class="footer-content hidden">
            <img src="../assets/Logo_Text.png" alt="XEREQ" class="footer-logo">
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
            const mainOverlay = document.getElementById('sidebarOverlay');

            if (openSidebarBtn && closeSidebarBtn && sidebar && mainOverlay) {
                function toggleSidebar() {
                    sidebar.classList.toggle('active');
                    mainOverlay.classList.toggle('active');
                    document.body.style.overflow = sidebar.classList.contains('active') ? 'hidden' : 'auto';
                }

                openSidebarBtn.addEventListener('click', toggleSidebar);
                closeSidebarBtn.addEventListener('click', toggleSidebar);
                mainOverlay.addEventListener('click', toggleSidebar);
            }

            // Theme Toggle Logic
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