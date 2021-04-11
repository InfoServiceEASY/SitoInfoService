<?php
session_start();
$error = "";
include_once("dal.php");
$conn = DataConnect();
if (isset($_GET["email"]) && isset($_GET["usr"])) {
    $email = $_GET["email"];
    $usr = $_GET["usr"];
    $conn = DataConnect();
    $query = "update utenza set status='active' WHERE email=? and username=? ";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ss', $email, $usr);
    if ($stmt->execute() === true) {
        $error = 'bravo ti sei registrato con successo premi sul pulsante per accedere </p> </div>
        <form action="public/login.php">
            <button class="btn btn-primary btn-block" type="submit">Login</button>
        </form>';
    } else {
        $error = "errore riprova</p> </div>";
    }
} else {
    echo "nulla";
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Login - InfoService</title>
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
                            <li class="nav-item item"><a class="nav-link" href="public/about-us.php">ABOUT US</a></li>
                            <li class="nav-item item"><a class="nav-link" href="public/contact-us.php">CONTACT US</a></li>
                            <li class="nav-item item"><a class="nav-link" href="public/enrollment.php">SIGN IN</a></li>
                            <li class="nav-item item"><a class="nav-link active" href="public/login.php">LOGIN</a></li>
                        </ul>
                    </div>
                </div>
            </nav>
        </div>
    </nav>
    <main class="page login-page">
    <section class="clean-block clean-form dark">
            <div class="container">
                <div class="block-heading">
                    <h2 class="text-info">Registrazione</h2>
                    <p> <?php echo $error ?>


            </div>
    </section>
    </main>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/baguettebox.js/1.10.0/baguetteBox.min.js"></script>
    <script src="../assets/js/script.min.js"></script>
</body>

</html>