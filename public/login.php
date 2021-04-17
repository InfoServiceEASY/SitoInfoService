<?php
session_start();
include_once '../dal.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $error = Login($_POST['email'], $_POST['password']);
}
?>
<!DOCTYPE html>
<html>

<head>
    <?php $title = 'Login - InfoService'; include_once '../template/head.php' ?>
</head>

<body>
    <?php include_once '../template/navbar.php' ?>
    <main class="page login-page">
        <section class="clean-block clean-form dark">
            <div class="container">
                <div class="block-heading">
                    <h2 class="text">Log In</h2>
                    <p>Inserisci le tue credenziali per iniziare.</p>
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
</body>

</html>