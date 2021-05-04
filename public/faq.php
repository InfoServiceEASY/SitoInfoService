<!DOCTYPE html>
<html>

<head>
    <?php $title = 'FAQ - InfoService';
    include_once '../template/head.php' ?>
</head>

<body>
    <?php include_once '../template/navbar.php' ?>
    <main class="page faq-page">
        <section class="clean-block clean-faq dark">
            <div class="container">
                <div class="block-heading">
                    <h2 class="text">FAQ</h2>
                    <p>Controlla se il tuo problema è già stato riscontrato. Altrimenti <a href="contact-us.php">contattaci</a>.</p>
                </div>
                <div class="block-content">
                    <div class="faq-item">
                        <h4 class="question">Il tuo computer non si accende più?</h4>
                        <div class="answer">
                            <p>Accertati che la batteria sia carica.</p>
                            <p>Per fare questo, collega il computer all'alimentatore e prova di nuovo ad accenderlo. Verifica inoltre che l'alimentatore funzioni correttamente</p>
                        </div>
                    </div>
                    <div class="faq-item">
                        <h4 class="question">Non riesci a visualizzare i tuoi ticket?</h4>
                        <div class="answer">
                            <p>Prova a disconnetterti e riconnetterti di nuovo.</p>
                        </div>
                    </div>
                    <div class="faq-item">
                        <h4 class="question">Non riesci a contattarci?</h4>
                        <div class="answer">
                            <p>Scrivici una email a infoservice@support.it</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
    <?php include_once '../template/footer.php' ?>
</body>

</html>