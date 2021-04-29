<?php
session_start();
include_once '../dal.php';
Session();
$ticket = ShowTicket();
?>

<!DOCTYPE html>
<html>

<head>
    <?php $title = 'I miei ticket - InfoService';
    include_once '../template/head.php' ?>
</head>

<body>
    <?php include_once '../template/navbar.php' ?>
    <main class="page landing-page">
        <section class="clean-block features">
            <div class="container">
                <div class="block-heading">
                    <h2 class="text">I tuoi ticket</h2>
                </div>
                <h3>Ticket aperti</h3>
                <?php echo $ticket[0] ?>
                <h3>Ticket chiusi</h3>
                <?php echo $ticket[1] ?>
            </div>
        </section>
    </main>
    <?php include_once '../template/footer.php' ?>
</body>

</html>