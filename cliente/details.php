<?php
session_start();
include_once '../dal.php';
Session();
$con = DataConnect();
$stmt = $con->prepare('SELECT id FROM ticket WHERE id=? AND fk_cliente=?');
$stmt->bind_param('ii', $_GET['id'], GetUser()[0]);
if ($stmt->execute()) {
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        if ($_GET['page'] === 'ticket')
            $field = ShowTicketDetails($_GET['id']);
        else if ($_GET['page'] === 'report')
            $field = ShowReportDetails($_GET['id']);
        else
            $field = 'Pagina non trovata';
    } else
        $field = 'Ticket non trovato';
} else
    $error = 'C\'è stato un problema riprova più tardi';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $error = ConvalidTicket($_POST['commento'], $_POST['tipologia'], $_POST['id']);
}
?>

<!DOCTYPE html>
<html>

<head>
    <?php $title = 'Dettagli - InfoService';
    include_once '../template/head.php' ?>
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
                <?php echo $error ?>
            </div>
        </section>
    </main>
    <?php include_once '../template/footer.php' ?>
</body>

</html>