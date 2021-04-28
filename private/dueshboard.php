<?php
session_start();
$title = "Dashboard";
include_once '../template/privatepage_params.php';
include_once '../dal.php';
Session();
?>
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

<?php
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
function FeedBack($conn){
    $sql = "SELECT MAX(r.isconvalidato) AS valore FROM report r
    WHERE r.isconvalidato is not null
    GROUP BY r.fk_ticket";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();
    $counter_1 = 0;
    $counter_0 = 0;
    foreach($result as $r)
    {
        $num = $r["valore"];
        $num == 0 ? $counter_0++ : $counter_1++;
    }
    $percentage = 100*($counter_1/ ($counter_1 + $counter_0));
    return array("Il ". strval($percentage). "% dei nostri clienti è soddisfatto del nostro servizio.", strval($percentage));
}
function Bars($conn){
    $sql = "SELECT r.fk_ticket, COUNT(r.fk_dipendente) AS NumDipendenti, t.descrizione 
    FROM report r INNER JOIN ticket t ON r.fk_ticket = t.id
    WHERE t.isaperto = 1
    GROUP BY fk_ticket";
    $stmt2 = $conn -> prepare($sql);
    $stmt2 -> execute();
    $data2 = $stmt2 ->get_result();
    $colors = array("danger", "primary", "warning", "info", "success"); //rosso, blue, giallo, azzurro, verde
    //var_dump($lista_perc_interventi);
    $j = 0; 
    for($i = 0; $i < $data2 -> num_rows; $i++){
      $lista_perc_interventi = $data2 -> fetch_assoc();
      $dipendenti = $lista_perc_interventi['NumDipendenti'];
      $perc = 100*($dipendenti)/($dipendenti+1);
        if(($j < 4 && rand(0,50) < 30) || $perc < 33 || $perc > 67){
        //quattro random, più quelli completati a meno di un terzo o più di due terzi
        $parole = explode(' ', $lista_perc_interventi['descrizione']);
        $testo = $parole[0] . ' ' . $parole[1] . ' ' . $parole[2] . ' ' . $parole[3];
        $color = $perc >= 75? $colors[count($colors) - 1] : ($perc < 50? $colors[0] : $colors[rand(1,count($colors) - 2)]);
      echo "<h4 class='small font-weight-bold'>".$testo."<span class='float-right'>".strval($perc)."%</span></h4>";
      echo "<div class='progress mb-4'>
        <div class= '" ."progress-bar bg-".strval($color)."' aria-valuenow=".strval($perc)." aria-valuemin='0' aria-valuemax='100' style='width: $perc%;'><span class='sr-only'>%</span></div>
        </div>";
      $j++;  
      }
    }
}
function Stats($conn){
  $solved_unresolved = "SELECT COUNT(isaperto) AS val FROM ticket 
  GROUP BY isaperto
  ORDER BY isaperto";
  $assigned_unassigned = "SELECT COUNT(isassegnato) AS val FROM ticket 
  GROUP BY isassegnato
  ORDER BY isassegnato";

  $stmt1 = $conn -> prepare($solved_unresolved);
  $stmt1 -> execute();
  $data1 = $stmt1 ->get_result();
  $stmt2 = $conn -> prepare($assigned_unassigned);
  $stmt2 -> execute();
  $data2 = $stmt2 ->get_result();
  $i = 0;
  $solved = $data1 -> fetch_assoc()['val'];
  $unresolved = $data1-> fetch_assoc()['val'];
  $assigned = $data2-> fetch_assoc()['val'];
  $unassigned = $data2-> fetch_assoc()['val'];
  return array("Solved" => $solved,
  "Unresolved" => $unresolved,
  "Assigned" => $assigned,
  "Unassigned" => $unassigned);
}

$stats = Stats($conn);
?>
<div class="containerone" style="width:97%; float: left;">
  <div class="containerr" style="width:120%; float: left;">
    <a style="float: left; font-size: 200%;">
      <p>Unresolved</p>
      </br> 
      <?php echo $stats["Unresolved"];?>
    </a>
  </div>
  <div class="containerr"  style="width:120%; float: left;">
    <a style="float: left; font-size: 200%;">
      <p>Solved</p>
      </br>
      <?php echo $stats["Solved"]; ?>
    </a>
  </div>
  <div class="containerr" style="width:120%; float: left;">
    <a style="float: left; font-size: 200%;">
      <p>Assigned</p>
      </br>
      <?php echo $stats["Assigned"]; ?>
    </a> 
    
  </div>
  <div class="containerr" style="width:120%; float: left;">
    <a style="float: left; font-size: 200%;">
      <p>Unassigned</p>
      </br>
      <?php echo $stats["Unassigned"]; ?>
    </a>
  </div>
</div>
<br>

<div style=" height: 275px; width: 95%;" id="lineChart">
 </div>
<div class="row" style="width: 100%;">
    <div class="col-lg-6 mb-4" style="width: 50%;">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="text-primary font-weight-bold m-0">Projects</h6>
            </div>
            <div class="card-body">
                <?php Bars($conn); ?>
            </div>
        </div>
    </div>
    <div class="col-lg-5 col-xl-4" style="width: 50%;">
    <?php $feed = FeedBack($conn);?>
                            <div class="card shadow mb-4" style="width: 150%;">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h6 class="text-primary font-weight-bold m-0">Feedback</h6>
                                    
                                </div>
                                <div class="card-body" style="width: 145%;">
                                    <div class="chart-area">
                                    <canvas data-bs-chart='{{&quot;type&quot;:&quot;doughnut&quot;,
                                      "data": {"labels":
                                      [<?php echo $feed[0]; ?>],
                                      "datasets":
                                      [
                                      {"label":"",
                                      "backgroundColor":["#1cc88a", "#ee0018"],
                                      "borderColor":["#ffffff"],
                                      "data":{
                                      [&quot;<?php echo $feed[1];?>&quot;,
                                       &quot;<?php echo strval(100 - intval($feed[1])); ?>&quot;
                                       ]
                                       }]},
                                       "options":
                                       {"maintainAspectRatio":false,
                                       "legend":{"display":false},
                                       "title":{}}}"'>
                                    </canvas>
                                    </div>
                                    <div class="text-center small mt-4"><span class="mr-2"><i class="fas fa-circle text-primary"></i>&nbsp;<?php echo $feed[0]; ?></span></div>
                                </div>
                            </div>
                        </div>
</div>
<!--data-bs-chart='{"type":"doughnut","data":{"labels":["<?php echo $feed[0]; ?>","",""],"datasets":[{"label":"","backgroundColor":["#4e73df","#1cc88a","#36b9cc"],"borderColor":["#ffffff"],"data":[&quot;<?php echo $feed[1]; ?>&quot;, &quot;<?php echo strval(100 - intval($feed[1])); ?>&quot;]}]},"options":{"maintainAspectRatio":false,"legend":{"display":false},"title":{}}} -->    

<footer class="bg-white sticky-footer">
                <div class="container my-auto">
                    <div class="text-center my-auto copyright"><span>Copyright © Infoservice 2021</span></div>
                </div>
            </footer>
        </div><a class="border rounded d-inline scroll-to-top" href="#page-top"><i class="fas fa-angle-up"></i></a></div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.bundle.min.js"></script>
    <script src="assets/js/bs-init.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.js"></script>
    <script src="assets/js/theme.js"></script>










