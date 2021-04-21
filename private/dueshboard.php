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
function Bars($conn){
    $sql = "SELECT r.fk_ticket, COUNT(r.fk_dipendente) AS NumDipendenti, t.descrizione 
    FROM report r INNER JOIN ticket t ON r.fk_ticket = t.id
    GROUP BY fk_ticket";
    $stmt2 = $conn -> prepare($sql);
    $stmt2 -> execute();
    $data2 = $stmt2 ->get_result();
    $lista_perc_interventi = $data2 -> fetch_assoc();
    for($i = 0; $i < $data2 ->num_rows; $i++){
        $perc = 100*($lista_perc_interventi[$i]['NumDipendenti'])/($lista_perc_interventi[$i]['NumDipendenti']+1);
    echo "<h4 class='small font-weight-bold'>$lista_perc_interventi[$i]['descrizione']<span class='float-right'>$perc%</span></h4>
    <div class='progress mb-4'>
        <div class='progress-bar bg-danger' aria-valuenow='20' aria-valuemin='0' aria-valuemax='100' style='width: $perc%;'><span class='sr-only'>%</span></div>
    </div>";
    }
}
$title = "Dashboard";
include_once '../template/privatepage_params.php'; ?>
<div class="row">
    <div class="col-lg-6 mb-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="text-primary font-weight-bold m-0">Projects</h6>
            </div>
            <div class="card-body">
                <?php Bars($conn); ?>
                <h4 class="small font-weight-bold">Server migration<span class="float-right">20%</span></h4>
                <div class="progress mb-4">
                    <div class="progress-bar bg-danger" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width: 20%;"><span class="sr-only">20%</span></div>
                </div>
                <h4 class="small font-weight-bold">Sales tracking<span class="float-right">40%</span></h4>
                <div class="progress mb-4">
                    <div class="progress-bar bg-warning" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 40%;"><span class="sr-only">40%</span></div>
                </div>
                <h4 class="small font-weight-bold">Customer Database<span class="float-right">60%</span></h4>
                <div class="progress mb-4">
                    <div class="progress-bar bg-primary" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%;"><span class="sr-only">60%</span></div>
                </div>
                <h4 class="small font-weight-bold">Payout Details<span class="float-right">80%</span></h4>
                <div class="progress mb-4">
                    <div class="progress-bar bg-info" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100" style="width: 80%;"><span class="sr-only">80%</span></div>
                </div>
                <h4 class="small font-weight-bold">Account setup<span class="float-right">Complete!</span></h4>
                <div class="progress mb-4">
                    <div class="progress-bar bg-success" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%;"><span class="sr-only">100%</span></div>
                </div>
            </div>
        </div>
    </div>
