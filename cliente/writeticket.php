<?php
session_start();
include_once '../dal.php';
Session();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $error = WriteTicket($_POST['oggetto'], $_POST['tipologia'], $_POST['settore'], $_POST['descrizione']);
    echo ("<script LANGUAGE='JavaScript'>
    window.alert('" . $error . "');
    window.location.href='dashboard.php';
    </script>");
}
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
</head>

<body>
    <?php include_once '../template/private-nav.php' ?>
    <main class="page contact-us-page">
        <section class="clean-block clean-form dark">
            <div class="container">
                <div class="block-heading">
                    <h2 class="text">Apri un nuovo ticket</h2>
                    <p>Compila i seguenti punti:</p>
                </div>
                <form style="border-radius: 25px" method="POST">
                    <div class="form-group">
                        <label for="exampleFormControlInput1">Oggetto</label>
                        <input class="form-control item" type="text" name="oggetto" required>
                    </div>
                    <div class="form-group">
                        <label for="exampleFormControlSelect1">Tipologia</label>
                        <select class="form-control" id="exampleFormControlSelect1" name="tipologia" required>
                            <option>--</option>
                            <option>Domanda</option>
                            <option>Incidente</option>
                            <option>Problema</option>
                            <option>Feature Request</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="exampleFormControlSelect1">Settore</label>
                        <select class="form-control" id="exampleFormControlSelect1" name="settore" required>
                            <option>--</option>
                            <?php echo GetSectors() ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="exampleFormControlTextarea1">Descrizione</label>
                        <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" name="descrizione" required></textarea>
                    </div>
                    <div>
                        <button id="btnShowModal" class="btn btn-primary btn-block" type="submit">Conferma</button>
                    </div>
                </form>
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