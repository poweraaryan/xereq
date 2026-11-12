<?php
include "../pre_requisites/dbconn.php";

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
    $followers = $info['followers'];
    $following = $info['following'];

    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        if(isset($_POST['claimtick'])){
            $sql = "UPDATE `users` SET `is_verified` = 'yes' WHERE `email`='$email';";
            $res = mysqli_query($conn,$sql);
            header("Location: /home/profile.php");
        }
        if(isset($_POST['namechangebtn'])){
            $newname = $_POST['new_name'];
            $sql = "UPDATE `users` SET `fullname`='$newname' WHERE `email`='$email';";
            $res = mysqli_query($conn,$sql);
            header("Location: /home/profile.php");
        }
        if(isset($_POST['delaccountbtn'])){
            $sql = "DELETE FROM `users` WHERE `email`='$email';";
            $res = mysqli_query($conn,$sql);
            header("Location: ../logout.php");
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>XEREQ | Profile</title>
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
    background: var(--surface-color);
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
.edit-btn {
    background: var(--bg-color);
    color: var(--text-muted);
    border: none;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    box-shadow: 5px 5px 10px var(--shadow-dark), -5px -5px 10px var(--shadow-light);
    transition: all 0.3s ease;
}
.edit-btn:hover {
    color: var(--accent-blue);
    box-shadow: inset 3px 3px 6px var(--shadow-dark), inset -3px -3px 6px var(--shadow-light);
}
.profile-email {
    color: var(--text-muted);
    font-size: 1.05rem;
    margin-bottom: 35px;
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
.delete-account-btn {
    background: none;
    border: none;
    color: var(--text-muted);
    font-size: 0.95rem;
    text-decoration: underline;
    cursor: pointer;
    transition: color 0.3s ease;
    margin-top: 15px;
}
.delete-account-btn:hover {
    color: var(--accent-red);
}



/* --- Modal Styles --- */
.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100vh;
    background: rgba(0, 0, 0, 0.6);
    backdrop-filter: blur(8px);
    z-index: 2000;
    display: flex;
    justify-content: center;
    align-items: center;
    opacity: 0;
    visibility: hidden;
    transition: all 0.3s ease;
}
.modal-overlay.active {
    opacity: 1;
    visibility: visible;
}
.modal-card {
    background: var(--surface-color);
    border-radius: 20px;
    padding: 40px;
    width: 90%;
    max-width: 400px;
    box-shadow: 15px 15px 30px rgba(0,0,0,0.4), -15px -15px 30px rgba(255,255,255,0.02);
    text-align: center;
    transform: translateY(20px) scale(0.95);
    transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
}
.modal-overlay.active .modal-card {
    transform: translateY(0) scale(1);
}
.modal-title {
    color: var(--text-main);
    font-size: 1.5rem;
    margin-bottom: 15px;
    font-weight: 700;
}
.modal-text {
    color: var(--text-muted);
    margin-bottom: 30px;
    line-height: 1.5;
}
.modal-actions {
    display: flex;
    justify-content: space-between;
    gap: 20px;
}
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
.btn-cancel {
    background: var(--bg-color);
    color: var(--text-main);
    box-shadow: 4px 4px 8px var(--shadow-dark), -4px -4px 8px var(--shadow-light);
}
.btn-cancel:hover {
    box-shadow: inset 2px 2px 4px var(--shadow-dark), inset -2px -2px 4px var(--shadow-light);
}
.btn-confirm {
    background: var(--bg-color);
    color: var(--accent-red);
    box-shadow: 4px 4px 8px var(--shadow-dark), -4px -4px 8px var(--shadow-light);
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

.btn-confirm:hover {
    box-shadow: inset 2px 2px 4px var(--shadow-dark), inset -2px -2px 4px var(--shadow-light);
    color: var(--accent-red);
}


/* Profile Neumorphic Input */
.profile-neu-input {
    width: 100%;
    padding: 12px 20px;
    border: none;
    border-radius: 12px;
    background: var(--bg-color);
    color: var(--text-main);
    font-size: 1.2rem;
    font-weight: 600;
    text-align: center;
    font-family: 'Outfit', sans-serif;
    box-shadow: inset 5px 5px 10px var(--shadow-dark), 
                inset -5px -5px 10px var(--shadow-light);
    transition: all 0.3s ease;
    margin-bottom: 25px;
}
.profile-neu-input:focus {
    outline: none;
    box-shadow: inset 8px 8px 16px var(--shadow-dark), 
                inset -8px -8px 16px var(--shadow-light);
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
        
        <!-- Profile Section -->
        <div class="profile-container" style="flex-direction: column; align-items: center;">
            <div style="width: 100%; max-width: 600px; display: flex; justify-content: flex-start; margin-bottom: 15px;">
                <a href="/home/" class="edit-btn" title="Go back to home" style="text-decoration: none;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                </a>
            </div>
            <div class="profile-card hidden">
                <div class="profile-avatar" style="text-transform: capitalize;">
                    <?php echo $firstname[0]; ?> 
                </div>
                
                <div class="profile-name-container" id="nameDisplayContainer">
                    <div class="profile-name"> 
            <div style="display:flex;justify-content:center;align-items:center;"><?php echo $fullname; ?>
            <?php if($is_verified == 'yes'){echo '<img src="assets/verified.png" style="width:30px;height:30px;">';} ?>
            </div></div>
                    <button class="edit-btn" id="editNameBtn" title="Edit Profile">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                    </button>
                </div>
                
                <div class="profile-email"><?php echo $email; ?></div>

                <div class="profile-stats">
                    <div class="stat-item">
                        <span class="stat-value"><?php echo $no_of_blogs_posted; ?></span>
                        <span class="stat-label">Blogs</span>
                    </div>
                </div>

                <button class="delete-account-btn" id="openDeleteModal">Delete Account</button>
            </div>
        </div>

        <!-- Writer Progress Bar Section -->
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


    
    <!-- Edit Name Modal -->
    <div class="modal-overlay" id="editNameModalOverlay">
        <div class="modal-card">
            <h3 class="modal-title">Edit Name</h3>
            <p class="modal-text">Enter your new full name below.</p>
            <form action="profile.php" method="POST">
                <input type="text" name="new_name" class="profile-neu-input" value="<?php echo $fullname; ?>" placeholder="Your Name" required>
                <div class="modal-actions">
                    <button type="button" class="modal-btn btn-cancel" id="cancelEditName">Cancel</button>
                    <button type="submit" name="namechangebtn" class="modal-btn btn-save">Save</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal-overlay" id="deleteModalOverlay">
        <div class="modal-card">
            <h3 class="modal-title">Delete Account</h3>
            <p class="modal-text">Are you sure you want to delete your account? This action is permanent and cannot be undone.</p>
            <div class="modal-actions">
                <button class="modal-btn btn-cancel" id="cancelDelete">Cancel</button>
                <form method="post" action="profile.php"><button type="submit" name="delaccountbtn" class="modal-btn btn-confirm" id="confirmDelete">Confirm</button></form>
            </div>
        </div>
    </div>

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

            // Edit Name Modal Logic
            const editNameBtn = document.getElementById('editNameBtn');
            const cancelEditNameBtn = document.getElementById('cancelEditName');
            const editNameModalOverlay = document.getElementById('editNameModalOverlay');

            if(editNameBtn && cancelEditNameBtn && editNameModalOverlay) {
                function toggleEditModal(e) {
                    if(e) e.preventDefault();
                    editNameModalOverlay.classList.toggle('active');
                    document.body.style.overflow = editNameModalOverlay.classList.contains('active') ? 'hidden' : 'auto';
                }

                editNameBtn.addEventListener('click', toggleEditModal);
                cancelEditNameBtn.addEventListener('click', toggleEditModal);
                editNameModalOverlay.addEventListener('click', (e) => {
                    if (e.target === editNameModalOverlay) {
                        toggleEditModal();
                    }
                });
            }

            // Modal Toggle Logic
            const openModalBtn = document.getElementById('openDeleteModal');
            const closeModalBtn = document.getElementById('cancelDelete');
            const confirmDeleteBtn = document.getElementById('confirmDelete');
            const deleteModalOverlay = document.getElementById('deleteModalOverlay');

            if(openModalBtn && closeModalBtn && deleteModalOverlay) {
                function toggleModal(e) {
                    if(e) e.preventDefault();
                    deleteModalOverlay.classList.toggle('active');
                    document.body.style.overflow = deleteModalOverlay.classList.contains('active') ? 'hidden' : 'auto';
                }

                openModalBtn.addEventListener('click', toggleModal);
                closeModalBtn.addEventListener('click', toggleModal);
                deleteModalOverlay.addEventListener('click', (e) => {
                    if (e.target === deleteModalOverlay) {
                        toggleModal();
                    }
                });

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