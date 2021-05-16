<?php
session_start();
$title = 'Dashboard';
include_once '../dal.php';
include_once '../template/privatepage_params.php';
Session();
$conn = DataConnect();
if (isset($_GET['data']) && !is_null($_GET['data']) && $_GET['data'] <= intval(date("Y")) && $_GET['data'] >= 2001) {
    $data = intval($_GET['data']);
} else {
    $data = intval(date("Y")) - 1;
}
$stmt = $conn->prepare( $_SESSION['member'] == "dipendente"? 
"SELECT monthname(t.dataapertura) as mese,  count(*) AS count FROM ticket t inner join report r on t.id = r.fk_ticket WHERE YEAR(t.dataapertura)=" . $data . " and r.fk_dipendente = ?  GROUP BY mese, month(t.dataapertura) order by month(t.dataapertura)"
:
"SELECT monthname(dataapertura) as mese,  count(*) AS count FROM ticket WHERE YEAR(dataapertura)=" . $data . "  GROUP BY mese, month(dataapertura) order by month(dataapertura)");
if($_SESSION['member'] == 'dipendente') $stmt -> bind_param('i', GetUser()[0]);
$stmt->execute();
$result = $stmt->get_result(); 
$mese = array();
$somma = array();
$r = array();
while($row = $result -> fetch_assoc()){
    array_push($r, $row); 
} 
function FillMonths($rows){
    $months = array();
    for($i=0; $i < count($rows); $i++)
        array_push($months, $rows[$i]['mese']);
    //ottenuti tutti i mesi, procedo cercando di capire se ho ticket o no    
    $result = array();
    $arr = array("January", "February", "March", "April",
     "May", "June", "July", "August", "September", "October", "November", "December");
     $j = 0;
     for($i = 0; $i < count($arr); $i++){
        if(in_array($arr[$i], $months)){
            $result[$arr[$i]] = $rows[$j]["count"];
            $j++;
        }
        else 
            $result[$arr[$i]] = 0;
     }
     return $result;
}
$data = FillMonths($r);
/*foreach ($result as $row) {
    array_push($mese, array(
        $row["mese"]
    ));
    array_push($somma, array(
        $row["count"]
    ));
}*/
foreach($data as $m =>$c){
    array_push($mese, array(
        $m
    ));
    array_push($somma, array(
        $c
    ));
}
$tutto = ContaTutto($data);
$featurerequest = RitornaPercentuale("Feature request", $tutto, $data, $_SESSION['member']);
$domanda = RitornaPercentuale("Domanda", $tutto, $data, $_SESSION['member']);
$incidente = RitornaPercentuale("Incidente", $tutto, $data, $_SESSION['member']);
$problema = RitornaPercentuale("Problema", $tutto, $data, $_SESSION['member']);
$unresolved = RitornaNumero('unresolved', $data, $_SESSION['member']);
$unassigned = RitornaNumero('unassigned', $data, $_SESSION['member']); 
$open = RitornaNumero('open', $data, $_SESSION['member']);
$solved = RitornaNumero('solved', $data, $_SESSION['member']);
?>

<div class="d-sm-flex justify-content-between align-items-center mb-4">
    <h3 class="text-dark mb-0">Dashboard</h3>
</div>
<div class="row">
    <div class="col-md-6 col-xl-3 mb-4">
        <div class="card shadow border-left-primary py-2">
            <div class="card-body">
                <div class="row align-items-center no-gutters">
                    <div class="col mr-2">
                        <div class="text-uppercase text-primary font-weight-bold text-xs mb-1"><span>unresolved</span></div>
                        <div class="text-dark font-weight-bold h5 mb-0"><span><?php echo $unresolved; ?></span></div>
                    </div>
                    <div class="col-auto"><i class="fas fa-calendar fa-2x text-gray-300"></i></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3 mb-4">
        <div class="card shadow border-left-success py-2">
            <div class="card-body">
                <div class="row align-items-center no-gutters">
                    <div class="col mr-2">
                        <div class="text-uppercase text-success font-weight-bold text-xs mb-1"><span>Unassigned</span></div>
                        <div class="text-dark font-weight-bold h5 mb-0"><span><?php echo $unassigned ?></span></div>
                    </div>
                    <div class="col-auto"><i class="fas fa-dollar-sign fa-2x text-gray-300"></i></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3 mb-4">
        <div class="card shadow border-left-info py-2">
            <div class="card-body">
                <div class="row align-items-center no-gutters">
                    <div class="col mr-2">
                        <div class="text-uppercase text-info font-weight-bold text-xs mb-1"><span>Open</span></div>
                        <div class="row no-gutters align-items-center">
                            <div class="col-auto">
                                <div class="text-dark font-weight-bold h5 mb-0 mr-3"><span><?php echo $open; ?></span></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3 mb-4">
        <div class="card shadow border-left-warning py-2">
            <div class="card-body">
                <div class="row align-items-center no-gutters">
                    <div class="col mr-2">
                        <div class="text-uppercase text-warning font-weight-bold text-xs mb-1"><span>Solved</span></div>
                        <div class="text-dark font-weight-bold h5 mb-0"><span><?php echo $solved; ?></span></div>
                    </div>
                    <div class="col-auto"><i class="fas fa-comments fa-2x text-gray-300"></i></div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<div class="row">
    <div class="col-lg-7 col-xl-8">
        <div class="card shadow mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="text-primary font-weight-bold m-0">Ticket creati nel <?php echo $data?> </h6>
                <form method="get">
                    <input type="number" name="data" placeholder="YYYY" min="2001" max="2021">
                    <button>ok</button>
                </form>
            </div>
            <div class="card-body">
                <div style="height: 600px; " class="chart-area">
                    <canvas id="chLine"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-5 col-xl-4">
        <div class="card shadow mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="text-primary font-weight-bold m-0">Situazione ticket</h6>
            </div>
            <div class="card-body">
                <div style="height: 600px; " class="chart-area">

                    <canvas id="torta"></canvas>
                </div>
            </div>
        </div>
    </div>

</div>
<div style="width:200%;">
    <div class="col-lg-6 mb-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="text-primary font-weight-bold m-0">Tipologie </h6>
            </div>
            <div class="card-body">
                <h4 class="small font-weight-bold">Feature Request<span class="float-right"><?php echo $featurerequest.'%' ?></span></h4>
                <div class="progress mb-4">
                    <div class="progress-bar bg-danger"  aria-valuemin="0" aria-valuemax="100" style=<?php echo '"width:' . $featurerequest . '%"' ?>><span class="sr-only"><?php echo $featurerequest.'%' ?></span></div>
                </div>
                <h4 class="small font-weight-bold">Domanda<span class="float-right"><?php echo $domanda.'%' ?></span></h4>
                <div class="progress mb-4">
                    <div class="progress-bar bg-warning"  aria-valuemin="0" aria-valuemax="100" style=<?php echo '"width:' . $domanda . '%"' ?>><span class="sr-only"><?php echo $domanda.'%' ?></span></div>
                </div>
                <h4 class="small font-weight-bold">Incidente<span class="float-right"><?php echo $incidente.'%' ?></span></h4>
                <div class="progress mb-4">
                    <div class="progress-bar bg-primary"  aria-valuemin="0" aria-valuemax="100" style=<?php echo '"width:' . $incidente . '%"' ?>><span class="sr-only"><?php echo $incidente.'%' ?></span></div>
                </div>
                <h4 class="small font-weight-bold">Problema<span class="float-right"><?php echo $problema.'%' ?></span></h4>
                <div class="progress mb-4">
                    <div class="progress-bar bg-info"  aria-valuemin="0" aria-valuemax="100" style=<?php echo '"width:' . $problema . '%"' ?>><span class="sr-only"><?php echo $problema.'%' ?></span></div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.bundle.min.js"></script>
<script src="../assets/js/bs-init.js"></script>
<script>
    var chartData = {
        labels: <?php echo json_encode($mese); ?>,
        datasets: [{
            data: <?php echo json_encode($somma); ?>,
        }]
    };
    var chLine = document.getElementById("chLine");
    if (chLine) {
        new Chart(chLine, {
            type: 'line',
            data: chartData,
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: false
                        }
                    }]
                },
                legend: {
                    display: false
                }
            }
        });
    }

    var torta = document.getElementById("torta");
    if (torta) {
        new Chart(torta, {
            type: "doughnut",
            data: {
                labels: ["risolti", "non risolti", "in lavorazione"],
                datasets: [{
                    "backgroundColor": ["#4e73df", "#1cc88a", "#36b9cc"],
                    "borderColor": ["#ffffff", "#ffffff", "#ffffff"],
                    "data": <?php echo '["' . $solved . '","' . $unresolved. '","' . $open. '"]' ?>
                }]
            },
            options: {
                maintainAspectRatio: false,
                legend: {
                    display: true
                }
            }
        })
    }
</script>
<?php /*$endtime = microtime(true); // Bottom of page

            printf("Page loaded in %f seconds", $endtime - $starttime); */ ?>
</body>

</html>
