<?php
session_start();
$title = 'Nuovo report';
include_once '../dal.php';
include_once '../template/privatepage_params.php';
Session();
$conn = DataConnect();
if (ReportOfthis($conn, $_GET['id'])) {
  if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $isrisolto = $_POST['IsRisolto'] == "Sì" ? 1 : 0;
    $error = InsertReport($_POST['Tempo'], $_POST['Descrizione'], $isrisolto, $_GET['id'], GetUser()[0]);
    if ($isrisolto === 0) {
      $stmt = $conn->prepare('UPDATE Ticket SET isassegnato=0 WHERE id=?');
      $stmt->bind_param('i', $_GET['id']);
      $stmt->execute();
    }
    echo "<script LANGUAGE='JavaScript'>
    window.alert('" . $error . "');
    window.location.href='Ticketlist.php';
    </script>";
  }
?>

  <h1 class="mt-4">Compila Report</h1>
  <br>
  <form method="POST">
    <div class="form-group">
      <label for="exampleFormControlInput1">Intervento</label>
      <label style="color:darkgrey;text-align:centre;content-align:centre" name="Intervento">Intervento n. <?php echo $_GET['id']; ?></label>
    </div>
    <div class="form-group">
      <label for="Tempo">Tempo impiegato (ore)?</label>
      </br>
      <input type="time" id="Tempo" name="Tempo" required>
    </div>
    <div class="form-group">
      <label for="ProblemaRisolto">Problema risolto?</label>
      <select name="IsRisolto" class="form-control" id="ProblemaRisolto" required>
        <option>--</option>
        <option>S&igrave;</option>
        <option>No</option>
      </select>
    </div>
    <div class="form-group">
      <label for="Descrizione">Descrizione</label>
      <textarea name="Descrizione" class="form-control" id="Descrizione" rows="3" required></textarea>
    </div>
    <div style="float: right;">
      <button id="btnShowModal" class="btn btn-primary" type="submit">Conferma</button>
    </div>
  </form>
  <script src="../assets/js/jquery.min.js"></script>
  <script src="../assets/js/bootstrap.bundle.min.js"></script>
  <script>
    $("#menu-toggle").click(function(e) {
      e.preventDefault();
      $("#wrapper").toggleClass("toggled");
    });
  </script>
  </body>
  </html>
<?php
} else {
  echo "<h1>Utente non autorizzato ad accedere alla scrittura o alla modifica di questo report.</h1>";
} ?>