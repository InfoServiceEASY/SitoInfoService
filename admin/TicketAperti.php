<?php session_start();
include('../dal.php');
$title = 'Lista interventi dipendente';
include '../template/privatepage_params.php'; ?>
<h1 class="mt-4">I ticket Aperti</h1>
<br>
<?php

$conn = DataConnect();
$sql = "SELECT t.id, t.dataapertura,t.descrizione FROM ticket t
LEFT JOIN report r 
    ON t.id =r.fk_ticket
WHERE   r.fk_ticket IS NULL and t.isaperto=1";
$sth = $conn->prepare($sql);
$sth->bind_param('s', $_SESSION['utente']);
$sth->execute();
$data = $sth -> get_result();
if($data != null) {
  $contents = PreparaTesti($data);
  PrintSolutions($contents[0], $contents[1], $contents[2]);
}