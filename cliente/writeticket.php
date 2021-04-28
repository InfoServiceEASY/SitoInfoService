<?php
session_start();
include_once '../dal.php';
Session();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $error = WriteTicket($_POST['oggetto'], $_POST['tipologia'], $_POST['settore'], $_POST['descrizione']);
    echo "<script LANGUAGE='JavaScript'>
     window.alert('" . $error . "');
    window.location.href='dashboard.php';
    </script>";
}
?>
<!DOCTYPE html>
<html>

<head>
    <?php $title = 'Nuovo ticket - InfoService'; include_once '../template/head.php' ?>
</head>

<body>
    <?php include_once '../template/navbar.php' ?>
    <main class="page landing-page">
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
                        <button class="btn btn-primary btn-block" type="submit">Conferma</button>
                    </div>
                </form>
            </div>
        </section>
    </main>
    <?php include_once '../template/footer.php' ?>
</body>

</html>