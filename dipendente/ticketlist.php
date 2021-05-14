<?php
session_start();
$title = 'Interventi';
include_once '../dal.php';
include_once '../template/privatepage_params.php';
$aperto = isset($_GET['aperto']) ? ($_GET['aperto'] == 1 ? true : false) : null;
$pageno = isset($_GET['pageno']) ? $_GET['pageno'] : 1;
$conn = DataConnect();
if (!is_null($aperto)) {
    ?> 
    <h1 style="padding-bottom: 50px;"> I tuoi interventi <?php echo $aperto? "aperti" : "chiusi"; ?> </h1>

    <?php
    Table($conn, $pageno, $aperto);
} else { ?>
    <h1 style="font-size: 300%"> Visualizza Interventi</h1>
    </br>
    </br>
    <a href="?pageno=1&aperto=0" style="text-decoration: none; color:white;">
    <button class="btn-primary" style="float:left; width: 50%; height:1000%; font-size: 200%; padding:10px; padding-bottom:100px;padding-top: 100px;">
            Visualizza i tuoi interventi chiusi
        </button>
    </a>
    <a href="?pageno=1&aperto=1" style="text-decoration: none; color:white;">
    <button class="btn-primary" style="float:left; width: 50%; height:1000%; font-size: 200%; padding:10px; padding-bottom: 100px;padding-top: 100px;">    
        Visualizza i tuoi interventi aperti
    </button>
    </a>
<?php
}
?>

<script>
    tabellaprivata()
</script>

<?php
function HeadRow($nome_colonne)
{
    $template = '<tr>';
    foreach ($nome_colonne as $colonna) {
        $template .= "<th><strong> $colonna </strong></td>";
    }
    $template .= '</tr>';
    return $template;
}

function Table_content($conn, $pageno, $total_pages, $query, $aperto)
{
    ?><table id="search" style="width: 100%">
    <thead>
    <th><strong> Id </strong></td>
    <th><strong> DataApertura </strong></td>
    <th><strong> Nome </strong></td>
    <th><strong> Oggetto </strong></td>
    <th><strong> Attività </strong></td>
    <th><strong> modifica</td>
    </thead>
    <tbody><?php
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', GetUser()[0]);
    $stmt->execute();
    $resultE = $stmt->get_result();
    if ($resultE->num_rows > 0) {
        while ($row = $resultE->fetch_assoc()) {
?>
            <tr>
                <td><?php echo $row["id"]; ?></td>
                <td><?php echo $row["DataApertura"]; ?></td>
                <td><?php echo $row["Nome"];  ?></td>
                <td><?php echo $row['Oggetto'] ?></td>
                <td><?php echo $row["Attività"];  ?></td>
                <?php
                echo $aperto ?
                    '<td><button id="unico" onclick="location.href=' . "'interventi.php?pageno=1&id=" . $row['id'] . "&aperto=1" . "'" . '"' . ">Visualizza Precedenti Report</button>" .
                    '<button id="unico" onclick="location.href=' . "'writereport.php?id=" . $row['id'] . "'" . '"' . ">Scrivi Report</button></td>"
                    : '<td><button id="unico" onclick="location.href=' . "'interventi.php?pageno=1&id=" . $row['id'] . "&aperto=0" . "'" . '"' . ">Visualizza Precedenti Report</button></td>";
                ?>
            </tr>
        <?php }
        $conn->close();
    } else {
        ?>
        <tr>
            <td colspan="5">No results found.</td>
        </tr>
    <?php  }?>
    </tbody>
     <tfoot>
     <th><strong> Id </strong></td>
     <th><strong> DataApertura </strong></td>
     <th><strong> Nome </strong></td>
     <th><strong> Oggetto </strong></td>
     <th><strong> Attività </strong></td>
     <th><strong> modifica </strong></td>
    </tfoot>
     </table><?php
    echo '<div  class="contiene">';
        Paginazione_dipendente($pageno, $total_pages,$aperto?"1":"0", null, "ticketlist"); ?>
    </div>
<?php
}

function Table($conn, $pageno, $aperto)
{
    $no_of_records_per_page = 10;
    $offset = ($pageno - 1) * $no_of_records_per_page;
    $total_pages_sql =
        "SELECT t.isaperto, r.fk_dipendente, COUNT(*) AS TotalRows
    FROM ticket t INNER JOIN report r ON t.id = r.fk_ticket
	WHERE" .  ($aperto == 1 ? "(t.isaperto = 1 AND r.fk_dipendente = ? AND r.isconvalidato IS NULL)" : "(r.fk_dipendente = ? AND t.isaperto = 0)") .
        "GROUP BY t.isaperto, r.fk_dipendente"; // fai il count in modo che ti ritorni la somma delle righe
    $stmt = $conn->prepare($total_pages_sql);
    $stmt->bind_param('i', GetUser()[0]);
    $stmt->execute();
    $result = $stmt->get_result();
    $total_rows = mysqli_fetch_array($result)["TotalRows"];
    $total_pages = floor($total_rows / $no_of_records_per_page) + 1;
    $query = $aperto ?
        "SELECT t.id, t.dataapertura AS DataApertura,t.oggetto AS Nome,t.descrizione AS Oggetto, r.attività AS Attività FROM ticket t INNER JOIN report r ON t.id = r.fk_ticket
    WHERE t.isaperto = 1 AND r.fk_dipendente =? and r.isconvalidato is null LIMIT $offset, $no_of_records_per_page"
        :
        "SELECT t.id, t.dataapertura AS DataApertura,t.oggetto AS Nome,t.descrizione AS Oggetto, r.attività AS Attività FROM ticket t INNER JOIN report r ON t.id = r.fk_ticket
        AND r.fk_dipendente =? and  t.isaperto = 0 LIMIT $offset, $no_of_records_per_page";
    //si comincia a stampare
    //aperti o chiusi
    Table_content($conn, $pageno, $total_pages, $query, $aperto);
}

?>
<br>
</div>
</body>

</html>