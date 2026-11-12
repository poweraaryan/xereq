<?php
include "../pre_requisites/dbconn.php";

if((!isset($_COOKIE['loggedin']) && !isset($_COOKIE['email'])) || (!isset($_GET['blogid']))){
    header("location:/");
}
else{
    $blogid = $_GET['blogid'];
    $sql = "SELECT * FROM `blogs` WHERE blogid=$blogid";
    $res = mysqli_query($conn,$sql);
    $fetch = mysqli_fetch_assoc($res);
    $content = $fetch['content'];
    $blogtitle = $fetch['title'];
    $desp = $fetch['description'];
    $blogemail = $fetch['email'];
    $datetimeofupload = $fetch['datetimeofupload'];
    $sql2 = "SELECT * FROM `users` WHERE email='$blogemail';";
    $res2 = mysqli_query($conn,$sql2);
    $fetch2 = mysqli_fetch_assoc($res2);
    $author = $fetch2['fullname'];
    $userid = $fetch2['userid'];
    $user_is_verified = $fetch2 ['is_verified'];
    $email = $_COOKIE['email'];

    $sql3 = "SELECT * FROM `users` WHERE `email`='$email';";
    $res3 = mysqli_query($conn,$sql3);
    $info = mysqli_fetch_assoc($res3);
    $fullname = $info['fullname'];
    $parts = explode(' ', $fullname);
    $firstname = $parts[0];
    $no_of_blogs_posted = $info['no_of_blogs'];
    $is_verified = $info['is_verified'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>XEREQ | Read Blog</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;800&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="assets/Logo_Image.png">
    <!-- Global Stylesheet -->
    <link rel="stylesheet" href="style.css">

    <style>
        /* --- Blog Post Specific Styles --- */
        .blog-post-wrapper {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 60px;
        }
        .blog-post-card {
            background: var(--surface-color);
            border-radius: 25px;
            padding: 60px;
            width: 100%;
            max-width: 900px;
            box-shadow: 10px 10px 20px var(--shadow-dark), -10px -10px 20px var(--shadow-light);
            transition: all 0.3s ease;
        }
        .blog-post-title {
            font-size: 3rem;
            font-weight: 800;
            line-height: 1.2;
            color: var(--text-main);
            margin-bottom: 25px;
            overflow-wrap: break-word;
            word-wrap: break-word;
        }
        .blog-post-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid var(--border-subtle);
            padding-bottom: 25px;
            margin-bottom: 40px;
        }
        .blog-author-info {
            display: flex;
            align-items: center;
            gap: 15px;
            text-decoration: none;
        }
        .blog-author-avatar {
            width: 55px;
            height: 55px;
            border-radius: 50%;
            background: transparent;
            box-shadow: inset 3px 3px 6px var(--shadow-dark), inset -3px -3px 6px var(--shadow-light);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--accent-blue);
            transition: all 0.3s ease;
        }
        .blog-author-info:hover .blog-author-avatar {
            color: var(--accent-red);
            box-shadow: inset 4px 4px 8px var(--shadow-dark), inset -4px -4px 8px var(--shadow-light);
        }
        .blog-author-details {
            display: flex;
            flex-direction: column;
            gap: 3px;
        }
        .blog-author-name {
            font-weight: 700;
            font-size: 1.15rem;
            color: var(--text-main);
            transition: color 0.3s ease;
        }
        .blog-author-info:hover .blog-author-name {
            color: var(--accent-blue);
        }
        .blog-date-read {
            font-size: 0.95rem;
            color: var(--text-muted);
        }
        
        /* Post Content Styling */
        .blog-post-content {
            font-size: 1.15rem;
            line-height: 1.8;
            color: var(--text-muted);
        }
        .blog-post-content h2 {
            font-size: 2rem;
            color: var(--text-main);
            margin: 40px 0 20px;
            font-weight: 700;
        }
        .blog-post-content h3 {
            font-size: 1.5rem;
            color: var(--text-main);
            margin: 30px 0 15px;
            font-weight: 600;
        }
        .blog-post-content p {
            margin-bottom: 25px;
            overflow-wrap: break-word;
            word-wrap: break-word;
        }
        .blog-post-quote {
            margin: 40px 0;
            padding: 35px;
            border-radius: 20px;
            background: transparent;
            box-shadow: inset 6px 6px 12px var(--shadow-dark), inset -6px -6px 12px var(--shadow-light);
            font-size: 1.35rem;
            font-style: italic;
            color: var(--text-main);
            border-left: 5px solid var(--accent-blue);
            line-height: 1.6;
        }
        
        /* Post Actions (Bottom) */
        .blog-post-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 60px;
            padding-top: 30px;
            border-top: 1px solid var(--border-subtle);
        }
        .blog-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
        }
        .blog-tag {
            padding: 10px 20px;
            border-radius: 20px;
            font-size: 0.95rem;
            font-weight: 600;
            color: var(--text-main);
            background: transparent;
            box-shadow: 4px 4px 8px var(--shadow-dark), -4px -4px 8px var(--shadow-light);
            text-decoration: none;
            transition: all 0.3s ease;
        }
        .blog-tag:hover {
            box-shadow: inset 3px 3px 6px var(--shadow-dark), inset -3px -3px 6px var(--shadow-light);
            color: var(--accent-blue);
        }
        .action-buttons {
            display: flex;
            gap: 20px;
        }
        
        @media (max-width: 768px) {
            .blog-post-card { padding: 30px 20px; }
            .blog-post-title { font-size: 2.2rem; }
            .blog-post-meta { flex-direction: column; align-items: flex-start; gap: 15px; }
            .blog-post-actions { flex-direction: column; align-items: stretch; gap: 30px; }
            .action-buttons { justify-content: center; }
            .blog-tags { justify-content: center; }
        }
    </style>
</head>
<body>

    <!-- Header -->
    <header>
        <a href="home/index.php" class="logo-container">
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
            <li>
                <a href="liked.php">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
                    Liked Blogs
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
        
        <div class="blog-post-wrapper">
            
            <!-- Go Back Button -->
            <div style="width: 100%; max-width: 900px; display: flex; justify-content: flex-start; margin-bottom: 25px;">
                <a href="explore.php" class="edit-btn" title="Go back to Explore" style="text-decoration: none;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                </a>
            </div>

            <!-- Full Blog Card -->
            <article class="blog-post-card hidden">
                
                <!-- Blog Title -->
                <h1 class="blog-post-title"><?php echo $blogtitle; ?></h1>
                
                <!-- Blog Meta Information -->
                <div class="blog-post-meta">
                    <a href="viewprofile.php?userid=<?php echo $userid; ?>" class="blog-author-info">
                        <div class="blog-author-avatar"><?php echo strtoupper($author[0]); ?></div>
                        <div class="blog-author-details" style="text-align:left;">
                            <div style="display:flex;justify-content:center;align-items:center;"><h2 class="blog-author-name" style="text-align:left;"><?php echo $author; ?></h2><?php if($user_is_verified == 'yes'){echo '<img src="assets/verified.png" style="width:25px;height:25px;">';} ?></div>
                            <span class="blog-date-read"><?php echo $datetimeofupload; ?></span>
                        </div>
                    </a>
                </div>

                <!-- Rich Blog Content -->
                <div class="blog-post-content">
                    
                <pre style="white-space: pre-line;overflow-wrap: break-word;">

                    <?php  
                    echo $content;
                    ?>


                </pre>
                    
                    
                </div>

                <!-- Footer Actions (Tags & Interaction) -->
                <div class="blog-post-actions">
                    
                    <div class="action-buttons">
                        <button class="like-btn sharebtn" title="Share Blog">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="18" cy="5" r="3"></circle><circle cx="6" cy="12" r="3"></circle><circle cx="18" cy="19" r="3"></circle><line x1="8.59" y1="13.51" x2="15.42" y2="17.49"></line><line x1="15.41" y1="6.51" x2="8.59" y2="10.49"></line></svg>
                        </button>
                    </div>
                </div>

            </article>
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

        const copyBtn = document.querySelector('.sharebtn');

  copyBtn.addEventListener('click', async () => {
    await navigator.clipboard.writeText(window.location.href);
    alert("Link Copied");
  });


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
            
            // Like Button Interactivity (Demo only)
            const likeBtns = document.querySelectorAll('.like-btn');
            likeBtns.forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    if(this.getAttribute('title') === 'Like Blog') {
                        this.classList.toggle('liked');
                    }
                });
            });
        });
    </script>
</body>
</html>