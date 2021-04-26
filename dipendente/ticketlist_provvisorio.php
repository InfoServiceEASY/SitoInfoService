<?php
session_start();
include('../dal.php');
$title = 'Interventi dipendente'; // mettere titolo più corto
include_once '../dal.php';
include '../template/privatepage_params.php'; ?>
<?php
$aperto = isset($_GET["aperto"]) ? ($_GET["aperto"] == 1 ? true : false) : null;
$pageno = isset($_GET['pageno']) ? $_GET['pageno'] : 1;
$conn = DataConnect();
#region commenti
/*$query1 = "SELECT t.id, t.dataapertura,t.descrizione , r.attività FROM ticket t INNER JOIN report r ON t.id = r.fk_ticket
 WHERE t.isaperto = 1 AND r.fk_dipendente =? and r.isconvalidato is null "; //r.attività is null, ma se si sbaglia?
$query2 = "SELECT t.id, t.dataapertura,t.descrizione FROM ticket t INNER JOIN report r ON t.id = r.fk_ticket
 AND r.fk_dipendente =? and  t.isaperto = 0 ";
function prova($sql, $h1, $chiuso)
{
  $conn = DataConnect();
  $id = GetUser()[0];
  $sth = $conn->prepare($sql);
  $sth->bind_param('i', $id);
  $sth->execute();
  $data = $sth->get_result();

  $template = "<h1 class='mt-4'>" . $h1 . "</h1>";
  if ($data->num_rows > 0) {
    for ($i = 0; $i < $data->num_rows; $i++) {
      $row = $data->fetch_assoc();
      $href = "writereport.php?Id=" . $row['id'];
      $href2 = "measures.php?Id=" . $row['id'];
      if ($i % 3 == 0) {
        $template .= "<div class='containerone'>";
      }
      $template .= "        
    <div class='contenitore'>
    <p>Intervento n." . $row['id'] . " aperto il " . $row['dataapertura'] . "</p>
    <p> " . $row['descrizione'] . "</p>
    </br>
    <a href='$href2'> Visualizza report sull'attività</a>
    </br>";
    $template .= (is_null($row['attività']))? "<a href='$href'> Scrivi report sull'attività</a> </div>" :
    "</div>";
      if ($i % 3 == 2) $template .= " </div>";
    }
    if ($data->num_rows % 3 != 0) $template .= " </div>";
  } else
    $template .= "<p>Al momento non hai ticket assegnati</p>";
  $conn->close();
  echo $template;
}*/

//prova($query1, "Interventi aperti", false);
//prova($query2, "Interventi chiusi", true);
#endregion 
if (!is_null($aperto)) {
    $nome_colonne = array("Id", "Data Apertura", "Nome", "Oggetto", "modifica");
    $total_pages = Table($conn, $nome_colonne, $pageno, $aperto);
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
<?php } else { ?>
    <h1 style="font-size: 300%"> Visualizza Interventi</h1>
    </br>
    </br>
    <a href="?pageno=1&aperto=0" style="text-decoration: none; color:white;">
        <button class="btn-primary" style="float:center; width: 100%; height:100%; font-size: 200%">
            </br>
            </br>
            </br>
            Visualizza i tuoi interventi chiusi
            </br>
            </br>
            </br>
        </button>
    </a>
    </br>
    </br>
    </br>
    </br>
    <a href="?pageno=1&aperto=1" style="text-decoration: none; color:white;">
        <button class="btn-primary" style="float:center; width: 100%; height:100%; font-size: 200%">
            </br>
            </br>
            </br>
            Visualizza i tuoi interventi aperti
            </br>
            </br>
            </br>
        </button>
    </a>
    </br>
    </br>
    </br>
    </br>
    </br>
    </br>
    </br>
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
function TableBeginningPrint($nome_colonne)
{
    echo '<table id="search" style="width: 100%">';
    echo "<thead>";
    echo HeadRow($nome_colonne);
    echo "</thead>";
    echo '<tbody>';
}
function Table_content($conn, $query, $aperto)
{
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
                <?php echo $aperto ?
                    '<td><button id="unico" onclick="location.href=' . "'measures.php?id=" . $row['id'] . "'" . '"' . ">Visualizza Precedenti Report</button>" .
                    '<button id="unico" onclick="location.href=' . "'writereport.php?id=" . $row['id'] . "'" . '"' . ">Scrivi Report</button></td>"
                    : '<td><button id="unico" onclick="location.href=' . "'measures.php?id=" . $row['id'] . "'" . '"' . ">Visualizza Precedenti Report</button></td>";
                ?>
            </tr>
        <?php }
        mysqli_close($conn);
    } else {
        ?>
        <tr>
            <td colspan="5">No results found.</td>
    <?php
    }
}
function TableEndPrint($nome_colonne)
{
    echo "</tbody>
        <tfoot>";
    echo HeadRow($nome_colonne);
    echo "</tfoot>
        </table>";
}
function Table($conn, $nome_colonne, $pageno, $aperto)
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
    //$result = mysqli_query($conn, $total_pages_sql);
    $total_rows = mysqli_fetch_array($result)["TotalRows"];
    $total_pages = floor($total_rows / $no_of_records_per_page) + 1;
    $query = null;
    if ($aperto) {
        $query = "SELECT t.id, t.dataapertura AS DataApertura,t.descrizione AS Oggetto, r.attività AS Nome FROM ticket t INNER JOIN report r ON t.id = r.fk_ticket
        WHERE t.isaperto = 1 AND r.fk_dipendente =? and r.isconvalidato is null LIMIT $offset, $no_of_records_per_page"; //r.attività is null, ma se si sbaglia?
    } else {
        $query = "SELECT t.id, t.dataapertura AS DataApertura,t.descrizione AS Oggetto, r.attività AS Nome FROM ticket t INNER JOIN report r ON t.id = r.fk_ticket
            AND r.fk_dipendente =? and  t.isaperto = 0 LIMIT $offset, $no_of_records_per_page";
    }
    //si comincia a stampare
    //aperti (o chiusi)
    TableBeginningPrint($nome_colonne);
    Table_content($conn, $query, $aperto);
    TableEndPrint($nome_colonne);
    //chiusi (vecchia versione)
    /*echo "</br>";
    TableBeginningPrint($nome_colonne);
    Table_content($conn, $query2, false);
    TableEndPrint($nome_colonne);*/
    return $total_pages;
}
#endregion Methods
    ?>
    <br>
    </div>
    </body>

    </html>