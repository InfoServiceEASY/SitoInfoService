<?php
//variables
$letterautente = strtoupper($_SESSION['utente'][0]);
//i nomi delle pagine devono essere parametroarraynometesto.php
$sidebar_text = array();

if ($_SESSION["member"] == "cliente") {
  $sidebar_text = array("Solutions", "Ticket", "MyTicket");
} else $_SESSION["member"] == "admin" ? $sidebar_text = array("Ticket aperti", "Ticket chiusi", "Visualizza contatto") : $sidebar_text = array("TicketList");
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title> <?php echo $title; ?></title>
  <script type="text/javascript" src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/stylesheetprivato.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.24/sb-1.0.1/sp-1.2.2/datatables.min.css" />

    <script src="../assets/js/jquery.min.js"></script>
    <script src="../assets/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/script.js"></script>
    <script src="https://smtpjs.com/v3/smtp.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.24/sb-1.0.1/sp-1.2.2/datatables.min.js"></script>
</head>

<body onload="menuacomparsa();">
  <div class="d-flex" id="wrapper">
    <div class="bg-light border-right" id="sidebar-wrapper">
      <div class="sidebar-heading">Infoservice </div>
      <div class="list-group list-group-flush" id="sidebar">
        <script>
          sidebar(<?php echo json_encode($sidebar_text); ?>, <?php echo '"' . $_SESSION['member'] . '"' ?>)
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
            <li class="nav-item dropdown">
              <a class="nav-link " href="#" id="navbarDropdown" role="button" style="background-color:#007bff;    border-radius: 50px;" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <?php echo $letterautente; ?>
              </a>
              <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                <a class="dropdown-item" href="../private/logout.php">LogOut</a>
              </div>
            </li>
          </ul>
        </div>
      </nav>
      <div class="container-fluid">