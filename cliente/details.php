<?php
session_start();
include_once '../dal.php';
Session();

if ($_GET['page'] == 'ticket')
    $field = ShowTicketDetails($_GET['id']);
else if ($_GET['page'] == 'report')
    $field = ShowReportDetails($_GET['id']);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['yes']))
        $error = ConvalidTicket(1, $_POST['commento'], $_POST['id']);
    else if (isset($_POST['no']))
        $error = ConvalidTicket(0, $_POST['commento'], $_POST['id']);
}
?>
<!DOCTYPE html>
<html>

<head>
    <?php $title = 'Dettagli - InfoService'; include_once '../template/head.php' ?>
</head>

<body>
    <?php include_once '../template/navbar.php' ?>
    <main class="page landing-page">
        <section class="clean-block features">
            <div class="container">
                <div class="block-heading">
                    <h2 class="text">Maggiori dettagli:</h2>
                </div>
                <?php echo  $field ?>
                <div><?php echo $error ?> </div>
            </div>
        </section>
    </main>
    <?php include_once '../template/footer.php' ?>
</body>

</html>