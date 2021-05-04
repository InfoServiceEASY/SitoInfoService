<?php
include_once 'dal.php';
$starttime = microtime(true); // Top of page
// Code

$conn = DataConnect();
$nomeColonna = 1; // 1 all'inizio perchè se si tratta di helpdesk farò where 1=1 e quindi sempre 
$condizione = "";
if ($_SESSION["member"] == "admin") $condizione = 1;
elseif ($_SESSION["member"] == "cliente") {
    $nomeColonna = "fk_cliente";
    $condizione = GetUser()[0];
}

if ($_SESSION["member"] == "dipendente") {
    $sql = "SELECT t.dataapertura, count(*) AS count FROM ticket t INNER JOIN report r ON t.id = r.fk_ticket
  WHERE t.isaperto = 1 AND r.fk_dipendente = (SELECT id FROM utenza WHERE username = ?)";
    $condizione = $_SESSION['utente'];
}

$conn->close();
$conn = DataConnect();
if (isset($_GET['data']) && intval($_GET['data']) && !is_null($_GET['data']) && $_GET['data'] < 2021 && $_GET['data'] > 2001) {
    $data = intval($_GET['data']);
} else {
    $data = 2019;
}
$stmt = $conn->prepare("SELECT monthname(str_to_date(MONTH(dataapertura),'%m')) as mese,  count(*) AS count FROM ticket WHERE YEAR(dataapertura)=" . $data . "  GROUP BY MONTH(dataapertura) order by MONTH(dataapertura) limit 2");
$stmt->execute();
$result = $stmt->get_result();
$mese = array();
$somma = array();

foreach ($result as $row) {
    array_push($mese, array(
        $row["mese"]
    ));
    array_push($somma, array(
        $row["count"]
    ));
}
$tutto=ContaTutto();

$featurerequest = RitornaPercentuale("Feature Request",$tutto);
$domanda = RitornaPercentuale("Domanda",$tutto);
$incidente = RitornaPercentuale("Incidente",$tutto);
$problema = RitornaPercentuale("Problema",$tutto);
/*
$featurerequest =12;
$domanda =12;
$incidente =12;
$problema =12;*/
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title> Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/stylesheetprivato.css" rel="stylesheet">
    <script src="assets/js/script.js"></script>

</head>

<body id="page-top" onload="menuacomparsa();">

    <div class="d-flex" id="wrapper">
        <div class="bg-light border-right" id="sidebar-wrapper">
            <div class="sidebar-heading">Infoservice </div>
            <div class="list-group list-group-flush" id="sidebar">
                <script>
                    sidebar(["TicketList"], "dipendente")
                </script>
            </div>
        </div>
        <div id="page-content-wrapper">

            <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
                <button class="btn btn-primary" id="menu-toggle">Menu</button>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ml-auto mt-2 mt-lg-0">
                        <li class="nav-item active">
                            <a class="nav-link" href="../index.php">Home <span class="sr-only">(current)</span></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Link</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link " href="#" id="navbarDropdown" role="button" style="background-color:#007bff;    border-radius: 50px;" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                P </a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="../private/Logout.php">LogOut</a>
                                <a class="dropdown-item" href="Impostazioni.php">Impostazioni</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#">Something else here</a>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>
            <div class="d-flex flex-column" id="content-wrapper">
                <div id="content">

                    <div class="container-fluid">
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
                                                <div class="text-dark font-weight-bold h5 mb-0"><span><?php echo RitornaNumero('unresolved') ?></span></div>
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
                                                <div class="text-dark font-weight-bold h5 mb-0"><span><?php echo RitornaNumero('unassigned') ?></span></div>
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
                                                        <div class="text-dark font-weight-bold h5 mb-0 mr-3"><span><?php echo RitornaNumero('open') ?></span></div>
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
                                                <div class="text-dark font-weight-bold h5 mb-0"><span><?php echo RitornaNumero('solved') ?></span></div>
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
                                    <h6 class="text-primary font-weight-bold m-0">Ticket creati nel </h6>
                                    <form method="get">
                                        <input type="number" name="data" placeholder="YYYY" min="2001" max="2021">
                                        <button>ok</button>
                                    </form>
                                </div>
                                <div class="card-body">
                                    <div style="height: 450px; " class="chart-area">
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
                                    <div style="height: 450px; " class="chart-area"><canvas data-bs-chart='{"type":"doughnut","data":{"labels":["risolti","non risolti","in lavorazione"],"datasets":[{"label":"","backgroundColor":["#4e73df","#1cc88a","#36b9cc"],"borderColor":["#ffffff","#ffffff","#ffffff"],"data":["50","30","15"]}]},"options":{"maintainAspectRatio":false,"legend":{"display":true},"title":{}}}'></canvas></div>
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
                                    <h4 class="small font-weight-bold">Feature Request<span class="float-right"><?php echo $featurerequest ?></span></h4>
                                    <div class="progress mb-4">
                                        <div class="progress-bar bg-danger" aria-valuemin="0" aria-valuemax="100" style=<?php echo '"width:' . $featurerequest . '"' ?>><span class="sr-only"><?php echo $featurerequest ?></span></div>
                                    </div>
                                    <h4 class="small font-weight-bold">Domanda<span class="float-right"><?php echo $domanda ?></span></h4>
                                    <div class="progress mb-4">
                                        <div class="progress-bar bg-warning" aria-valuemin="0" aria-valuemax="100" style=<?php echo '"width:' . $domanda . '"' ?>><span class="sr-only"><?php echo $domanda ?></span></div>
                                    </div>
                                    <h4 class="small font-weight-bold">Incidente<span class="float-right"><?php echo $incidente ?></span></h4>
                                    <div class="progress mb-4">
                                        <div class="progress-bar bg-primary" aria-valuemin="0" aria-valuemax="100" style=<?php echo '"width:' . $incidente . '"' ?>><span class="sr-only"><?php echo $incidente ?></span></div>
                                    </div>
                                    <h4 class="small font-weight-bold">Problema<span class="float-right"><?php echo $problema ?></span></h4>
                                    <div class="progress mb-4">
                                        <div class="progress-bar bg-info" aria-valuemin="0" aria-valuemax="100" style=<?php echo '"width:' . $problema . '"' ?>><span class="sr-only"><?php echo $problema ?></span></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <script src="assets/js/jquery.min.js"></script>
         
            <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.bundle.min.js"></script>
            <script src="assets/js/bs-init.js"></script>
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
            </script>
            <?php $endtime = microtime(true); // Bottom of page

printf("Page loaded in %f seconds", $endtime - $starttime );?>
</body>

</html>