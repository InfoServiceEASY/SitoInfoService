<?php session_start();
include('../../dal.php');
$title = 'Lista interventi dipendente';
$conn = DataConnect();
include '../../template/privatepage_params.php';
$sql = "SELECT t.id, t.dataapertura,t.descrizione,t.oggetto,t.tipologia,s.nome  FROM ticket t
inner join settore s on s.id=t.fk_settore
where t.id=?";
$sth = $conn->prepare($sql);
$sth->bind_param('i', $_GET["id"]);
$sth->execute();
$result = $sth->get_result();
if ($result->num_rows > 0) $row = $result->fetch_assoc();

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
        <select name='scelta' onchange=reload(this.form,<?php echo $_GET["id"] ?>)>
            <option  value=''>Scelta dipendenti</option>
            <?php if(isset($_GET["scelta"])) {
                if($_GET["scelta"]==1)echo " <option selected value='1'>del settore</option><option selected value='2'>Tutti</option></select>"; 
                }else echo " <option value='1'>del settore</option><option selected value='2'>Tutti</option></select>";
                
        if (isset($_GET["scelta"])) {
            $variabile = $_GET["scelta"];
            $conn = DataConnect();
            if ($variabile == '1') {
                $sql = "SELECT dipendente.id, concat(nome, ' ', cognome) as fullname FROM k113bann4ponykr2.dipendente
                inner join lavora on dipendente.fk_utenza=lavora.fk_dipendente
                where lavora.fk_settore=(select id from settore where settore.nome= ? )";
                $sth = $conn->prepare($sql);
               $sth->bind_param('s', $row["nome"]);
            } else {
                $sql = "SELECT id, concat(nome, ' ', cognome) as fullname FROM k113bann4ponykr2.dipendente";
                $sth = $conn->prepare($sql);
            }
            $sth->execute();
            $result = $sth->get_result();
            $dropdown = "<select name='dipendenti'><option value=''>Select one</option>";
            if ($result->num_rows > 0) {

                while ($row = $result->fetch_assoc()) {
                    $dropdown .= "<option value=" . $row['id'] . ">" . $row['fullname'] . "</option>";
                }
                echo $dropdown . "</select>";
            }
        }
        ?>
    </div>
</form>

</div>
</body>

</html>