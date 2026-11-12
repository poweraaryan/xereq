<?php
include "../pre_requisites/dbconn.php";

if((!isset($_COOKIE['loggedin']) && !isset($_COOKIE['email'])) || !isset($_GET['userid'])){
    header("location:/");
}
else{
    $userid = $_GET['userid'];
    $sql = "SELECT * FROM `users` WHERE userid=$userid;";
    $res = mysqli_query($conn,$sql);
    $fetch = mysqli_fetch_assoc($res);
    $fullname = $fetch['fullname'];
    $followers = $fetch['followers'];
    $following = $fetch['following'];
    $noofblogs = $fetch['no_of_blogs'];
    $email = $fetch['email'];
    $is_verified = $fetch['is_verified'];
    $useremail = $_COOKIE['email'];
    $sql2 = "SELECT * FROM `users` WHERE email='$useremail';";
    $res2 = mysqli_query($conn,$sql2);
    $fetch2 = mysqli_fetch_assoc($res2);
    $userfirstname = explode(" ",$fetch2['fullname'])[0];
    $user_is_verified = $fetch2['is_verified'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>XEREQ | View Profile</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;800&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="assets/Logo_Image.png">
    <link rel="stylesheet" href="style.css">

    <style>
/* --- Profile Specific Styles --- */
.profile-container {
    display: flex;
    justify-content: center;
    margin-bottom: 50px;
}
.profile-card {
    background: var(--surface-color);
    border-radius: 20px;
    padding: 50px 40px;
    width: 100%;
    max-width: 600px;
    box-shadow: 10px 10px 20px var(--shadow-dark), -10px -10px 20px var(--shadow-light);
    text-align: center;
    position: relative;
    transition: all 0.3s ease;
}
.profile-avatar {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    background: transparent;
    box-shadow: 8px 8px 16px var(--shadow-dark), -8px -8px 16px var(--shadow-light);
    margin: 0 auto 25px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 3rem;
    font-weight: 800;
    color: var(--accent-blue);
    transition: all 0.3s ease;
}
.profile-name-container {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 15px;
    margin-bottom: 5px;
}
.profile-name {
    font-size: 2.2rem;
    font-weight: 700;
    color: var(--text-main);
}
.profile-email {
    color: var(--text-muted);
    font-size: 1.05rem;
    margin-bottom: 25px; /* Adjusted spacing for follow button */
}
.profile-stats {
    display: flex;
    justify-content: space-around;
    border-top: 1px solid var(--border-subtle);
    border-bottom: 1px solid var(--border-subtle);
    padding: 25px 0;
    margin-bottom: 30px;
}
.stat-item {
    display: flex;
    flex-direction: column;
    gap: 5px;
}
.stat-value {
    font-size: 1.8rem;
    font-weight: 700;
    color: var(--accent-blue);
}
.stat-label {
    font-size: 0.9rem;
    color: var(--text-muted);
    text-transform: uppercase;
    letter-spacing: 1px;
    font-weight: 500;
}

/* Follow Button Styles */
.follow-btn-container {
    margin-bottom: 35px;
}
.btn-follow {
    background: transparent;
    color: var(--accent-blue);
    box-shadow: 4px 4px 8px var(--shadow-dark), -4px -4px 8px var(--shadow-light);
    padding: 12px 35px;
    font-size: 1rem;
    font-weight: 600;
    border: none;
    border-radius: 12px;
    cursor: pointer;
    transition: all 0.3s ease;
    font-family: 'Outfit', sans-serif;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}
.btn-follow:hover {
    box-shadow: inset 2px 2px 4px var(--shadow-dark), inset -2px -2px 4px var(--shadow-light);
    color: var(--accent-red);
}
.btn-follow.following {
    box-shadow: inset 2px 2px 4px var(--shadow-dark), inset -2px -2px 4px var(--shadow-light);
    color: var(--text-muted);
}
.btn-follow.following:hover {
    color: var(--accent-red);
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
        
        <!-- Profile Section -->
        <div class="profile-container" style="flex-direction: column; align-items: center;">
            <div style="width: 100%; max-width: 600px; display: flex; justify-content: flex-start; margin-bottom: 15px;">
                <a href="explore.php" class="edit-btn" title="Go back" style="text-decoration: none;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                </a>
            </div>
            
            <div class="profile-card hidden">
                <div class="profile-avatar">
                    <?php echo strtoupper($fullname[0]); ?> <!-- Initials Placeholder -->
                </div>
                
                <div class="profile-name-container">
                    <div style="display:flex;justify-content:center;align-items:center;"><h3 style="color: var(--text-main); font-size: 1.8rem;"><?php echo $fullname; ?></h3>
            <?php if($is_verified == 'yes'){echo '<img src="assets/verified.png" style="width:25px;height:25px;">';} ?>
                    </div>
                </div>
                
                <div class="profile-email"><?php echo $email; ?></div>

                <!-- Follow Button -->

                <div class="profile-stats">
                    <div class="stat-item">
                        <span class="stat-value"><?php echo $noofblogs; ?></span>
                        <span class="stat-label">Blogs</span>
                    </div>
                </div>
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