<?php
session_start();
$error = "";
include_once("../dal.php");
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn = DataConnect();
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $username = $_POST['username'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql_u = "SELECT * FROM utenza WHERE username=?;";
    $stmt = $conn->prepare($sql_u);
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();

    $sql_e = "SELECT * FROM utenza WHERE email=?";
    $stmt = $conn->prepare($sql_e);
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $resultE = $stmt->get_result();


    if ($result->num_rows > 0) {
        $error = "Sorry... username already taken";
    } else if ($resultE->num_rows > 0) {
        $error = "Sorry... email already taken";
    } else {
        echo "ci sentro";
     $conn->close();
      $error=  Register($firstname, $lastname, $username, $phone, $email, $password);
        
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Register - InfoService</title>
    <script src=
    "https://smtpjs.com/v3/smtp.js">
  </script>
   <script src="../assets/js/script.js"></script>
    <link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat:400,400i,700,700i,600,600i">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/simple-line-icons/2.4.1/css/simple-line-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/baguettebox.js/1.10.0/baguetteBox.min.css">
    <link rel="stylesheet" href="../assets/css/styles.min.css">
</head>

<body>
    <nav class="navbar navbar-light navbar-expand-lg fixed-top bg-white clean-navbar">
        <div class="container">
            <nav class="navbar navbar-dark navbar-expand-lg fixed-top bg-dark clean-navbar">
                <div class="container"><button data-toggle="collapse" class="navbar-toggler" data-target="#navcol-1"><span class="sr-only">Toggle navigation</span><span class="navbar-toggler-icon"></span></button><img src="../assets/img/logo.png" style="height: 60px;">
                    <div class="collapse navbar-collapse" id="navcol-1">
                        <ul class="nav navbar-nav ml-auto">
                            <li class="nav-item item"><a class="nav-link" href="about-us.php">ABOUT US</a></li>
                            <li class="nav-item item"><a class="nav-link" href="contact-us.php">CONTACT US</a></li>
                            <li class="nav-item item"><a class="nav-link" href="enrollment.php">SIGN IN</a></li>
                            <li class="nav-item item"><a class="nav-link" href="login.php">LOGIN</a></li>
                        </ul>
                    </div>
                </div>
            </nav>
        </div>
    </nav>
    <main class="page registration-page">
        <section class="clean-block clean-form dark">
            <div class="container">
                <div class="block-heading">
                    <h2 class="text-info">Sign In</h2>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo.</p>
                </div>
                <form method="POST">
                    <?php echo $error ?>
                    <div class="form-group"><label for="firstname">First Name</label><input class="form-control item" required type="text" name="firstname"></div>
                    <div class="form-group"><label for="lastname">Last Name</label><input class="form-control item" required type="text" name="lastname"></div>
                    <div class="form-group"><label for="username">Username</label><input class="form-control item" required type="text" name="username"></div>
                    <div class="form-group"><label for="phone">Phone Number</label><input class="form-control item" required type="text" name="phone"></div>
                    <div class="form-group"><label for="email">Email</label><input class="form-control item" required type="email" name="email"></div>
                    <div class="form-group"><label for="password">Password</label><input class="form-control item" required type="password" name="password"></div>
                    <button class="btn btn-primary btn-block" type="submit">Sign Up</button>
                </form>
            </div>
        </section>
        <div class="clean-block add-on social-icons">
            <div class="icons"><a href="#"><i class="fa fa-facebook"></i></a><a href="#"><i class="fa fa-instagram"></i></a><a href="#"><i class="fa fa-twitter"></i></a></div>
        </div>
    </main>
    
    <footer class="page-footer dark">
        <div class="container">
            <div class="row">
                <div class="col-sm-3">
                    <h5>Get started</h5>
                    <ul>
                        <li><a href="#">Home</a></li>
                        <li><a href="#">Sign up</a></li>
                        <li><a href="#">Downloads</a></li>
                    </ul>
                </div>
                <div class="col-sm-3">
                    <h5>About us</h5>
                    <ul>
                        <li><a href="#">Company Information</a></li>
                        <li><a href="#">Contact us</a></li>
                        <li><a href="#">Reviews</a></li>
                    </ul>
                </div>
                <div class="col-sm-3">
                    <h5>Support</h5>
                    <ul>
                        <li><a href="#">FAQ</a></li>
                        <li><a href="#">Help desk</a></li>
                        <li><a href="#">Forums</a></li>
                    </ul>
                </div>
                <div class="col-sm-3">
                    <h5>Legal</h5>
                    <ul>
                        <li><a href="#">Terms of Service</a></li>
                        <li><a href="#">Terms of Use</a></li>
                        <li><a href="#">Privacy Policy</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="footer-copyright">
            <p>© 2021 Copyright Text</p>
        </div>
    </footer>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/baguettebox.js/1.10.0/baguetteBox.min.js"></script>
    <script src="../assets/js/script.min.js"></script>
</body>

</html>