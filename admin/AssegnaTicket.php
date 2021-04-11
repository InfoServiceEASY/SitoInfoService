<?php session_start();
include('../dal.php');
$title = 'Lista interventi dipendente';
$conn = DataConnect();
include '../template/privatepage_params.php';
$sql = "SELECT t.id, t.dataapertura,t.descrizione,t.oggetto,t.tipologia,s.nome  FROM ticket t
inner join settore s on s.id=t.fk_settore
where t.id=?";
$sth = $conn->prepare($sql);
$sth->bind_param('i', $_GET["id"]);
$sth->execute();
$result = $sth->get_result();
if ($result->num_rows > 0) $row = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") 
{
    $conn = DataConnect();
    $query = "INSERT INTO report (datainizio,fk_dipendente,fk_ticket) VALUES (NOW(),(select fk_utenza from dipendente where id=?),?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ii', $_POST['dipendenti'],$row['id']);
    if ($stmt->execute() === true) {}
}
$conn->close();
?>

<form method="POST">
    <div class="form-group">
        <label><?php echo $row['id'] . " aperto il " . $row['dataapertura'] ?></label>
    </div>
    <div class="form-group">
        <label><strong>Oggetto</strong></label>
        <p><?php echo $row["oggetto"] ?></p>

    </div>
    <div class="form-group">
        <label><strong>Tipologia</strong></label>
        <p><?php echo $row["tipologia"] ?></p>

    </div>
    <div class="form-group">
        <label><strong>Settore</strong></label>
        <p><?php echo $row["nome"] ?></p>

    </div>
    <div class="form-group">
        <label><strong>Descrizione</strong></label>
        <p><?php echo $row["descrizione"] ?></p>
    </div>
    <div class="form-group">
        <select class="custom-select"name='scelta' onchange=reload(this.form,<?php echo $_GET["id"] ?>)>
            
            <?php if(isset($_GET["scelta"])) {
                if($_GET["scelta"]==1)echo " <option  value=''>Scelta dipendenti</option><option selected value='1'>del settore</option><option value='2'>Tutti</option>"; 
                else if($_GET["scelta"]==2) echo " <option  value=''>Scelta dipendenti</option><option  value='1'>del settore</option><option selected value='2'>Tutti</option>";
                else{echo " <option selected value=''>Scelta dipendenti</option><option value='1'>del settore</option><option  value='2'>Tutti</option>";}
                echo "</select></div>";
            }else{echo " <option selected value=''>Scelta dipendenti</option><option value='1'>del settore</option><option  value='2'>Tutti</option></select></div>";}
              
        if (isset($_GET["scelta"])) {
            $variabile = $_GET["scelta"];
            $conn = DataConnect();
            if ($variabile == 1) {
                $sql = "SELECT dipendente.id, concat(nome, ' ', cognome) as fullname  FROM k113bann4ponykr2.dipendente
                inner join lavora on dipendente.fk_utenza=lavora.fk_dipendente
                where lavora.fk_settore=(select id from settore where settore.nome= ? )";
                $sth = $conn->prepare($sql);
               $sth->bind_param('s', $row["nome"]);
            } else {
                $sql = "SELECT id, concat(nome, ' ', cognome) as fullname  FROM k113bann4ponykr2.dipendente";
                $sth = $conn->prepare($sql);
            }
            $sth->execute();
            $result = $sth->get_result();
            $dropdown = "<div class='form-group'><select class='custom-select' name='dipendenti'><option value=''>Select one</option>";
            if ($result->num_rows > 0) {

                while ($row = $result->fetch_assoc()) {
                    $dropdown .= "<option value=" . $row['id'] . ">" . $row['fullname'] . "</option>";
                }
                echo $dropdown . "</select>    </div>";
            }
        }
        ?>

    <div style="float: right;">
    <button class="btn btn-primary" type="submit">Cancella</button>
    <button id="btnShowModal" class="btn btn-primary" type="submit">Conferma</button><!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-sm">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Ticket assegnato con successo</h5>
            <button id="btnClose" type="button" class="btn-close" data-mdb-dismiss="modal" aria-label="Close"></button>
          </div>

        </div>
      </div>
      <script type="text/javascript">
        $(document).ready(function() {
          $("#btnShowModal").click(function() {
            $("#exampleModal").modal("show");
          });
          $("#btnClose").click(function() {
            $("#exampleModal").modal("toggle");
          });
        });
      </script>

</form>
<style>

.custom-select {
  /*position: relative;*/
  font-family: Arial;

}
</style>
</div>
</body>

</html>