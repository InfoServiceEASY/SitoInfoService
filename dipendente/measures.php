<?php
session_start();
include('../dal.php');
$title = 'Lista report compilati dal dipendente';
include '../template/privatepage_params.php'; ?>
<h1 class="mt-4">I tuoi report</h1>
<?php
$id = $_GET['Id'];
$conn = DataConnect();
$sql = "SELECT report.id, report.datainizio, report.datafine, report.isrisolto,ticket.descrizione, report.commento FROM ticket INNER JOIN report ON ticket.id = report.fk_ticket
 WHERE ticket.isaperto = 1 AND report.fk_dipendente = (SELECT id FROM utenza WHERE username = ?) and ticket.id=?";
$sth = $conn->prepare($sql);
$sth->bind_param('si', $_SESSION['utente'],$id);
$sth->execute();
$data = $sth -> get_result();
if($data != null) {
  $contents = PreparaTesti($data);
  PrintSolutions($contents[0], $contents[1], $contents[2]);
}

function PreparaTesti($data)
{
  $titoli = array();
  $testi = array();
  $ids = array();
  foreach ($data as $d) {
    array_push($titoli, "Report n." . $d['id'] ." \n relativo all'intervento n." . $_GET['Id'] . "\n con data di inizio il " . $d['datainizio']. " \n e con fine il ".$d['datafine']. ".");
    array_push($testi, $d['descrizione'] . "\n" . $d['isrisolto'] == 1? "Risolto":"Non risolto." . "\n Commento cliente:" . ($d['commento'] == null? "non pervenuto": $d['commento']));
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

  <style>
    .containerone {
      display: flex;
      height: 300px;
    }

    div.container {
      flex: 1;
      border-radius: 25px;
      border: 2px solid white;
      margin-right: 10px;
      margin-top: 10px;
    }

    .container:hover {
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.8);
    }
  </style>
</form>

<!-- /#page-content-wrapper -->

</div>
</body>


</html>
