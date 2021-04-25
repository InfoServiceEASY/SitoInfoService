<?php
include_once '../dal.php';
if (isset($_GET["email"]) && isset($_GET["usr"])) {
    $conn = DataConnect();
    $status = 1;
    $stmt = $conn->prepare('UPDATE utenza SET status=? WHERE email=? AND username=?');
    $stmt->bind_param('iss', $status, $_GET["email"], $_GET["usr"]);
    if ($stmt->execute() === true) {
        $stmt->close();
        $conn->close();
        $error = 'Bravo ti sei registrato con successo premi sul pulsante per accedere </p> </div>
        <form action="login.php">
            <button class="btn btn-primary btn-block" type="submit">Login</button>
        </form>';
    } else {
        $stmt->close();
        $conn->close();
        $error = 'Errore riprova</p> </div>';
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Verifica Email - InfoService</title>
    <link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat:400,400i,700,700i,600,600i">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/simple-line-icons/2.4.1/css/simple-line-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/baguettebox.js/1.10.0/baguetteBox.min.css">
    <link rel="stylesheet" href="../assets/css/styles.min.css">
</head>

<body>
    <?php include_once '../template/navbar.php' ?>
    <main class="page login-page">
        <section class="clean-block clean-form dark">
            <div class="container">
                <div class="block-heading">
                    <h2 class="text">Registrazione</h2>
                    <p> <?php echo $error ?>

                </div>
        </section>
    </main>
    <?php include_once '../template/footer.php' ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/baguettebox.js/1.10.0/baguetteBox.min.js"></script>
</body>

</html>