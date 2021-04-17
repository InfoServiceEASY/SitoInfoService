<?php
session_start();
$title = 'Lista interventi dipendente'; 
include_once '../dal.php';
include_once '../template/privatepage_params.php';
Session();
?>
<h1 class="mt-4">I ticket assegnati</h1>
<br>
<?php
$uno = 1;
$conn = DataConnect();
$stmt = $conn->prepare('SELECT t.id,t.dataapertura,t.descrizione,t.oggetto,t.tipologia,s.nome FROM ticket t
INNER JOIN settore s on s.id=t.fk_settore LEFT JOIN report r ON t.id =r.fk_ticket 
AND t.isaperto=? ORDER BY t.dataapertura DESC limit 12');
$stmt->bind_param('i', $uno);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();
if ($result->num_rows > 0) {
  for ($i = 0; $i < $result->num_rows; $i++) {
    $row = $result->fetch_assoc();
    (strlen($row['descrizione']) > 5 ? $descrizione = substr($row['descrizione'], 0, 20) . "..." : $descrizione = $row['descrizione']);
    $href = "AssegnaTicket.php?id=" . $row['id'];
    if ($i % 3 == 0) echo "<div class='containerone'>";
    $template = "
        <div class='contenitore'>
        <p>Intervento n." . $row['id'] . "</p><p> aperto il " . $row['dataapertura'] . "</p>
        <p><strong>tipologia</strong> " . $row['tipologia'] . "</p>
        <p><strong>settore</strong> " . $row['nome'] . "</p>
        <p> <strong>oggetto</strong> " . $row['oggetto'] . "</p>
        <p> <strong>descrizione</strong> " . $descrizione . "</p>
        <a href='$href'> assegna ticket</a>
        </div>";
    echo $template;
    if ($i % 3 == 2) echo " </div>";
  }
}
?>
<br>
</div>
</body>
</html>