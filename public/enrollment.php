<?php
include_once '../dal.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $error =  Register($_POST['firstname'], $_POST['lastname'], $_POST['username'], $_POST['phone'], $_POST['email'], $_POST['password']);
}
?>
<!DOCTYPE html>
<html>

<head>
    <?php $title = 'Register - InfoService'; include_once '../template/head.php' ?>
</head>

<body>
    <?php include_once '../template/navbar.php' ?>
    <main class="page registration-page">
        <section class="clean-block clean-form dark">
            <div class="container">
                <div class="block-heading">
                    <h2 class="text">Sign Up</h2>
                    <p>Registrati per entrare a far parte di noi.</p>
                </div>
                <form style="border-radius: 25px" method="POST">
                    <div class="form-group"><label for="firstname">First Name</label><input class="form-control item" required type="text" name="firstname"></div>
                    <div class="form-group"><label for="lastname">Last Name</label><input class="form-control item" required type="text" name="lastname"></div>
                    <div class="form-group"><label for="username">Username</label><input class="form-control item" required type="text" name="username"></div>
                    <div class="form-group"><label for="phone">Phone Number</label><input class="form-control item" required type="text" name="phone"></div>
                    <div class="form-group"><label for="email">Email</label><input class="form-control item" required type="email" name="email"></div>
                    <div class="form-group"><label for="password">Password</label><input class="form-control item" required type="password" name="password"></div>
                    <button class="btn btn-primary btn-block" type="submit">Sign Up</button>
                    <div><?php echo $error ?> </div>
                </form>
            </div>
        </section>
    </main>
    <?php include_once '../template/footer.php' ?>
</body>

</html>