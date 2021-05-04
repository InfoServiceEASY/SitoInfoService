<?php
session_start();
$title = 'Interventi dipendente';
include_once '../dal.php';
include_once '../template/privatepage_params.php';
Session();

$conn = DataConnect();
$cond = 0;
$stmt = $conn->prepare('SELECT * FROM ticket WHERE id=? AND isassegnato=?');
$stmt->bind_param('ii', $_GET['id'], $cond);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();
if ($result->num_rows > 0) {
  $row = GetTicketRowgivenId($_GET["id"]);
  if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['Assegna'])) {
      if ($_POST['dipendenti'] != '0' && $_POST['scelta'] != '0') {
        $error = createreport($_POST['dipendenti'], $row['id']);
      } else
        $error = 'La prossima volta seleziona qualcuno';
    } else if (isset($_POST['Elimina'])) {
      $error = deleteTicket($row['id']);
    }
    echo ("<script LANGUAGE='JavaScript'>
    window.alert('" . $error . "');
    window.location.href='TicketAperti.php';
    </script>");
  }
?>

<form method="POST">
  <div class="form-group">
    <label><?php echo $row['id'] . " aperto il " . $row['dataapertura'] ?></label>
  </div>
  <div class="form-group">
    <label><strong>Oggetto</strong></label>
    <p><?php echo $row['oggetto'] ?></p>

  </div>
  <div class="form-group">
    <label><strong>Tipologia</strong></label>
    <p><?php echo $row['tipologia'] ?></p>

  </div>
  <div class="form-group">
    <label><strong>Settore</strong></label>
    <p><?php echo $row['nome'] ?></p>

  </div>
  <div class="form-group">
    <label><strong>Descrizione</strong></label>
    <p><?php echo $row['descrizione'] ?></p>
  </div>
  <div class="form-group">
    <select class="custom-select" name='scelta' onchange=reload(this.form,<?php echo $_GET["id"] ?>)>
      <option selected value='0'>Scelta dipendenti</option>
      <option value='1'>del settore</option>
      <option value='2'>Tutti</option>
    </select>
  </div>
  <script type="text/javascript">
    function prova(variabile) {
      $("select option[value='" + variabile + "']").attr("selected", "selected");
    }
    <?php if (isset($_GET['scelta'])) {
      if ($_GET['scelta'] == 1) echo "prova(1)";
      else if ($_GET['scelta'] == 2) echo "prova(2)";
      else echo "prova(0)";
    } ?>
  </script>
  <?php
  if (isset($_GET['scelta'])) {
    $variabile = $_GET['scelta'];
    $conn = DataConnect();
    if ($variabile == 1) {
      $sql = "SELECT dipendente.id, concat(nome, ' ', cognome) AS fullname  FROM dipendente
                INNER JOIN lavora on dipendente.fk_utenza=lavora.fk_dipendente
                WHERE lavora.fk_settore=(SELECT id FROM settore WHERE settore.nome= ? )";
      $stmt = $conn->prepare($sql);
      $stmt->bind_param('s', $row["nome"]);
    } else {
      $stmt = $conn->prepare('SELECT id, concat(nome, \' \', cognome) AS fullname FROM dipendente');
    }
    $stmt->execute();
    $result = $stmt->get_result();
    $dropdown = "
    <div class='form-group'>
    <select class='custom-select' name='dipendenti'>
    <option value='0'>Select one</option>";
    if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
        $dropdown .= "<option value=" . $row['id'] . ">" . $row['fullname'] . "</option>";
      }
      echo $dropdown . "</select>    </div>";
    }
  }
  ?>

  <div style="float: right;">
    <button class="btn btn-primary" type="submit" name="Elimina">Elimina</button>
    <button id="btnShowModal" class="btn btn-primary" type="submit" name="Assegna">Assegna</button>
  </div>
</form>
<?php } else {
  echo "<h1>ticket già assegnato o inesistente</h1>";
}?>
</div>
</body>


</html>