<?php
session_start();
$error = "";
include_once("../dal.php");
$conn = DataConnect();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $username = $_POST['username'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $error = Register($firstname, $lastname, $username, $phone, $email, $password);
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Register - InfoService</title>
    <link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat:400,400i,700,700i,600,600i">
    <link href="//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/baguettebox.js/1.10.0/baguetteBox.min.css">
    <link rel="stylesheet" href="../assets/css/styles.min.css">
</head>

<body>
    <?php include_once "../template/navbar.php" ?>
    <main class="page registration-page">
        <section class="clean-block clean-form dark">
            <div class="container">
                <div class="block-heading">
                    <h2 class="text">Sign Up</h2>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo.</p>
                </div>
                <form style="border-radius: 25px" method="POST">
                    <div class="form-group"><label for="firstname">First Name</label><input class="form-control item" required type="text" name="firstname"></div>
                    <div class="form-group"><label for="lastname">Last Name</label><input class="form-control item" required type="text" name="lastname"></div>
                    <div class="form-group"><label for="username">Username</label><input class="form-control item" required type="text" name="username"></div>
                    <div class="form-group"><label for="phone">Phone Number</label><input class="form-control item" required type="text" name="phone"></div>
                    <div class="form-group"><label for="email">Email</label><input class="form-control item" required type="email" name="email"></div>
                    <div class="form-group"><label for="password">Password</label><input class="form-control item" required type="password" name="password"></div><button class="btn btn-primary btn-block" type="submit">Sign Up</button>
                </form>
                <div><?php echo $error ?></div>

            </div>
        </section>
    </main>
    <?php include_once("../template/footer.php") ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/baguettebox.js/1.10.0/baguetteBox.min.js"></script>
    <script src="../assets/js/script.min.js"></script>
</body>

</html>