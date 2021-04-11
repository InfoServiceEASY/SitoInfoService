<?php
session_start();
include_once("../../dal.php");
Session();

$title = "Scrivi nuovo report";
include '../../template/privatepage_params.php';

$id = $_GET['Id'];
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $descrizione = $_POST["Descrizione"];
    $durata=$_POST["Tempo"];
    $isrisolto = $_POST["IsRisolto"] == "Sì"? 1:0;
    $fk_utente = GetIDGivenUsername();
    InsertReport($durata,$descrizione, $isrisolto, $id, $fk_utente);
  }

  function InsertReport($durata,$descrizione, $isrisolto, $fk_ticket, $fk_dipendente){
    //fk_ticket in report. n report 1 ticket.
    $conn = DataConnect();
    $sql = "update report set datafine=?,durata=?,attività=?,isrisolto=? where fk_ticket=? and fk_dipendente=? and isrisolto=null";
    //mettere ora attuale
    $thisdate = date('d/m/Y \a\l\l\e H:i:s');
    $sth = $conn -> prepare($sql);
    $sth ->bind_param('sssiii', $thisdate,$durata, $descrizione, $isrisolto,$fk_ticket, $fk_dipendente);
    $sth -> execute();
    echo "REPORT SCRITTO!";
  }
  ?>

    <h1 class="mt-4">Compila Report</h1>
      <br>
<form method="POST">
    <div class="form-group">
      <label for="exampleFormControlInput1">Intervento</label>
      <!--<input class="form-control" type="text" >-->
      <label style = "color:darkgrey;text-align:centre;content-align:centre" name = "Intervento" >Intervento n. <?php echo "$id"; ?></label> <!--Nome Intervento Da DB-->
    </div>
    <div class="form-group">
      <label for="Tempo">Tempo impiegato (ore)?</label>
      </br>
      <input type="time" id="Tempo" name="Tempo" required>

    <!--  <input  type="text"  required></input>-->
      </div>

    <div class="form-group">
      <label for="ProblemaRisolto">Problema risolto?</label>
      <select name = "IsRisolto" class="form-control" id="ProblemaRisolto" required>
        <option>--</option>
        <option>S&igrave;</option>
        <option>No</option>
      </select>
    </div>
    <div class="form-group">
        <label for="Descrizione">Descrizione</label>
        <textarea name = "Descrizione"class="form-control" id="Descrizione" rows="3" required></textarea>
      </div>
      <div style="float: right;">
        <button class="btn btn-primary" type="submit">Cancella</button>
        <button id="btnShowModal" class="btn btn-primary" type="submit">Conferma</button>
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
  </script>


</body>

</html>