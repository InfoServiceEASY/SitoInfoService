<?php
session_start();
$title = "Scrivi nuovo report";
include_once '../dal.php';
include_once '../template/privatepage_params.php';
Session();

$id = $_GET['Id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $isrisolto = $_POST['IsRisolto'] == "Sì" ? 1 : 0;
  $error = InsertReport($_POST['Tempo'], $_POST['Descrizione'], $isrisolto, $id, GetUser()[0]);
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
    <label style="color:darkgrey;text-align:centre;content-align:centre" name="Intervento">Intervento n. <?php echo "$id"; ?></label>
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