<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title> interventi dipendente</title>
    <script type="text/javascript" src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/stylesheetprivato.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.24/sb-1.0.1/sp-1.2.2/datatables.min.css" />

    <script src="../assets/js/jquery.min.js"></script>
    <script src="../assets/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/script.js"></script>

    <script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.24/sb-1.0.1/sp-1.2.2/datatables.min.js"></script>

</head>

<body onload="menuacomparsa();">
    <div class="d-flex" id="wrapper">
        <div class="bg-light border-right" id="sidebar-wrapper">
            <div class="sidebar-heading">Infoservice </div>
            <div class="list-group list-group-flush" id="sidebar">
                <script>
                    sidebar(["TicketAperti", "ticket"], "admin")
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
                                C </a>
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
            <div class="container-fluid">
                <h1 class="mt-4">I ticket Aperti</h1>
                <br>
                <script>
                    tabellaprivata()
                </script>
                <table id="search" style="width: 100%">
                    <thead>
                        <tr>

                            <th class="datepicker"><strong>data apertura</strong></td>
                            <th class="datepicker"><strong>oggetto</strong></td>
                            <th><strong>settore</strong></td>
                            <th><strong>descrizione</strong></td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>2021-04-19 08:12:24</td>
                            <td>pc rotto</td>
                            <td>Assistenza hardware a dispositivi informatici</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>2021-04-16 15:00:09</td>
                            <td>pc rotto</td>
                            <td>Assistenza hardware a dispositivi informatici</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>2021-04-12 08:16:18</td>
                            <td>Test 11</td>
                            <td>Personalizzazione di software</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>2021-04-10 14:51:33</td>
                            <td>Test 9</td>
                            <td>Configurazione di server e relativi servizi</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>2020-12-26 01:47:06</td>
                            <td></td>
                            <td>Assistenza software e sviluppo di nuove applicazioni</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>2020-12-13 16:51:41</td>
                            <td></td>
                            <td>Assistenza software e sviluppo di nuove applicazioni</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>2020-12-13 06:51:13</td>
                            <td></td>
                            <td>Assistenza software e sviluppo di nuove applicazioni</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>2020-10-27 17:32:39</td>
                            <td></td>
                            <td>Configurazione di server e relativi servizi</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>2020-06-19 13:32:45</td>
                            <td></td>
                            <td>Assistenza software e sviluppo di nuove applicazioni</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>2020-05-23 06:33:26</td>
                            <td></td>
                            <td>Configurazione di server e relativi servizi</td>
                            <td></td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th><strong>data apertura</strong></td>
                            <th><strong>oggetto</strong></td>
                            <th><strong>settore</strong></td>
                            <th><strong>descrizione</strong></td>
                        </tr>
                    </tfoot>
                </table>
                <p>pagina 1 su 4 pagine</p>
                <ul id="navlist">
                    <li><a href="?pageno=1">First</a></li>
                    <li class="disabled">
                        <a href="#">Prev</a>
                    </li>
                    <li class="">
                        <a href="?pageno=2">Next</a>
                    </li>
                    <li><a href="?pageno=4">Last</a></li>
                </ul>
                <br>
            </div>
</body>

</html>