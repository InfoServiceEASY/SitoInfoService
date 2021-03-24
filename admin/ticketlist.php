<?php
/*lista 
 $sql = "SELECT id, dataapertura,descrizione FROM ticket
    WHERE isaperto = 1 AND fk_dipendente = ?"
    $sth = $conn -> prepare($sql);
    $sth -> bindparam('?', IDByUser($_SESSION['utente'])); //SELECT id from dipendente WHERE username = parametro ($_SESSION['utente'])   
*/
session_start();
 include('../dal.php');
 $conn = DataConnect();
 $sql = "SELECT id, dataapertura,descrizione FROM ticket
 WHERE isaperto = 1 AND fk_dipendente = ?";
 $sth = $conn -> prepare($sql);
 mysqli_stmt_bind_param($sth, 'i', IDByUser($conn, $_SESSION['utente'])); //'?'
 //$sth -> bind_param(
 $data = $sth -> execute();
 $contents = PreparaTesti($data);
 PrintSolutions($contents[0], $contents[1]);

function IDByUser($conn, $user){
    $sql = "SELECT id FROM utenza WHERE username = ?";
    $sth = $conn -> prepare($sql);
    $sth -> bind_param('s', $user);
 }
 function PreparaTesti($data){
    $titoli = array(); 
    $testi = array();
    foreach($data as $d)
    {
        array_push($titoli, "Intervento n." . $d['id'] . " aperto il " . $d['dataapertura']);
        array_push($testi, $d['descrizione']);
    }
    return array($titoli, $testi);
 }
 function PrintSolutions($titoli, $testi){
    for($i = 0; $i<count($titoli); $i++){
        if($i % 3 == 0)echo "<div class='containerone'>";
        $template = "
        <div class='container'>
        <a><p>$titoli[$i]</p></a>
        <a>$testi[$i]</a>
        </div>";
        echo $template;
        if($i % 3 == 2) echo " </div>";
    }
   #region codice commentato 
  /*
  <div class='container'>
        <a><p></p></a>
    <a>Tuo problema con Soluzione 1</a>
    </div>
    <div  class='container magenta'>
        <a><p>Soluzione 1</p></a>
    <a>Tuo problema con Soluzione 1</a>
    </div>
  */
  #endregion codice commentato
 }

?>
<?php include '../template/privatepage_params.php'; ?>
<h1 class="mt-4">I tuoi interventi</h1>
<br>
<form>
  
<style>

.containerone {
  display: flex;
  height: 300px;
}

div.container {
  flex: 1;
  border-radius: 25px;
    border: 2px solid white;
    margin-right: 10px;
    margin-top: 10px;
}
.container:hover {
  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.8);
}

</style>
</form>

<!-- /#page-content-wrapper -->

</div>
</body>

</html>