<?php
session_start();
include_once '../dal.php';
Session();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $error = UpdateProfile($_POST['nome'], $_POST['cognome'], $_POST['cellulare'], $_POST['username'], $_POST['email']);
}
?>

<!DOCTYPE html>
<html>

<head>
    <?php $title = 'Il mio profilo - InfoService'; include_once '../template/head.php' ?>
</head>

<body>
    <?php include_once '../template/private-nav.php' ?>
    <main class="page landing-page">
        <section class="clean-block clean-form dark">
            <div class="container">
                <div class="block-heading">
                    <h2 class="text"><?php echo ShowProfile()[0], ' ', ShowProfile()[1] ?></h2>
                    <p>Da qui puoi gestire le tue impostazioni</p>
                </div>
                <div>
                    <?php echo ShowProfile()[2] ?>
                </div>
            </div>
        </section>
    </main>
    <?php include_once '../template/footer.php' ?>
</body>

</html>