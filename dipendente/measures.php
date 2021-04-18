<?php
session_start();
include('../dal.php');
$title = 'Lista report compilati dal dipendente';
include '../template/privatepage_params.php'; ?>
<h1 class="mt-4">I reports</h1>
<?php
$id = $_GET['Id'];
$fk_dipendente = GetUser()[0];
$conn = DataConnect();
if (!isset($_GET["Cancella"])) {
 /* $sql = "SELECT r.id, r.datainizio, r.datafine, r.isrisolto,t.descrizione, r.commento FROM ticket t INNER JOIN report r ON t.id = r.fk_ticket
  WHERE t.isaperto = 1 AND r.fk_dipendente = ? and t.id=?";*/ 
  $sql="SELECT r.id, r.datainizio, r.datafine, r.isrisolto,r.attività,t.descrizione, r.commento, t.isaperto FROM ticket t INNER JOIN report r ON t.id = r.fk_ticket where t.id=? and r.attività is not null";
  $sth = $conn->prepare($sql);
  $sth->bind_param('i',$id);
  $sth->execute();
  $data = $sth->get_result();
  if ($data != null) {
    if($data->num_rows > 0){
    for ($i = 0; $i < $data->num_rows; $i++) 
    {
      $row = $data -> fetch_assoc();
      $href = "writereport.php?Id=$id&ReportId=".$row['id'];
      $href2 = "measures.php?Id=$id&ReportId=".$row['id']."&Cancella=yes";
      if ($i % 3 == 0) echo "<div class='containerone' style='float:left'>";
      echo "<div class='contenitore'>
      ";
       echo "
          <a><p>
          Report n."
           . $row['id'] . " \n relativo all'intervento n." . $id . "\n con data di inizio il " . $row['datainizio'] . " \n e con fine il " . $row['datafine'] . 
           "</p></a>";
          echo "<a><p> Attività svolta: ". $row['attività'] ."</p></a>";
          echo "</br><a><p>" . $row['isrisolto'] == 1 ? "Risolto" : "Non risolto.";
          echo "</br>\n Commento cliente: " . ($row['commento'] == null ? "non pervenuto" : $row['commento']).
          "</p></a>";
          $template = (IsMine($conn, $row['id']) && $row["isaperto"]==1)? 
          ("
          <a href='$href2'> <p>Cancella questo report</p></a>".
          "<a href='$href'> <p>Modifica il report sull'attività</p></a>") : ("");
      echo $template;
      echo "</div>";
      if ($i % 3 == 2) echo " </div>";
    }
    if ($data->num_rows % 3 != 0) echo " </div>";
   }
   else{
    echo "<p>Al momento non sono stati scritti nuovi record.</p>";
   }
  }
} else {
  $id_report = $_GET['ReportId'];
  $sql = "UPDATE report set datafine=null,durata=null,attività=null,isrisolto=null where id=? and isconvalidato is null";
  $sth = $conn->prepare($sql);
  $sth->bind_param('i',  $id_report );
  $sth->execute();
  echo ("<script LANGUAGE='JavaScript'>
  window.alert('eliminato con successo');
  window.location.href='Ticketlist.php';
  </script>");
}

/*function PreparaTesti($data)
{
  $titoli = array();
  $testi = array();
  $ids = array();
  foreach ($data as $d) {
    array_push($titoli, "Report n." . $d['id'] . " \n relativo all'intervento n." . $_GET['Id'] . "\n con data di inizio il " . $d['datainizio'] . " \n e con fine il " . $d['datafine'] . ".");
    array_push($testi, $d['descrizione'] . "\n" . $d['isrisolto'] == 1 ? "Risolto" : "Non risolto." . "</br>\n Commento cliente: " . ($d['commento'] == null ? "non pervenuto" : $d['commento']));
    array_push($ids, $d["id"]);
  }
  return array($titoli, $testi, $ids);
}*/
function IsMine($conn, $id_report)
{
  $sql = "SELECT fk_dipendente FROM report WHERE id = ?";
  $sth = $conn->prepare($sql);
  $sth->bind_param('i', $id_report);
  $sth->execute();
  $fk_dipendente = $sth->get_result();
  $sth->close();
  $fk_dipendente = $fk_dipendente->fetch_assoc();
  return $fk_dipendente["fk_dipendente"] == GetUser()[0];
}
/*
function PrintSolutions($conn, $titoli, $testi, $ids, $id)
{
  for ($i = 0; $i < count($titoli); $i++) {
    $href = "writereport.php?Id=$id&ReportId=$ids[$i]";
    $href2 = "measures.php?Id=$id&ReportId=$ids[$i]&Cancella=yes";
    if ($i % 3 == 0) echo "<div class='containerone' style='float:left'>";
    $template = "
        <div class='container'>
        <a><p>$titoli[$i]</p></a>
        <a>$testi[$i]</a>
        </br>";
        $template = (IsMine($conn, $ids[$i])? ($template . "<a href='$href2'> Cancella questo report</a>
        </br><a href='$href'> Modifica il report sull'attività</a>
        </div>") :  $template);
    echo $template;
    if ($i % 3 == 2) echo " </div>";
  }
  if (count($titoli) % 3 != 0) echo " </div>";
  #region codice commentato 

}
*/
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
?>
<br>
<form>


</form>

<!-- /#page-content-wrapper -->

</div>
</body>


</html>