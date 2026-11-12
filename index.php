<?php
include "pre_requisites/dbconn.php";
include "pre_requisites/location.php";
$city_country = "$city,$country";
$ip_adress = $_SERVER['REMOTE_ADDR'];
$sql = "SELECT * FROM `visitors` WHERE `ip_adress`='$ip_adress';";
$res = mysqli_query($conn,$sql);
$num = mysqli_num_rows($res);

if($num == 0){
    $sql2 = "INSERT INTO `visitors` (`no_of_times_visited`,`ip_adress`,`city,country`) VALUES ('1','$ip_adress','$city_country');";
    $res2 = mysqli_query($conn,$sql2);
}
else{
    $row = mysqli_fetch_assoc($res);
    $new_times_visited = $row['no_of_times_visited']+1;
    $sql3 = "UPDATE `visitors` SET `no_of_times_visited` = '$new_times_visited' WHERE `ip_adress` = '$ip_adress';";
    $res3 = mysqli_query($conn,$sql3);
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>XEREQ</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;800&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="assets/Logo_image.png">
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
    <style>
        @theme {
            --font-sans: InterVariable, sans-serif;
            --font-sans--font-feature-settings: 'cv02', 'cv03', 'cv04', 'cv11';
        }       
    
        

        

    
        

    </style>
    <style>
        :root {
            --bg-color: #121316;
            --surface-color: #1a1c23;
            --text-main: #f1f5f9;
            --text-muted: #94a3b8;
            --accent-blue: #00c6ff;
            --accent-red: #ff3366;
            --shadow-light: #20232b;
            --shadow-dark: #0a0a0d;
            --header-bg: rgba(18, 19, 22, 0.6);
            --border-subtle: rgba(255, 255, 255, 0.03);
            --glass-bg: rgba(255, 255, 255, 0.02);
            --glass-border: rgba(255, 255, 255, 0.05);
            --glass-shadow: 0 15px 35px rgba(0,0,0,0.2);
            --glass-btn-bg: rgba(255, 255, 255, 0.08);
            --glass-btn-hover: rgba(255, 255, 255, 0.15);
            --cta-shadow: inset 0 0 40px rgba(0,0,0,0.5);
            --filter-logo: invert(0);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Outfit', sans-serif;
            scroll-behavior: smooth;
        }

        body {
            background-color: var(--bg-color);
            color: var(--text-main);
            overflow-x: hidden;
            line-height: 1.7;
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
            background: var(--header-bg, rgba(18, 19, 22, 0.6));
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid var(--border-subtle);
        }

        .logo-container {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
        }

        .logo-img { height: 40px; width: auto; }
        .logo-text { height: 20px; width: auto; margin-top: 2px;  filter: var(--filter-logo, invert(0)); transition: filter 0.3s ease; }

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

        /* --- Buttons --- */
        .neu-btn {
            background: var(--bg-color);
            color: var(--accent-blue);
            border: none;
            padding: 16px 36px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 1.05rem;
            cursor: pointer;
            box-shadow: 6px 6px 14px var(--shadow-dark), 
                       -6px -6px 14px var(--shadow-light);
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .neu-btn:hover {
            box-shadow: inset 4px 4px 8px var(--shadow-dark), 
                        inset -4px -4px 8px var(--shadow-light);
            color: var(--accent-red);
        }

        /* --- Hero Section --- */
        .hero {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            padding: 120px 20px 60px;
            position: relative;
        }

        .hero::before {
            content: '';
            position: absolute;
            width: 450px;
            height: 450px;
            background: linear-gradient(45deg, var(--accent-blue), var(--accent-red));
            border-radius: 50%;
            filter: blur(150px);
            opacity: 0.12;
            z-index: -1;
            animation: pulse 7s infinite alternate ease-in-out;
        }

        @keyframes pulse {
            0% { transform: scale(0.85); opacity: 0.08; }
            100% { transform: scale(1.15); opacity: 0.15; }
        }

        .hero h1 {
            font-size: 4.5rem;
            font-weight: 800;
            margin-bottom: 25px;
            line-height: 1.1;
            background: linear-gradient(to right, var(--text-main), var(--text-muted));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hero h1 span {
            background: linear-gradient(to right, var(--accent-blue), var(--accent-red));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hero p {
            font-size: 1.25rem;
            color: var(--text-muted);
            max-width: 700px;
            margin-bottom: 45px;
        }

        /* --- Neumorphic Section Styling --- */
        .section-title {
            text-align: center;
            font-size: 2.8rem;
            margin-bottom: 60px;
            font-weight: 600;
            color: var(--text-main);
        }

        .section-wrapper {
            padding: 100px 5%;
        }

        /* --- Trending Blogs --- */
        .blog-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 40px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .blog-card {
            background: var(--surface-color);
            border-radius: 20px;
            padding: 30px;
            box-shadow: 8px 8px 16px var(--shadow-dark), 
                       -8px -8px 16px var(--shadow-light);
            transition: transform 0.3s ease;
            text-align: left;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .blog-card:hover {
            transform: translateY(-8px);
        }

        .blog-category {
            color: var(--accent-red);
            font-size: 0.85rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 15px;
            display: block;
        }

        .blog-card h3 {
            font-size: 1.6rem;
            margin-bottom: 15px;
            line-height: 1.3;
        }

        .blog-card p {
            color: var(--text-muted);
            font-size: 1rem;
            margin-bottom: 25px;
            flex-grow: 1;
        }

        .read-more {
            color: var(--accent-blue);
            text-decoration: none;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }
        
        .read-more:hover { color: var(--text-main); }

        /* --- Praise / Features Section (Liquid Glass) --- */
        .praise-section {
            background: var(--praise-bg, rgba(26, 28, 35, 0.4));
            position: relative;
            overflow: hidden;
        }

        .praise-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 30px;
            max-width: 1200px;
            margin: 0 auto;
            position: relative;
            z-index: 2;
        }

        .glass-review {
            background: var(--glass-bg);
            border-radius: 24px;
            padding: 40px 30px;
            border: 1px solid var(--glass-border);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            box-shadow: var(--glass-shadow);
            text-align: center;
        }

        .glass-review svg {
            color: var(--accent-blue);
            width: 40px;
            height: 40px;
            margin-bottom: 20px;
            opacity: 0.8;
        }

        .glass-review p {
            font-size: 1.1rem;
            font-style: italic;
            color: var(--text-main);
            margin-bottom: 20px;
        }

        .glass-review h4 {
            color: var(--text-muted);
            font-size: 0.95rem;
            font-weight: 400;
        }

        /* --- Publisher CTA --- */
        .publisher-cta {
            background: linear-gradient(135deg, rgba(0, 198, 255, 0.1), rgba(255, 51, 102, 0.1));
            border-radius: 30px;
            padding: 80px 40px;
            max-width: 900px;
            margin: 0 auto;
            text-align: center;
            border: 1px solid var(--glass-border);
            box-shadow: var(--cta-shadow);
        }

        .publisher-cta h2 {
            font-size: 2.8rem;
            margin-bottom: 20px;
        }

        .publisher-cta p {
            color: var(--text-muted);
            font-size: 1.15rem;
            max-width: 600px;
            margin: 0 auto 40px;
        }

        .glass-btn {
            background: var(--glass-btn-bg);
            border: 1px solid var(--glass-border);
            color: var(--text-main);
            padding: 16px 45px;
            border-radius: 30px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            backdrop-filter: blur(10px);
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .glass-btn:hover {
            background: var(--glass-btn-hover);
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.3);
            border-color: var(--accent-blue);
        }

        /* --- Footer --- */
        footer {
            background: var(--bg-color);
            padding: 60px 5% 30px;
            border-top: 1px solid var(--border-subtle);
            text-align: center;
        }

        .footer-content {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 20px;
            margin-bottom: 40px;
        }

        .footer-logo { height: 28px; margin-bottom: 10px; opacity: 0.9;  filter: var(--filter-logo, invert(0)); transition: filter 0.3s ease; }

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
        }

        .contact-item a:hover { color: var(--accent-red); }

        .copyright { color: #555; font-size: 0.9rem; }

        /* --- Responsive Design --- */
        @media (max-width: 768px) {
            header { padding: 15px 20px; }
            nav ul { display: none; }
            .hero h1 { font-size: 3rem; }
            .section-title { font-size: 2.2rem; }
            .publisher-cta { padding: 50px 20px; }
            .publisher-cta h2 { font-size: 2.2rem; }
        }
    
        

        

    
        [data-theme="light"] {
            --praise-bg: rgba(255, 255, 255, 0.4);
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
        <a href="#" class="logo-container">
            <img src="assets/Logo_Image.png" alt="X Logo" class="logo-img">
            <img src="assets/Logo_text.png" alt="XEREQ" class="logo-text">
        </a>
        <nav>
            <ul>
                <li><a href="#home">Home</a></li>
                <li><a href="#trending">Trending Blogs</a></li>
                <li><a href="#praise">Why Us</a></li>
                <li><a href="#publishers">Publishers</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <!-- Hero Section -->
        <section id="home" class="hero hidden">
            <div style="margin-bottom: 30px;">
                <img src="assets/Logo_Image.png" alt="XEREQ Icon" style="width: 120px; filter: drop-shadow(0 0 20px rgba(0, 198, 255, 0.4));">
            </div>
            <h1>The Modern Canvas<br>for <span>Breathtaking Blogs</span></h1>
            <p>Dive into a world where words meet aesthetic design. XEREQ is the ultimate reading destination, curated for thinkers, creators, and daily readers.</p>
            <a href="login.php" class="neu-btn">Start Reading</a>
        </section>

        <!-- Praise / Why XEREQ Section -->
        <section id="praise" class="section-wrapper praise-section hidden">
            <h2 class="section-title">Loved by Readers & Writers</h2>
            <div class="praise-grid">
                <div class="glass-review">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
                    <p>"The cleanest, most distraction-free blog interface out there. Reading on XEREQ feels incredibly fluid and visually satisfying."</p>
                    <h4>— Content Creator Weekly</h4>
                </div>
                <div class="glass-review">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"></path></svg>
                    <p>"XEREQ doesn't just host blogs; it elevates them. The liquid glass design makes every article feel like a premium editorial piece."</p>
                    <h4>— Digital Aesthetics Magazine</h4>
                </div>
                <div class="glass-review">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><path d="M8 14s1.5 2 4 2 4-2 4-2"></path><line x1="9" y1="9" x2="9.01" y2="9"></line><line x1="15" y1="9" x2="15.01" y2="9"></line></svg>
                    <p>"I shifted all my publications here. The community is fantastic, and the modern UI keeps my readers engaged for hours."</p>
                    <h4>— Top Top Publisher</h4>
                </div>
            </div>
        </section>

        <!-- Trending Blogs Section -->
        <section id="trending" class="section-wrapper hidden">
            <h2 class="section-title">Trending on XEREQ</h2>
            <div class="blog-grid">
                <div class="blog-card">
                    <span class="blog-category">Education & Lifestyle</span>
                    <h3>The Ultimate Guide to a 9-Hour Study Routine for PCM</h3>
                    <p>Discover actionable strategies to balance intensive problem-solving, theoretical concepts, and daily breaks to maximize your academic output.</p>
                    <a href="login.php" class="read-more">Read Article <span>&rarr;</span></a>
                </div>
                <div class="blog-card">
                    <span class="blog-category">Style & Fashion</span>
                    <h3>Trending Hairstyles: Mastering the Modern Wolf Cut</h3>
                    <p>From blonde highlights to curtain bangs, explore how to visualize and achieve the perfect textured, edgy look for this season.</p>
                    <a href="login.php" class="read-more">Read Article <span>&rarr;</span></a>
                </div>
                <div class="blog-card">
                    <span class="blog-category">Career & Tech</span>
                    <h3>Choosing the Best Global Universities for Computer Science</h3>
                    <p>An in-depth look at top institutions worldwide, comparing academic rankings, early admission criteria, and incredible graduate outcomes.</p>
                    <a href="login.php" class="read-more">Read Article <span>&rarr;</span></a>
                </div>
            </div>
        </section>

        <!-- Publisher CTA Section -->
        <section id="publishers" class="section-wrapper hidden">
            <div class="publisher-cta">
                <h2>Ready to Share Your Voice?</h2>
                <p>Wanna publish a blog? Click here to write on one of the most beautiful and fastest-growing blog sites. Join our network of passionate publishers and present your thoughts on a canvas that truly honors your words.</p>
                <a href="login.php" class="glass-btn">Start Publishing</a>
            </div>
        </section>
    </main>

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
