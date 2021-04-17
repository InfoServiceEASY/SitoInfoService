<footer class="page-footer dark footer">
    <div class="container">
        <div class="row">
            <div class="col">
                <h5>Get started</h5>
                <ul>
                    <li><a href="login.php">Login</a></li>
                    <li><a href="enrollment.php">Sign Up</a></li>
                </ul>
            </div>
            <div class="col">
                <h5>Support</h5>
                <ul>
                    <li><a href="about-us.php">About Us</a></li>
                    <li><a href="contact-us.php">Contact Us</a></li>
                    <li><a href="faq.php">FAQ</a></li>
                </ul>
            </div>
            <div class="clean-block add-on social-icons">
                <ul>
                    <a href="#" <i class="icon-facebook-sign icon-3x"></i></a>
                    <a href="#" <i class="icon-instagram icon-3x"></i></a>
                    <a href="#" <i class="icon-twitter icon-3x"></i></a>
                </ul>
            </div>
        </div>
    </div>
    <div class="footer-copyright">
        <p>©2021 INFOSERVICE - All rigths reserved</p>
    </div>
</footer>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/baguettebox.js/1.10.0/baguetteBox.min.js"></script>
<?php if ($_SERVER['REQUEST_URI'] === '/SitoInfoService/cliente/settings.php' || $_SERVER['REQUEST_URI'] === '/cliente/settings.php' || $_SERVER['REQUEST_URI'] === '/infoservice/cliente/settings.php')
    echo '<script>
function myFunction() {
    var checkBox = document.getElementById("action");
    var text = document.getElementById("field");
    if (checkBox.checked == true) {
        $(".field").prop("disabled", false);
    } else {
        $(".field").prop("disabled", true);
    }
}
</script>';
else if ($_SERVER['REQUEST_URI'] === '/SitoInfoService/cliente/writeticket.php' || $_SERVER['REQUEST_URI'] === '/cliente/writeticket.php' || $_SERVER['REQUEST_URI'] === '/infoservice/cliente/writeticket.php')
    echo '<script>
$(document).ready(function() {
    $("#btnShowModal").click(function() {
        $("#exampleModal").modal("show");
    });
    $("#btnClose").click(function() {
        $("#exampleModal").modal("toggle");
    });
});
</script>';

?>