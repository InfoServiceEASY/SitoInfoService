<?php session_start();
$title = 'commenti';
include_once '../dal.php';
include_once '../template/privatepage_params.php';


$conn = DataConnect();
$cond = 0;
$stampare="";
$id=$_GET['id'];
$stmt = $conn->prepare('SELECT * FROM contatto WHERE id=? AND vistato=?');
$stmt->bind_param('ii', $id, $cond);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();
if ($result->num_rows > 0) {
  $row = $result->fetch_assoc();
  if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['Rispondi'])&& isset($_POST['textarea'])) {
      $email=$row['email'];
      $risposta=$_POST['textarea'];
      $domanda=$row['descrizione'];
     echo "<script>window.sendmessaggio('$domanda','$risposta','$email');</script>";    
     $stmt = $conn->prepare('update contatto set vistato=1 , risposta=? WHERE id=?');
    $stmt->bind_param('si', $_POST['textarea'],$id);
    if ($stmt->execute())
       $error="la risposta è stata inviata correttamente";
    else 
       $error="c'è stato un problema ritenta";
    } 
    else if (isset($_POST['Elimina'])) 
      $error = deleteTicket($row['id']);
       echo ("<script LANGUAGE='JavaScript'>
    window.alert('" . $error . "');
    window.location.href='TicketAperti.php';
    </script>");
    
  }}
?>
<form method="POST">
<div class="form-group">
    <label><?php echo $row['id'] . " creato il  " . $row['dataapertura'] ?></label>
  </div>
  <div class="form-group">
    <label><strong>Nome</strong></label>
    <p><?php echo $row['nome'] ?></p>

  </div>
  <div class="form-group">
    <label><strong>Cognome</strong></label>
    <p><?php echo $row['cognome'] ?></p>

  </div>
  <div class="form-group">
    <label><strong>numero</strong></label>
    <p><?php echo $row['cellulare'] ?></p>

  </div>
  <div class="form-group">
    <label><strong>Descrizione</strong></label>
    <p><?php echo $row['descrizione'] ?></p>
  </div>
  <div class="form-group">
    <label><strong>Risposta</strong></label>
  <textarea name="textarea" id="input" class="form-control" rows="3" ></textarea>
  </div>

<div style="float: right;">
    <button class="btn btn-primary" type="submit" name="Elimina">Elimina</button>
    <button id="btnShowModal" class="btn btn-primary" type="submit" name="Rispondi">Rispondi</button>
  </div></form>
</div>
</body>
</html>