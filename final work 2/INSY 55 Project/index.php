<?php require_once 'config/session_helper.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Athletiqs Gym</title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;700&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

    <!-- External CSS -->
    <link rel="stylesheet" href="css/style.css">
    <style>
        .profile-dropdown {
            position: relative;
            display: inline-block;
        }
        
        .profile-toggle {
            display: flex;
            align-items: center;
            gap: 10px;
            background: none;
            border: 1px solid white;
            color: white;
            padding: 8px 18px;
            border-radius: 25px;
            cursor: pointer;
            font-size: 14px;
        }
        
        .profile-toggle:hover {
            background: rgba(255, 255, 255, 0.1);
        }
        
        .profile-toggle i {
            font-size: 18px;
        }
        
        .profile-menu {
            display: none;
            position: absolute;
            right: 0;
            top: 100%;
            margin-top: 10px;
            background: #222;
            border: 1px solid #444;
            border-radius: 8px;
            min-width: 150px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
            z-index: 1000;
        }
        
        .profile-menu.show {
            display: block;
        }
        
        .profile-menu a {
            display: block;
            padding: 12px 20px;
            color: white;
            text-decoration: none;
            border-bottom: 1px solid #444;
        }
        
        .profile-menu a:last-child {
            border-bottom: none;
        }
        
        .profile-menu a:hover {
            background: #333;
        }
        
    </style>
</head>

<body>

    <!-- NAVIGATION -->
    <nav class="navbar">
        <div class="nav-left">
            <a href="#" class="nav-logo">Athletiqs Gym</a>
            <button class="nav-toggle" aria-label="Toggle navigation">
                <i class="fa-solid fa-bars"></i>
            </button>
            <div class="nav-links">
                <a href="#monthly">Membership</a>
                <a href="#day">DaySession</a>
                <a href="#boxing">Boxing</a>
                <a href="#dance">Dancing</a>
            </div>
        </div>

        <div class="nav-right">
            <?php if (isLoggedIn()): ?>
                <div class="profile-dropdown">
                    <button class="profile-toggle" onclick="toggleProfileMenu()">
                        <i class="fa-solid fa-user-circle"></i>
                        <span><?php echo htmlspecialchars(getLoggedInUsername()); ?></span>
                        <i class="fa-solid fa-chevron-down"></i>
                    </button>
                    <div class="profile-menu" id="profileMenu">
                        <?php if (getUserType() === 'member'): ?>
                            <a href="member_account.php">Manage</a>
                        <?php else: ?>
                            <a href="#" class="manage">Manage</a>
                        <?php endif; ?>
                        <a href="logout.php">Logout</a>
                    </div>
                </div>
            <?php else: ?>
                <span>Already a member?</span>
                <a href="signup.php"><button class="login-btn">SIGN IN/SIGN UP</button></a>
            <?php endif; ?>
        </div>
    </nav>

    <!-- HERO SECTION -->
    <section class="hero">
        <div class="hero-content">
            <h1>ATHLETIQS</h1>
            <h2>GYM</h2>
            <a href="#offers" class="see-offers-btn">SEE OFFERS</a>
            <p class="fb-text">Check us on our FB page!</p>
            <a href="https://www.facebook.com/profile.php?id=100083120754834" target="_blank" rel="noopener noreferrer" class="fb-icon"><i class="fa-brands fa-facebook"></i></a>
        </div>
    </section>

    <!-- OFFERS SECTION -->
    <section id="offers" class="offers-section">

        <!-- OFFER 1 -->
        <section id="monthly">
        <div class="offer scroll-animate">
            <img src="img/monthly.jpg" class="offer-img">
            <div class="offer-text">
                <h2>MONTHLY MEMBERSHIP</h2>
                <p>
                    Keep up the the daily non-stop grind with a 30-day membership, you will gain access to the gym at any open times 
without the hassle in paying for each session. Be more efficient even with your payments 
                </p>
                <a href="Payment_Section.php?service=Monthly%20Membership"><button class="offer-btn">INTERESTED</button></a>
            </div>
        </div>
        </section>

        <!-- OFFER 2 -->
         <section id="day">
        <div class="offer scroll-animate">
            <img src="img/day.jpg" class="offer-img">
            <div class="offer-text">
                <h2>DAY SESSION</h2>
                <p>
                    Not yet fully ready to commit but still on track for improvement? 
                    Don't worry because we got you! With our 1 day session payment, 
                    you can schedule your visit and pay in advance at any moment and anytime. 
                    As long as you're ready, we'll be ready with you!
                </p>
                <a href="Payment_Section.php?service=Day%20Session"><button class="offer-btn">INTERESTED</button></a>
            </div>
        </div>
        </section>

        <!-- OFFER 3 -->
        <section id="boxing">
        <div class="offer scroll-animate">
            <img src="img/boxing.jpg" class="offer-img">
            <div class="offer-text">
                <h2>BOXING TRAINING</h2>
                <p>
                    Not into lifting but fired up to level up? No problem we've got you.
 With our 1-day boxing session pass, you can lock in your spot and pay ahead whenever it works for you. When you're ready to glove up, we're ready to train with you — no pressure, just progress.
                </p>
                <a href="Payment_Section.php?service=Boxing"><button class="offer-btn">INTERESTED</button></a>
            </div>
        </div>
        </section>

        <!-- OFFER 4 -->
        <section id="dance">
        <div class="offer scroll-animate">
            <img src="img/dance.jpg" class="offer-img">
            <div class="offer-text">
                <h2>DANCE FITNESS</h2>
                <p>
                    Ready to move, groove, and feel the music your way? We've made it easy.
 Grab our 1-day dance session pass and book your spot on any available schedule. Pay in advance, show up when you're ready, and we'll be right there matching your energy.
 Just pure movement, good vibes, and steady improvement.
                </p>
                <a href="Payment_Section.php?service=Dancing"><button class="offer-btn">INTERESTED</button></a>
            </div>
        </div>
        </section>
    </section>
    <script>
        const scrollElements = document.querySelectorAll(".scroll-animate");
        const navToggle = document.querySelector(".nav-toggle");
        const navLinks = document.querySelector(".nav-links");
      
        const elementInView = (el) => {
          const elementTop = el.getBoundingClientRect().top;
          return elementTop <= (window.innerHeight - 100);
        };
      
        const displayScrollElement = (element) => {
          element.classList.add("in-view");
        };
      
        window.addEventListener("scroll", () => {
          scrollElements.forEach((el) => {
            if (elementInView(el)) displayScrollElement(el);
          });
        });

        if (navToggle && navLinks) {
          navToggle.addEventListener("click", () => {
            navLinks.classList.toggle("open");
          });
        }
        
        function toggleProfileMenu() {
            const menu = document.getElementById('profileMenu');
            menu.classList.toggle('show');
        }
        
        // Close profile menu when clicking outside
        document.addEventListener('click', function(event) {
            const dropdown = document.querySelector('.profile-dropdown');
            const menu = document.getElementById('profileMenu');
            if (dropdown && menu && !dropdown.contains(event.target)) {
                menu.classList.remove('show');
            }
        });
      </script>
      
</body>
</html>
