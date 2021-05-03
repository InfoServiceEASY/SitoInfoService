<?php
session_start();
include('../dal.php');
$title = 'Interventi dipendente'; // mettere titolo più corto
include_once '../dal.php';
include '../template/privatepage_params.php'; 

$aperto = isset($_GET["aperto"]) ? ($_GET["aperto"] == 1 ? true : false) : null;
$pageno = isset($_GET['pageno']) ? $_GET['pageno'] : 1;
$conn = DataConnect();

if (!is_null($aperto)) {
    Table($conn, $pageno, $aperto);
}else { ?>
    <h1 style="font-size: 300%"> Visualizza Interventi</h1>
    </br>
    </br>
    <a href="?pageno=1&aperto=0" style="text-decoration: none; color:white;">
        <button class="btn-primary" style="float:center; width: 100%; height:100%; font-size: 200%; padding:10px; padding-bottom:100px;padding-top: 100px;">
            Visualizza i tuoi interventi chiusi
        </button>
    </a>
    </br>
    </br>
    </br>
    <a href="?pageno=1&aperto=1" style="text-decoration: none; color:white;">
        <button class="btn-primary" style="float:center; width: 100%; height:100%; font-size: 200%; padding:10px; padding-bottom: 100px;padding-top: 100px;">
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
#region Methods
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
    echo '<table id="search" style="width: 100%">
    <thead>
    <th><strong> Id </strong></td>
    <th><strong> DataApertura </strong></td>
    <th><strong> Nome </strong></td>
    <th><strong> Oggetto </strong></td>
    <th><strong> Attività </strong></td>
    <th><strong> modifica</td>
    </thead>
    <tbody>';
    $id = GetUser()[0];
    $sth = $conn->prepare($query);
    $sth->bind_param('i', $id);
    $sth->execute();
    $resultE = $sth->get_result();
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
                    '<td><button id="unico" onclick="location.href=' . "'interventi.php?id=" . $row['id']. "&aperto=1" . "'" . '"' . ">Visualizza Precedenti Report</button>" .
                    '<button id="unico" onclick="location.href=' . "'writereport.php?id=" . $row['id'] . "'" . '"' . ">Scrivi Report</button></td>"
                    : '<td><button id="unico" onclick="location.href=' . "'interventi.php?id=" . $row['id'] . "&aperto=0". "'" . '"' . ">Visualizza Precedenti Report</button></td>";
                ?>
            </tr>
        <?php }
        mysqli_close($conn);
    } else {
        ?>
        <tr>
            <td colspan="5">No results found.</td>
        </tr>
        <?php  }
     echo '</tbody>
     <tfoot>
     <th><strong> Id </strong></td>
     <th><strong> DataApertura </strong></td>
     <th><strong> Nome </strong></td>
     <th><strong> Oggetto </strong></td>
     <th><strong> Attività </strong></td>
     <th><strong> modifica </strong></td>
     </tfoot>
     </table>';
     echo '<div  class="contiene">';
     //echo "<p class='inlineLeft'  >pagina " . strval($pageno) . " su " . strval($total_pages) . " pagine</p>";
     echo '<p style = "font-size: 200%; text-align:center;">';
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
<?php
}

function Table($conn, $pageno, $aperto)
{
    $no_of_records_per_page = 10;
    $offset = ($pageno - 1) * $no_of_records_per_page;
    $total_pages_sql =
        "SELECT t.isaperto, r.fk_dipendente, COUNT(*) AS TotalRows
    FROM ticket t INNER JOIN report r ON t.id = r.fk_ticket
	WHERE" .  ($aperto == 1 ? "(t.isaperto = 1 and r.fk_dipendente = ? and r.isconvalidato is null)" : "(r.fk_dipendente = ? and t.isaperto = 0)") .
        "GROUP BY t.isaperto, r.fk_dipendente"; // fai il count in modo che ti ritorni la somma delle righe
    $id = GetUser()[0];
    $sth = $conn->prepare($total_pages_sql);
    $sth->bind_param('i', $id);
    $sth->execute();
    $result = $sth->get_result();
    $total_rows = mysqli_fetch_array($result)["TotalRows"];
    $total_pages = floor($total_rows / $no_of_records_per_page) + 1;
    $query = $aperto?   
    "SELECT t.id, t.dataapertura AS DataApertura,t.oggetto AS Nome,t.descrizione AS Oggetto, r.attività AS Attività FROM ticket t INNER JOIN report r ON t.id = r.fk_ticket
    WHERE t.isaperto = 1 AND r.fk_dipendente =? and r.isconvalidato is null LIMIT $offset, $no_of_records_per_page"
    : 
    "SELECT t.id, t.dataapertura AS DataApertura,t.oggetto AS Nome,t.descrizione AS Oggetto, r.attività AS Attività FROM ticket t INNER JOIN report r ON t.id = r.fk_ticket
        AND r.fk_dipendente =? and  t.isaperto = 0 LIMIT $offset, $no_of_records_per_page";
    //si comincia a stampare
    //aperti o chiusi
    Table_content($conn, $pageno, $total_pages, $query, $aperto);
}
#endregion Methods
?>
<br>
</div>
</body>
</html>