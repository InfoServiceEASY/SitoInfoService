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
    <title>Dashboard - InfoService</title>
    <link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat:400,400i,700,700i,600,600i">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/simple-line-icons/2.4.1/css/simple-line-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/baguettebox.js/1.10.0/baguetteBox.min.css">
    <link rel="stylesheet" href="../assets/css/styles.min.css">
</head>

<body>
    <?php include_once "../template/private-nav.php" ?>
    <main class="page faq-page">
        <section class="clean-block features">
            <div class="container">
                <div class="block-heading">
                    <h2 class="text">Benvenuto <?php echo GetNameGivenID() ?></h2>
                    <p>Qua puoi gestire il tuo profilo e i tuoi ticket.</p>
                </div>
                <div class="row justify-content-center">
                    <div class="col-md-5 feature-box"><i class="icon-desktop icon" style="color: black;"></i>
                        <h4><a href="ticket.php">Apri un nuovo ticket</a></h4>
                        <p>Se hai qualche tipo di problema.</p>
                    </div>
                    <div class="col-md-5 feature-box"><i class="icon-cogs icon" style="color: black;"></i>
                        <h4><a href="myticket.php">Visualizza i tuoi ticket</a></h4>
                        <p>Controlla i ticket attivi.</p>
                    </div>
                    <div class="col-md-5 feature-box"><i class="icon-screen-smartphone icon" style="color: black;"></i>
                        <h4><a href="report.php">Visualizza e convalida report</a></h4>
                        <p>Sos</p>
                    </div>
                    <div class="col-md-5 feature-box"><i class="icon-group icon" style="color: black;"></i>
                        <h4>Consulenza</h4>
                        <p>Consulenti esperti ti aiuteranno a identificare nuove via da percorrere.</p>
                    </div>
                </div>
            </div>
        </section>
    </main>
    <?php include_once "../template/footer.php" ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/baguettebox.js/1.10.0/baguetteBox.min.js"></script>
</body>

</html>