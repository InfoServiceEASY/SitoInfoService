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
  $row = GetTicketRowgivenId($_GET['id']);

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
</form>
<?php } else {
  echo '<h1>ticket già assegnato o inesistente</h1>';
}?>
</div>
</body>


</html>