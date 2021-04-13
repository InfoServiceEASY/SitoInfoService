<?php
session_start();
include_once '../dal.php';
include_once '../template/privatepage_params.php';
Session();
$title = 'Lista interventi dipendente'; ?>
<h1 class="mt-4">I ticket Aperti</h1>
<br>
<?php
$uno = 1;
$conn = DataConnect();
$stmt = $conn->prepare('SELECT t.id,t.dataapertura,t.descrizione,t.oggetto,t.tipologia,s.nome FROM ticket t
INNER JOIN settore s on s.id=t.fk_settore LEFT JOIN report r ON t.id =r.fk_ticket AND r.fk_ticket IS NULL 
AND t.isaperto=? ORDER BY t.dataapertura DESC');
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
        <div class='container'>
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
  //  $row = $result->fetch_assoc();
  // $contents = PreparaTesti($data);
  //PrintSolutions($contents[0], $contents[1], $contents[2],$contents[3], $contents[4], $contents[5]);
}
?>
<br>
<form>

  <style>
    p {
      margin: 1
    }

    .containerone {
      display: flex;
      height: 100%;
      margin-top: 5%;
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