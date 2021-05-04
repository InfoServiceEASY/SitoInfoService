<?php
include_once '../dal.php';
if (isset($_SESSION['login'])) {
    header('location: ../index.php');
    exit();
}
if (isset($_GET['email']) && isset($_GET['usr'])) {
    $conn = DataConnect();
    $stmt = $conn->prepare('SELECT status FROM utenza WHERE email=? AND username=?');
    $stmt->bind_param('ss', $_GET['email'], $_GET['usr']);
    if ($stmt->execute()) {
        $stmt->close();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $result = $result->fetch_assoc();
            if ($result['status'] === 0) {
                $status = 1;
                $stmt = $conn->prepare('UPDATE utenza SET status=? WHERE email=? AND username=?');
                $stmt->bind_param('iss', $status, $_GET['email'], $_GET['usr']);
                if ($stmt->execute()) {
                    $error = 'Bravo ti sei registrato con successo, premi sul pulsante per accedere</p></div>
                    <form action="login.php">
                        <button class="btn btn-primary btn-block" type="submit">Login</button>
                    </form>';
                } else {
                    $error = 'C\'è stato un problema riprova più tardi</p></div>';
                }
                $stmt->close();
                $conn->close();
            } else {
                $conn->close();
                header('location: ../index.php');
                exit();
            }
        } else {
            $conn->close();
            header('location: ../index.php');
            exit();
        }
    } else {
        $stmt->close();
        $conn->close();
        $error = 'C\'è stato un problema riprova più tardi</p></div>';
    }
} else {
    header('location: ../index.php');
    exit();
}
?>

<!DOCTYPE html>
<html>

<head>
    <?php $title = 'Verifica email - InfoService';
    include_once '../template/head.php' ?>
</head>

<body>
    <?php include_once '../template/navbar.php' ?>
    <main class="page login-page">
        <section class="clean-block clean-form dark">
            <div class="container">
                <div class="block-heading">
                    <h2 class="text">Registrazione</h2>
                    <p> <?php echo $error ?>
                </div>
        </section>
    </main>
    <?php include_once '../template/footer.php' ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/baguettebox.js/1.10.0/baguetteBox.min.js"></script>
</body>

</html>