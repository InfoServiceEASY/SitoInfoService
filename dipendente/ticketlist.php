<?php
/*lista 
 $sql = "SELECT id, dataapertura,descrizione FROM ticket
    WHERE isaperto = 1 AND fk_dipendente = ?"
    $sth = $conn -> prepare($sql);
    $sth -> bindparam('?', IDByUser($_SESSION['utente'])); //SELECT id from dipendente WHERE username = parametro ($_SESSION['utente'])   
*/

session_start();

include('../dal.php');
$title = 'Lista interventi dipendente';
include '../template/privatepage_params.php'; ?>

<h1 class="mt-4">I tuoi interventi</h1>
<?php

$conn = DataConnect();
$sql = "SELECT ticket.id, ticket.dataapertura,ticket.descrizione FROM ticket INNER JOIN report ON ticket.id = report.fk_ticket
 WHERE ticket.isaperto = 1 AND report.fk_dipendente = (SELECT id FROM utenza WHERE username = ?)";
$sth = $conn->prepare($sql);
$sth->bind_param('s', $_SESSION['utente']);
$sth->execute();
$data = $sth -> get_result();
if($data != null) {
  $contents = PreparaTesti($data);
  PrintSolutions($contents[0], $contents[1], $contents[2]);
}
/* $conn = DataConnect();
 $sql = "SELECT id, dataapertura,descrizione FROM ticket
 WHERE isaperto = 1 AND fk_dipendente = ?";
 $sth = $conn -> prepare($sql);
 mysqli_stmt_bind_param($sth, 'i', IDByUser($conn, $_SESSION['utente'])); //'?'
 //$sth -> bind_param(
 $data = $sth -> execute();
 $contents = PreparaTesti($data);
 PrintSolutions($contents[0], $contents[1]);

function IDByUser($conn, $user){
    $sql = "SELECT id FROM utenza WHERE username = ?";
    $sth = $conn -> prepare($sql);
    $sth -> bind_param('s', $user);
 }*/
function PreparaTesti($data)
{
  $titoli = array();
  $testi = array();
  $ids = array();
  foreach ($data as $d) {
    array_push($titoli, "Intervento n." . $d['id'] . " aperto il " . $d['dataapertura']);
    array_push($testi, $d['descrizione']);
    array_push($ids, $d['id']);
  }
  return array($titoli, $testi, $ids);
}
function PrintSolutions($titoli, $testi, $ids)
{
  for ($i = 0; $i < count($titoli); $i++) {
    $href = "writereport.php?Id=$ids[$i]";
    $href2 = "measures.php?Id=$ids[$i]"; 
    if($i % 3 == 0) echo "<div class='containerone'>";
    $template = "
        <div class='container'>
        <a><p>$titoli[$i]</p></a>
        <a>$testi[$i]</a>
        </br>
        <a href='$href2'> Visualizza report scritti sull'attività</a>
        </br>
        <a href='$href'> Scrivi report sull'attività</a>
        </div>";
    echo $template;
    if ($i % 3 == 2) echo " </div>";
  }
  if (count($titoli) % 3 != 0) echo " </div>";
  #region codice commentato 
  /*
  <div class='container'>
        <a><p></p></a>
    <a>Tuo problema con Soluzione 1</a>
    </div>
    <div  class='container magenta'>
        <a><p>Soluzione 1</p></a>
    <a>Tuo problema con Soluzione 1</a>
    </div>
  */
  #endregion codice commentato
}

?>
<br>
<form>


</form>

<!-- /#page-content-wrapper -->

</div>
</body>


</html>

