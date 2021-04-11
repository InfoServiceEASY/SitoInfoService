<?php
session_start();
include_once '../../dal.php';
Session();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['yes'])) {

    }else if(isset($_POST['no'])){
        
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>I miei report - InfoService</title>
    <link rel="stylesheet" href="../../assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat:400,400i,700,700i,600,600i">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/simple-line-icons/2.4.1/css/simple-line-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/baguettebox.js/1.10.0/baguetteBox.min.css">
    <link rel="stylesheet" href="../../assets/css/styles.min.css">
</head>

<body>
    <?php include_once '../../template/private-nav.php' ?>
    <main class="page landing-page">
        <section class="clean-block features">
            <div class="container">
                <div class="block-heading">
                    <h2 class="text">I tuoi report</h2>
                </div>
                <h3>Report in attesa di risoluzione</h3>
                <?php echo ShowReport()[0] ?>
                <h3>Report da convalidare</h3>
                <?php echo ShowReport()[1] ?>
                <h3>Report convalidati e risolti</h3>
                <?php echo ShowReport()[2] ?>
                <h3>Report non risolti</h3>
                <?php echo ShowReport()[3] ?>
            </div>
        </section>
    </main>
    <?php include_once "../../template/footer.php" ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/baguettebox.js/1.10.0/baguetteBox.min.js"></script>
    <script src="../../assets/js/script.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $("#btnShowModal").click(function() {
                $("#exampleModal").modal("show");
            });
            $("#btnClose").click(function() {
                $("#exampleModal").modal("toggle");
            });
        });
    </script>
</body>

</html>