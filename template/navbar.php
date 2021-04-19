<nav class="navbar navbar-light navbar-expand-lg fixed-top bg-light clean-navbar">
    <div class="container"><button data-toggle="collapse" class="navbar-toggler" data-target="#navcol-1"><span class="sr-only">Toggle navigation</span><span class="navbar-toggler-icon"></span></button><img src="../assets/img/logo.png" style="height: 60px;">
        <div class="collapse navbar-collapse" id="navcol-1">
            <ul class="nav navbar-nav ml-auto">
 <?php
    if (strpos(dirname($_SERVER['REQUEST_URI']), "/public") !== false)
        echo '

            <li class="nav-item item"><a class="nav-link" href="../index.php">HOME</a></li>
            <li class="nav-item item"><a class="nav-link" href="about-us.php">ABOUT US</a></li>
            <li class="nav-item item"><a class="nav-link" href="contact-us.php">CONTACT US</a></li>
            <li class="nav-item item"><a class="nav-link" href="enrollment.php">SIGN UP</a></li>
            <li class="nav-item item"><a class="nav-link" href="login.php">LOGIN</a></li>
        </ul>
    </div>
</div>
</nav>';
    else if (strpos(dirname($_SERVER['REQUEST_URI']), "/cliente") !== false)
         echo '
            <li class="nav-item item"><a class="nav-link" href="dashboard.php">HOME</a></li>
        </ul>
        <div class="dropdown">
            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Impostazioni
            </button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <a class="dropdown-item" href="settings.php">Profilo</a>
                <a class="dropdown-item" href="../private/logout.php">Logout</a>
            </div>
        </div>
    </div>
</div>
</nav>';
