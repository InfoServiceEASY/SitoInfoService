<?php
session_start();
include_once '../dal.php';
Session();

$conn = DataConnect();
$nomeColonna = 1; // 1 all'inizio perchè se si tratta di helpdesk farò where 1=1 e quindi sempre 
$condizione = "";
if ($_SESSION["member"] == "admin") $condizione = 1;
elseif ($_SESSION["member"] == "cliente") {
  $nomeColonna = "fk_cliente";
  $condizione = GetUser()[0];
}
$query = "SELECT dataapertura , count(*) AS count FROM `ticket` where YEAR(dataapertura)>=YEAR(CURDATE())-2 and " . $nomeColonna . "=? group by dataapertura";

if ($_SESSION["member"] == "dipendente") {
  $sql = "SELECT t.dataapertura, count(*) AS count FROM ticket t INNER JOIN report r ON t.id = r.fk_ticket
  WHERE t.isaperto = 1 AND r.fk_dipendente = (SELECT id FROM utenza WHERE username = ?)";
  $condizione = $_SESSION['utente'];
}


$stmt = $conn->prepare($query);
$stmt->bind_param('i', $condizione);
$stmt->execute();
$result = $stmt->get_result();
$data = array();
foreach ($result as $row) {
  array_push($data, array(
    "data" => $row["dataapertura"],
    "somma" => $row["count"]
  ));
}
$title = "Dashboard";
include_once '../template/privatepage_params.php'; ?>
<div class="containerone">
  <div class="containerr">
    <a>
      <p>Unresolved</p>
    </a> 0
  </div>
  <div class="containerr">
    <a>
      <p>solved</p>
    </a>
    0
  </div>
  <div class="containerr">
    <a>
      <p>Overdue</p>
    </a>
    0
  </div>
  <div class="containerr">
    <a>
      <p>Unassigned</p>
    </a>
    0
  </div>
  <div class="containerr">
    <a>
      <p>Open</p>
    </a>
    0
  </div>
  <div class="containerr">
    <a>
      <p>On Hold</p>
    </a>
    0
  </div>
</div>
<br>

<div style=" height: 275px; width: 100%;" id="lineChart"> </div>
<button id="exportChart">Export Chart</button>
<div style="height: 400px;" class="containerone">
  <div style="margin-right: 0.57%;width:49%;  margin-top: 20px;" class="containerr">
    <p style="float: left;">Unresolved tickets</p>
    <a href="#">View details</a>

    <img style="margin-top: 60px;" src="
    https://eucfassetsgreen.freshdesk.com/production/a/assets/images/empty-states/unresolved-empty-eb60bb2b7b369cedbde7f34f11ec516e84dee3f466fd453f4bc621dcea912c98.svg" alt="unresolved" width="200" height="200">
  </div>
  <div style="text-align: left;margin-right: 0.57%;width:49%;margin-top: 20px;" class="containerr">
    <p style="float: left;">Your Satisfaction</p>
    <a href="#">View details</a>

    <img style="margin-top: 40px; width:80%" src="../assets/img/Soddisfazioni.PNG">
  </div>
</div>
<!--
    <div>
      <br>
      <p >positive</p>
      <p style="float: left; font-size:30px">0%</p>
    <img  src="
    https://eucfassetsgreen.freshdesk.com/production/a/assets/images/empty-states/unresolved-empty-eb60bb2b7b369cedbde7f34f11ec516e84dee3f466fd453f4bc621dcea912c98.svg" 
    alt="unresolved" width="50px">
    <p>negative</p>
      <span>0%</span>
    <img style="margin-top: 60px;" src="
    https://eucfassetsgreen.freshdesk.com/production/a/assets/images/empty-states/unresolved-empty-eb60bb2b7b369cedbde7f34f11ec516e84dee3f466fd453f4bc621dcea912c98.svg" 
    alt="unresolved">
    </div>-->


<style>
  a {
    padding: 5px;
    float: right;
  }

  .containerone {
    box-sizing: border-box;
    background-color: rgb(235, 239, 243);
    height: 100px;
  }

  div.containerr {
    background-color: white;
    height: 80%;
    margin-right: 0.57%;
    margin-top: 10px;
    text-align: center;
    float: left;
    width: 16%;
    position: relative;
  }

  div.containerr:first-child {
    margin-left: 0.57%;
  }

  .containerr:hover {
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.8);
  }
</style>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>
<script>
  chart(<?php echo json_encode($data); ?>);
</script>

</body>