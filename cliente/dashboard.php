<?php
session_start();
include_once '../dal.php';
Session();
?>
<!DOCTYPE html>
<html>

<head>
    <?php $title = 'Dashboard - InfoService';
    include_once '../template/head.php' ?>
</head>

<body>
    <?php include_once '../template/private-nav.php' ?>
    <main class="page landing-page">
        <section class="clean-block features">
            <div class="container">
                <div class="block-heading">
                    <h2 class="text">Benvenuto <?php echo GetUser()[1] ?></h2>
                    <p>Qua puoi gestire il tuo profilo e i tuoi ticket.</p>
                </div>
                <div class="row justify-content-center">
                    <div class="col-md-4 feature-box"><i class="icon-tag icon" style="color: black;"></i>
                        <h4><a href="writeticket.php">Apri un nuovo ticket</a></h4>
                        <p>Se hai qualche tipo di problema.</p>
                    </div>
                    <div class="col-md-4 feature-box"><i class="icon-check icon" style="color: black;"></i>
                        <h4><a href="myticket.php">Visualizza i tuoi ticket</a></h4>
                        <p>Controlla i ticket attivi.</p>
                    </div>
                    <div class="col-md-4 feature-box"><i class="icon-flag icon" style="color: black;"></i>
                        <h4><a href="report.php">Visualizza e convalida report</a></h4>
                        <p>Controlla lo stato dei report.</p>
                    </div>
                </div>
            </div>
        </section>
    </main>
    <?php include_once '../template/footer.php' ?>
</body>

</html>