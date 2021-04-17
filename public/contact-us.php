<?php
include_once '../dal.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $error = Contact($_POST['firstname'], $_POST['lastname'], $_POST['phone'], $_POST['email'], $_POST['description']);
}
?>
<!DOCTYPE html>
<html>

<head>
    <?php $title = 'Contact Us - InfoService'; include_once '../template/head.php' ?>
</head>

<body>
    <?php include_once '../template/navbar.php' ?>
    <main class="page contact-us-page">
        <section class="clean-block clean-form dark">
            <div class="container">
                <div class="block-heading">
                    <h2 class="text">Contact Us</h2>
                    <p>Hai un quesito da porci? Contattaci.</p>
                </div>
                <form style="border-radius: 25px" method="POST">
                    <div class="form-group"><label for="firstname">First Name</label><input class="form-control item" required type="text" name="firstname"></div>
                    <div class="form-group"><label for="lastname">Last Name</label><input class="form-control item" required type="text" name="lastname"></div>
                    <div class="form-group"><label for="phone">Phone Number</label><input class="form-control item" required type="text" name="phone"></div>
                    <div class="form-group"><label for="email">Email</label><input class="form-control item" required type="email" name="email"></div>
                    <div class="form-group"><label for="description">Description</label><input class="form-control item" required type="text" name="description"></div>
                    <button class="btn btn-primary btn-block" type="submit">Send</button>
                </form>
                </form>
            </div>
        </section>
    </main>
    <?php include_once '../template/footer.php' ?>
</body>

</html>