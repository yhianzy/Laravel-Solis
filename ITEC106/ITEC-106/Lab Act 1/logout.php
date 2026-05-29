   <nav class="navbar navbar-expand-lg bg-dark" data-bs-theme="dark">
        <div class="container-fluid ms-5">
            <div class="ms-5">
                <a class="navbar-brand ms-5" href="landing page.php"><img src="img/streetLogo.webp" alt="" style="height: 40px; width: 60px;"></a>
            </div>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse d-flex justify-content-end" id="navbarSupportedContent">
                <ul class="navbar-nav mb-2 gap-2 me-5 mb-lg-0" style="font-size: 20px; margin-right: 15px;">
                    <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="landingpage.php" style="color: white; font-family: fantasy; margin-right: 45px;">Home</a>
                    </li>
                   <li class="nav-item">
                    <a class="nav-link" href="about.php" style="color: white;font-family: fantasy; margin-right: 45px">About</a>
                    </li>
                  
                    <li class="nav-item">
                    <a class="nav-link" href="logout.php" style="color: white;font-family: fantasy; margin-right: 45px">Logout</a>
                    </li>
                    
                </ul>
            </div>
        </div>
    </nav>


<?php 

session_start();
session_destroy();
header("Location: login.php");

?>