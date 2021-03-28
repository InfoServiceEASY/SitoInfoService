<?php
session_start();
include_once("../dal.php");
Session();
$id = $_GET['Id'];
function UserId($conn){
  $sql = "SELECT id FROM utenza WHERE username = ?";
  $sth = $conn->prepare($sql);
  $sth->bind_param('s', $_SESSION['utente']);
  $sth->execute();
  return $sth -> get_result(); 
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
  <!--  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js" integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous"></script>
  -->  

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Scrivi nuovo report</title>

   <!--Bootstrap core CSS -->
  <link href="../assets/css/bootstrap.min.css" rel="stylesheet">

  <!-- Custom styles for this template -->
  <link href="../assets/css/stylesheetprivato.css" rel="stylesheet">
</head>

<body>
<?php include '../template/privatepage_params.php';
include_once('../dal.php');
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //forse meglio sfruttare name della form con $_POST e passare insertreport parameters da là?
  }
  function InsertReport($descrizione, $isrisolto_string, $durata, $fk_ticket){
    //fk_ticket in report. n report 1 ticket.
    $conn = DataConnect();
    $sql = "INSERT INTO report(ora,attività,isrisolto,fk_dipendente,fk_ticket,isconvalidato,commento) VALUES(?,?,?,?,?,?,?,?)";
    $fk_utente = UserId(DataConnect());
    $date = date('d/m/Y \a\l\l\e H:i:s');
    $statement = $conn -> prepare($sql);
    $statement -> bind_param('ssiiiis', $date, $descrizione,$isrisolto_string=='Sì'? 1:0,$fk_utente, $fk_ticket, 0, "");
    $statement -> execute();
  }
  ?>

    <h1 class="mt-4">Compila Report</h1>
      <br>
    <form method="POST">
  <div class="form-group">
    <label for="exampleFormControlInput1">Intervento</label>
    <!--<input class="form-control" type="text" >-->
    <label id="InterventoId" style = "color:darkgrey;text-align:centre;content-align:centre">Intervento n. <?php echo "$id"; ?></label> <!--Nome Intervento Da DB-->
  </div>
  <div class="form-group">
    <label for="exampleFormControlSelect1">Tempo impiegato (ore)?</label>
    </br>
    <input type="text" id="OreId"></input>
    </div>
  
  <div class="form-group">
    <label for="IsRisoltoId">Problema risolto?</label>
    <select id="IsRisoltoId" class="form-control">
      <option>--</option>
      <option>S&igrave;</option>
      <option>No</option>
    </select>
  </div>
  <div class="form-group">
      <label for="exampleFormControlTextarea1">Descrizione</label>
      <textarea class="form-control" id="DescrizioneId" rows="3"></textarea>
    </div>
    <div style="float: right;">
      <button class="btn btn-primary" type="submit">Cancella</button>
      <button id="btnShowModal" type="button" class="btn btn-primary" type="submit" onclick=>Conferma</button>
</form>
        </div>
    </div>
    <!-- /#page-content-wrapper -->

  </div>
  <!-- /#wrapper -->

  <!-- Bootstrap core JavaScript -->
  <script src="../assets/js/jquery.min.js"></script>
  <script src="../assets/js/bootstrap.bundle.min.js"></script>

  <!-- Menu Toggle Script -->
  <script>
    $("#menu-toggle").click(function(e) {
      e.preventDefault();
      $("#wrapper").toggleClass("toggled");
    });
    function SendJson(){
      let id = <?php echo $id; ?>;
      let vars = ["Ore", "IsRisolto", "Descrizione","Intervento"].map((elemento) =>{
        return document.getElementById(elemento + "Id");
      });
      let data = [{
        "Ore": vars[0].innerHTML,
        "IsRisolto":vars[1].Childnodes[vars[1].selectedIndex].innerHTML,
        "Descrizione":vars[2].innerHTML,
        "FK_Ticket": vars[3].innerHTML.split('.')[1].trim()
      }];
      alert(data);
      <?php $data = "<script>document.writeln(data);</script>";?>
      <?php
        InsertReport($data['Descrizione'], $data['IsRisolto'], $data['Ore'], $data['FK_Ticket']);
      ?>
    }
  </script>


</body>

</html>