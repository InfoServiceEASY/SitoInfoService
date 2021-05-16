<?php
session_start();
$title = 'Interventi dipendente';
include_once '../dal.php';
include_once '../template/privatepage_params.php';
Session();
?>

<h1 class="mt-4">I report</h1>
<br>
<script>
    tabellaprivata()
</script>

<?php
$conn = DataConnect();
$total_rows = 0;
$total_pages = 1;
$pageno = isset($_GET['pageno']) ? $_GET['pageno'] : 1;
$aperto = isset($_GET["aperto"]) ? ($_GET['aperto'] == 1 ? true : false) : null;
$num_aperto = $aperto?"1":"0";
if (ReportOfthis($_GET['id'])) {
    if (!is_null($aperto)) {
        $no_of_records_per_page = 10;
        $offset = ($pageno - 1) * $no_of_records_per_page;
        $total_pages_sql = "SELECT r.fk_dipendente, COUNT(*) AS TotalRows FROM 
        report r INNER JOIN ticket t on r.fk_ticket = t.id WHERE r.fk_ticket = ? and r.fk_dipendente = ? GROUP BY r.fk_dipendente";
        $stmt = $conn->prepare($total_pages_sql);
        $stmt->bind_param('ii', $_GET['id'], GetUser()[0]);
        $stmt->execute();
        $result = $stmt->get_result();
        if (mysqli_fetch_array($result)['fk_dipendente'] != null) {
            $total_rows = mysqli_fetch_array($result)["TotalRows"];
            $total_pages = floor($total_rows / $no_of_records_per_page) + 1;
        }
        if (!isset($_GET['Cancella'])) {
            $sql = "SELECT r.id AS Id, r.datainizio AS DataInizio, r.datafine AS DataFine, r.isrisolto AS Risolto,t.oggetto AS Nome,t.descrizione AS Oggetto, r.attività AS Attività, r.commento, t.isaperto
        FROM ticket t INNER JOIN report r ON t.id = r.fk_ticket
        where t.id=? and r.attività is not null LIMIT $offset, $no_of_records_per_page";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('i', $_GET['id']);
            $stmt->execute();
            $data = $stmt->get_result();
            ?><table id="search" style="width: 100%">
            <thead>
            <th><strong> Id </strong></td>
            <th><strong> Oggetto </strong></td>
            <th><strong> DataInizio/DataFine </strong></td>
            <th><strong> Risolto </strong></td>
            <th><strong> Attività </strong></td>
            <th><strong> modifica</strong></td>
            </thead>
            <tbody><?php
            if ($data != null) {
                if($data->num_rows > 0){
                while ($row = $data->fetch_assoc()) {
                ?>
                    <tr>
                    <td><?php echo $row["Id"]; ?></td>
                    <td><?php echo $row["Nome"]. ": </br>" . $row['Oggetto'] ?></td>
                    <td><?php echo "DataInizio: </br>" . $row["DataInizio"]. "</br> DataFine: </br>" . $row["DataFine"]?></td>
                    <td><?php echo strval($row["Risolto"]) == "1" ? "Sì" : "No"; ?></td>
                    <td><?php echo $row["Attività"];  ?></td> 
                <?php
                    if (($aperto) && IsMine($row["Id"]))
                        echo '<td><button id="unico" onclick="location.href=' . "'writereport.php?id=" . $row['Id'] . "'" . '"' . ">Modifica Report</button>" .
                            '<button id="unico" onclick="location.href=' . "'interventi.php?id=" . $row['Id'] . "&Cancella=yes" . "'" . '"' . ">Cancella Report</button></td>";
                    else echo '<td> NON MODIFICABILE </td>';
                ?>
                </tr>
                <?php    
                }
            }

            } else {
                ?>
                <tr>
                    <td colspan="5">No results found.</td>
                </tr>
        <?php  }
            ?></tbody>
            <tfoot>
            <th><strong> Id </strong></td>
            <th><strong> Oggetto </strong></td>
            <th><strong> DataInizio/DataFine </strong></td>
            <th><strong> Risolto </strong></td>
            <th><strong> Attività </strong></td>
            <th><strong> modifica</strong></td>
            </tfoot>
            </table><?php
        } else {
            $stmt = $conn->prepare('UPDATE report set datafine=null,durata=null,attività=null,isrisolto=null where id=? and isconvalidato is null');
            $stmt->bind_param('i',  $_GET['ReportId']);
            $stmt->execute();
            echo ("<script LANGUAGE='JavaScript'>
        window.alert('eliminato con successo');
        window.location.href='Ticketlist.php';
        </script>");
        }
        echo '<div  class="contiene">';
        Paginazione_dipendente($pageno, $total_pages, $num_aperto, strval($_GET['id']), "interventi");
        ?>
        </div>
        <br>
        </div>
        </body>
        </html>
<?php
    } else {
        echo "<h1>Errore nella specifica dello stato dei ticket.</h1>";
    }
} else {
    echo "<h1>Il ticket relativo agli interventi non è stato mai assegnato a questo dipendente.</h1>";
}
?>