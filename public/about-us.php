<!DOCTYPE html>
<html>

<head>
    <?php $title = 'About Us - InfoService'; include_once '../template/head.php' ?>
</head>

<body>
    <?php include_once '../template/navbar.php' ?>
    <main class="page">
        <section class="clean-block about-us">
            <div class="container">
                <div class="block-heading">
                    <h2 class="text">About Us</h2>
                    <p>Le nostre figure professionali.</p>
                </div>
                <div class="row justify-content-center">
                    <div class="col-sm-6 col-lg-4">
                        <div class="card clean-card text-center"><img class="card-img-top w-100 d-block" src="../assets/img/avatars/avatar1.jpg">
                            <div class="card-body info">
                                <h4 class="card-title">Alessandro Ferrari</h4>
                                <p class="card-text">Web Designer</p>
                                <div class="icons"><a href="#"><i class="icon-social-facebook"></i></a><a href="#"><i class="icon-social-instagram"></i></a><a href="#"><i class="icon-social-twitter"></i></a></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-4">
                        <div class="card clean-card text-center"><img class="card-img-top w-100 d-block" src="../assets/img/avatars/avatar2.jpg">
                            <div class="card-body info">
                                <h4 class="card-title">Marouan Ouadi</h4>
                                <p class="card-text">Project Manager</p>
                                <div class="icons"><a href="#"><i class="icon-social-facebook"></i></a><a href="#"><i class="icon-social-instagram"></i></a><a href="#"><i class="icon-social-twitter"></i></a></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-4">
                        <div class="card clean-card text-center"><img class="card-img-top w-100 d-block" src="../assets/img/avatars/avatar3.jpg">
                            <div class="card-body info">
                                <h4 class="card-title">Alessandro Fedele</h4>
                                <p class="card-text">Software Engineer</p>
                                <div class="icons"><a href="#"><i class="icon-social-facebook"></i></a><a href="#"><i class="icon-social-instagram"></i></a><a href="#"><i class="icon-social-twitter"></i></a></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
    <?php include_once '../template/footer.php' ?>
</body>

</html>