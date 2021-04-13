<?php
session_start();
include_once '../dal.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['email'];
    $password = $_POST['password'];
    $error = Login($username, $password);
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Login - InfoService</title>
    <script src="https://smtpjs.com/v3/smtp.js"></script>
    <script src="../assets/js/script.js"></script>
    <link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat:400,400i,700,700i,600,600i">
    <link href="//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/baguettebox.js/1.10.0/baguetteBox.min.css">
    <link rel="stylesheet" href="../assets/css/styles.min.css">
</head>

<body>
    <?php include_once "../template/navbar.php" ?>
    <main class="page login-page">
        <section class="clean-block clean-form dark">
            <div class="container">
                <div class="block-heading">
                    <h2 class="text">Log In</h2>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo.</p>
                </div>
                <form style="border-radius: 25px" method="POST">
                    <div class="form-group"><label for="email">Email o Username</label><input class="form-control item" name="email"></div>
                    <div class="form-group"><label for="password">Password</label><input class="form-control" type="password" name="password"></div><button class="btn btn-primary btn-block" type="submit">Log In</button>
                    </br>
                    <p> Non ancora registrato? Registrati <a href="enrollment.php" style="text-decoration: none;">qui</a>.</p>
                    <div><?php echo $error ?> </div>
                </form>
            </div>
        </section>
    </main>
    <?php include_once '../template/footer.php' ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/baguettebox.js/1.10.0/baguetteBox.min.js"></script>
</body>

</html>