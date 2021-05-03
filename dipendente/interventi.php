<?php
session_start();
$title = 'interventi dipendente'; 
include_once '../dal.php';
include_once '../template/privatepage_params.php';
Session();
?>
<h1 class="mt-4">I report</h1>
<br>
<script >tabellaprivata()</script>
<?php
$conn = DataConnect();
$id = $_GET['Id'];
$total_rows = 0;
$total_pages = 1;
$pageno = isset($_GET['pageno'])? $_GET['pageno']: 1;
$aperto = isset($_GET["aperto"]) ? ($_GET["aperto"] == 1 ? true : false) : null;
if(Ticket_Assigned_ToMe($conn, $id)){
    if(!is_null($aperto)){
        $no_of_records_per_page = 10;
        $offset = ($pageno - 1) * $no_of_records_per_page;
        //it takes the number of the records you must show
        $total_pages_sql = "SELECT r.fk_dipendente, COUNT(*) AS TotalRows FROM 
        report r inner join ticket t on r.fk_ticket = t.id WHERE r.fk_ticket = ? and r.fk_dipendente = ? ORDER BY r.fk_dipendente"; 
        $sth = $conn->prepare($total_pages_sql);
        $sth->bind_param('ii',$id, GetUser()[0]);
        $sth->execute();
        $result = $sth->get_result();

        if(mysqli_fetch_array($result)["fk_dipendente"] != null){
            $total_rows = mysqli_fetch_array($result)["TotalRows"];
            $total_pages = floor($total_rows / $no_of_records_per_page) + 1;
        }

        if (!isset($_GET["Cancella"])) {    
        $sql="SELECT r.id AS Id, r.datainizio AS DataInizio, r.datafine AS DataFine, r.isrisolto AS Risolto,t.oggetto AS Nome,t.descrizione AS Oggetto, r.attività AS Attività, r.commento, t.isaperto
        FROM ticket t INNER JOIN report r ON t.id = r.fk_ticket
        where t.id=? and r.attività is not null LIMIT $offset, $no_of_records_per_page";
        $sth = $conn->prepare($sql);
        $sth->bind_param('i',$id);
        $sth->execute();
        $data = $sth->get_result();
        echo '<table id="search" style="width: 100%">
            <thead>
            <th><strong> Id </strong></td>
            <th><strong> DataInizio </strong></td>
            <th><strong> DataFine </strong></td>
            <th><strong> Risolto </strong></td>
            <th><strong> Nome </strong></td>
            <th><strong> Oggetto </strong></td>
            <th><strong> Attività </strong></td>
            <th><strong> modifica</td>
            </thead>
            <tbody>';
        if ($data != null) {
            while($data->num_rows > 0){
                //record
                ?>
                <td><?php echo $row["Id"]; ?></td>
                <td><?php echo $row["DataInizio"]; ?></td>
                <td><?php echo $row["DataFine"]; ?></td>
                <td><?php echo strval($row["Risolto"]) == "1"? "Sì":"No"; ?></td>
                <td><?php echo $row["Nome"];  ?></td>
                <td><?php echo $row['Oggetto'] ?></td>
                <td><?php echo $row["Attività"];  ?></td>
                <?php
                if(($aperto) && IsMine($conn, $row["Id"]))
                        echo'<td><button id="unico" onclick="location.href='."'writereport.php?ReportId=".$row['Id']."'".'"'.">Modifica Report</button></td>".
                        '<td><button id="unico" onclick="location.href='."'interventi.php?ReportId=".$row['Id']."&Cancella=yes"."'".'"'.">Cancella Report</button></td>";
                    else echo '<td></td>';
            }}else {
                ?>
                <tr>
                    <td colspan="5">No results found.</td>
                </tr>
                <?php  }
            echo '</tbody>
            <tfoot>
            <th><strong> Id </strong></td>
            <th><strong> DataInizio </strong></td>
            <th><strong> DataFine </strong></td>
            <th><strong> Risolto </strong></td>
            <th><strong> Nome </strong></td>
            <th><strong> Oggetto </strong></td>
            <th><strong> Attività </strong></td>
            <th><strong> modifica</td>
            </tfoot>
            </table>';
        }
        else {
        $id_report = $_GET['ReportId'];
        $sql = "UPDATE report set datafine=null,durata=null,attività=null,isrisolto=null where id=? and isconvalidato is null";
        $sth = $conn->prepare($sql);
        $sth->bind_param('i',  $id_report );
        $sth->execute();
        echo ("<script LANGUAGE='JavaScript'>
        window.alert('eliminato con successo');
        window.location.href='Ticketlist.php';
        </script>");
        }
        echo '<div  class="contiene">
            <p style = "font-size: 200%; text-align:center;">';
            $num_aperto = strval($aperto ? 1 : 0);
            for ($i = 1; $i <= $total_pages; $i++)
                echo "<a href = '?pageno=$i&aperto=$num_aperto'> $i </a>";
            echo '</p>';
            echo '<p style = "font-size: 200%; text-align:center;">';
            echo "<a href='?pageno=1&aperto=$num_aperto'>   &lt;&lt;   </a>";
            ?>
            <a href="<?php if ($pageno <= 1) {
                            echo '#';
                        } else {
                            echo "?pageno=" . ($pageno - 1) . "&aperto=" . $num_aperto;
                        } ?>"> &lt; </a>
            <a> <?php echo $pageno; ?> </a>
            <a href="<?php if ($pageno >= $total_pages) {
                            echo '#';
                        } else {
                            echo "?pageno=" . ($pageno + 1) . "&aperto=" . $num_aperto;
                        } ?>"> &gt; </a>
            <a href="?pageno=<?php echo $total_pages . "&aperto=" . $num_aperto; ?>"> &gt;&gt; </a>
            </p>
            </div>
        <br>
        </div>
        </body>
        </html>
        <?php 
    }
    else{
        echo "<h1>Errore nella specifica dello stato dei ticket.</h1>";
    }
}
else{
    "<h1>Ticket non assegnato al dipendente.</h1>";
}
?>