<?php
include "../pre_requisites/dbconn.php";
if(!isset($_COOKIE['loggedin']) && !isset($_COOKIE['email'])){
    header("location:/");
}
else{
    $useremail = $_COOKIE['email'];
    $sql2 = "SELECT * FROM `users` WHERE email='$useremail';";
    $res2 = mysqli_query($conn,$sql2);
    $fetch2 = mysqli_fetch_assoc($res2);
    $userfirstname = explode(" ",$fetch2['fullname'])[0];
    $user_is_verified = $fetch2['is_verified'];
    $search = false;

    if($_SERVER['REQUEST_METHOD'] == 'GET'){
        if(isset($_GET['q'])){
            $searchquery = $_GET['q'];
            $searchsql = "SELECT * FROM blogs WHERE MATCH(`title`, `description`, `content`) AGAINST('$searchquery');";
            $searchres = mysqli_query($conn,$searchsql);
            $search = true;
        }
    }

}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>XEREQ | Explore</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;800&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="assets/Logo_Image.png">
    <!-- Global Stylesheet -->
    <link rel="stylesheet" href="style.css">

    <style>
        /* --- Explore Page Specific Styles --- */
        .explore-header {
            text-align: center;
            margin-bottom: 40px;
        }
        .explore-header h1 {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--text-main);
            margin-bottom: 10px;
        }
        .explore-header p {
            color: var(--text-muted);
            font-size: 1.1rem;
        }
        
        .search-container {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 15px;
            margin-bottom: 60px;
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
            padding: 0 20px;
        }
        .search-input {
            flex-grow: 1;
            padding: 16px 25px;
            font-size: 1.1rem;
            border-radius: 15px;
            text-align: left;
            background:none;
            box-shadow: inset 6px 6px 12px var(--shadow-dark), 
                        inset -6px -6px 12px var(--shadow-light);
        }
        .search-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 58px;
            height: 58px;
            border-radius: 15px;
            padding: 0;
            flex-shrink: 0;
        }

        @media (max-width: 768px) {
            .search-container {
                flex-direction: column;
                gap: 20px;
                margin-bottom: 40px;
            }
            .search-btn {
                width: 100%;
                height: 55px;
            }
            .explore-header h1 {
                font-size: 2.2rem;
            }
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
            <p style="color: var(--text-muted); font-size: 0.9rem;"><?php echo $useremail; ?></p>
        </div>

        <ul class="sidebar-links">
            <li>
                <a href="explore.php">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                    Explore
                </a>
            </li>
            <li>
                <a href="profile.php">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                    My Profile
                </a>
            </li>
            <li>
                <a href="new_blog.php">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                    New Blog
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
        
        <div class="explore-header hidden">
            <h1>Explore XEREQ</h1>
            <p>Discover ideas, stories, and knowledge.</p>
        </div>

        <!-- Search Bar -->
        <form action="explore.php" method="GET" class="search-container hidden">
            <input type="text" name="q" class="neu-input search-input" placeholder="Search for blogs, authors, or topics..." style="color:green;" required>
            <button type="submit" class="neu-btn search-btn" title="Search">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
            </button>
        </form>
        
        <!-- Blog Cards Grid -->
        <div class="blog-grid">
            
          <?php

          if(!$search){

                $sql2 = "SELECT * FROM `blogs` ORDER BY `blogid` DESC";
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
          }
          else{
            if(mysqli_num_rows($searchres) == 0){
                echo "<h1>No results found</h1>";
            }
            else{
while($row = mysqli_fetch_assoc($searchres)){
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
          }}
            ?>

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