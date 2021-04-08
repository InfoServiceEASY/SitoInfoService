<?php
if (!isset($_SESSION)) {
  session_start();
}
//variables
$letterautente = strtoupper($_SESSION['utente'][0]);
//i nomi delle pagine devono essere parametroarraynometesto.php
$sidebar_text = array();
/*$nome = basename($_SERVER['PHP_SELF']); //pagecorrente
switch($nome){ //dovrà essere invece $_SESSION['tipo_utente'] al posto di $nome e i casi saranno "customer", "employee", "helpdesk" 
    case 'ticket.php': 
        $sidebar_text = array("Dashboard", "Solutions","Ticket","Events","Profile","Status");
    break;
    case 'employee copy.php':
        $sidebar_text = array("Dashboard","Events","Profile","Status");
    break;
    case '': //helpdesk
        break;
}*/

if ($_SESSION["member"] == "cliente")  {$sidebar_text = array("Dashboard", "Solutions","Ticket","Events","Status","MyTicket");}
else $_SESSION["member"] == "helpdesk"? $sidebar_text = array("Dashboard","Events","Profile","Status","TicketAperti"): $sidebar_text = array("Dashboard","TicketList","Events","Profile","Status");

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title> <?php echo $title;?></title>

  <script type="text/javascript" src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
 
  <!--Bootstrap core CSS -->
  <link href="../assets/css/bootstrap.min.css" rel="stylesheet">

  <!-- Custom styles for this template -->
  <link href="../assets/css/stylesheetprivato.css" rel="stylesheet">


  <!-- Bootstrap core JavaScript -->
  <script src="../assets/js/jquery.min.js"></script>
  <script src="../assets/js/bootstrap.bundle.min.js"></script>
  <script src="../assets/js/script.js"></script>
</head>

<body onload="menuacomparsa();">
<div class="d-flex" id="wrapper">

<!-- Sidebar -->
<div class="bg-light border-right" id="sidebar-wrapper">
  <div class="sidebar-heading">Infoservice </div>
  <div class="list-group list-group-flush" id="sidebar">
  <script> sidebar( <?php echo json_encode($sidebar_text); ?>) </script>
 <!-- <?php //include 'sidebar_1.php';?> -->
  </div>
</div>
<!-- /#sidebar-wrapper -->

<!-- Page Content -->
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
          <a class="nav-link " href="#" id="navbarDropdown" role="button" style="background-color:yellow" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <?php echo $letterautente; ?>
          </a>
          <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
            <a class="dropdown-item" href="Logout.php">LogOut</a>
            <a class="dropdown-item" href="Impostazioni.php">Impostazioni</a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="#">Something else here</a>
          </div>
        </li>
      </ul>
    </div>
  </nav>
  <div class="container-fluid">