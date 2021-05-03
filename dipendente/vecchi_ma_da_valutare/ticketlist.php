<?php
/*lista 
 $sql = "SELECT id, dataapertura,descrizione FROM ticket
    WHERE isaperto = 1 AND fk_dipendente = ?"
    $sth = $conn -> prepare($sql);
    $sth -> bindparam('?', IDByUser($_SESSION['utente'])); //SELECT id from dipendente WHERE username = parametro ($_SESSION['utente'])   
*/
session_start();
include('../dal.php');
$title = 'Lista interventi dipendente'; // mettere titolo più corto
include '../template/privatepage_params.php'; ?>
<?php
$conn = DataConnect();
/*$sql = "SELECT ticket.id, ticket.dataapertura,ticket.descrizione FROM ticket INNER JOIN report ON ticket.id = report.fk_ticket
 WHERE ticket.isaperto = 1 AND report.fk_dipendente = (SELECT id FROM utenza WHERE username = ?)";
*/
$query1 = "SELECT t.id, t.dataapertura,t.descrizione , r.attività FROM ticket t INNER JOIN report r ON t.id = r.fk_ticket
 WHERE t.isaperto = 1 AND r.fk_dipendente =? and r.isconvalidato is null "; //r.attività is null, ma se si sbaglia?
$query2 = "SELECT t.id, t.dataapertura,t.descrizione FROM ticket t INNER JOIN report r ON t.id = r.fk_ticket
 AND r.fk_dipendente =? and  t.isaperto = 0 ";
function prova($sql, $h1, $chiuso)
{
  $conn = DataConnect();
  $id = GetUser()[0];
  $sth = $conn->prepare($sql);
  $sth->bind_param('i', $id);
  $sth->execute();
  $data = $sth->get_result();

  $template = "<h1 class='mt-4'>" . $h1 . "</h1>";
  if ($data->num_rows > 0) {
    for ($i = 0; $i < $data->num_rows; $i++) {
      $row = $data->fetch_assoc();
      $href = "writereport.php?Id=" . $row['id'];
      $href2 = "measures.php?Id=" . $row['id'];
      if ($i % 3 == 0) {
        $template .= "<div class='containerone'>";
      }
      $template .= "        
    <div class='contenitore'>
    <p>Intervento n." . $row['id'] . " aperto il " . $row['dataapertura'] . "</p>
    <p> " . $row['descrizione'] . "</p>
    </br>
    <a href='$href2'> Visualizza report sull'attività</a>
    </br>";
    $template .= (is_null($row['attività']))? "<a href='$href'> Scrivi report sull'attività</a> </div>" :
    "</div>";
      if ($i % 3 == 2) $template .= " </div>";
    }
    if ($data->num_rows % 3 != 0) $template .= " </div>";
  } else
    $template .= "<p>Al momento non hai ticket assegnati</p>";
  $conn->close();
  echo $template;
}
prova($query1, "Interventi aperti", false);
//prova($query3, "Interventi in attesa di convalida", false);
prova($query2, "Interventi chiusi", true);


?>

<br>
</div>
</body>
</html>