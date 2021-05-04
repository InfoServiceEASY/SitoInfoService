<?php
session_start();
include_once '../dal.php';
Session();
$report = ShowReport();
?>

<!DOCTYPE html>
<html>

<head>
    <?php $title = 'I miei report - InfoService';
    include_once '../template/head.php' ?>
</head>

<body>
    <?php include_once '../template/navbar.php' ?>
    <main class="page landing-page">
        <section class="clean-block features">
            <div class="container">
                <div class="block-heading">
                    <h2 class="text">I tuoi report</h2>
                </div>
                <h3>Report da convalidare</h3>
                <?php echo $report[0] ?>
                <h3>Report convalidati e risolti</h3>
                <?php echo $report[1] ?>
                <h3>Report non risolti</h3>
                <?php echo $report[2] ?>
            </div>
        </section>
    </main>
    <?php include_once '../template/footer.php' ?>
</body>

</html>