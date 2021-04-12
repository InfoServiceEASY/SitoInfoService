<?php
session_start();
include_once '../dal.php';
Session();
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>I miei report - InfoService</title>
    <link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat:400,400i,700,700i,600,600i">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/simple-line-icons/2.4.1/css/simple-line-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/baguettebox.js/1.10.0/baguetteBox.min.css">
    <link rel="stylesheet" href="../assets/css/styles.min.css">
</head>

<body>
    <?php include_once '../template/private-nav.php' ?>
    <main class="page landing-page">
        <section class="clean-block clean-info dark">
            <div class="container">
                <div class="block-heading">
                    <h2 class="text"><?php echo ShowProfile()[0], ' ', ShowProfile()[1] ?></h2>
                    <p>Da qui puoi gestire le tue impostazioni</p>
                </div>
                <div>
                    <div class="col-md-6">
                        <h3>Perchè dovresti scegliere noi?</h3>
                        <div class="getting-started-info">
                            <p>Siamo esperti nel settore dell'assistenza hardware e software. Con noi i tuoi problemi verranno risolti nel giro di pochi giorni con costi contenuti.</p>
                        </div><a class="btn btn-outline-dark btn-lg" type="button" href="public/enrollment.php">Diventa uno di noi</a>
                    </div>
                </div>
            </div>
        </section>
    </main>
    <?php include_once "../template/footer.php" ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/baguettebox.js/1.10.0/baguetteBox.min.js"></script>
    <script src="../assets/js/script.min.js"></script>
</body>

</html>