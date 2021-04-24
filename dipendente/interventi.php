<?php
session_start();
$title = 'interventi dipendente'; 
include_once '../dal.php';
include_once '../template/privatepage_params.php';
Session();

function TableBeginningPrint($nome_colonne){
    echo '<table id="search" style="width: 100%">';
        echo "<thead>";
        echo HeadRow($nome_colonne);
        echo "</thead>";
        echo '<tbody>'; 
}
function Table_content($conn, $query, $aperto){
    $conn = DataConnect();
  $id = GetUser()[0];
  $sth = $conn->prepare($query);
  $sth->bind_param('i', $id);
  $sth->execute();
  $data = $sth->get_result();
    if (  $res_data = mysqli_query($conn, $query)) {
        while ($row = mysqli_fetch_array($res_data)) {
    ?>
            <tr>
                <td><?php echo $row["dataapertura"]; ?></td>
                <td><?php echo $row["nome"];  ?></td>
                <td><?php echo $row['oggetto'] ?></td>
               <?php echo $aperto? 
                '<td><button id="unico" onclick="location.href='."'measures.php?id=".$row['id']."'".'"'.">Visualizza Precedenti Report</button></td>".
                '<td><button id="unico" onclick="location.href='."'writereport.php?id=".$row['id']."'".'"'.">Scrivi Report</button></td>"
               : '<td><button id="unico" onclick="location.href='."'measures.php?id=".$row['id']."'".'"'.">Visualizza Precedenti Report</button></td>";
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
function TableEndPrint($nome_colonne){
    echo "</tbody>
        <tfoot>";
        echo HeadRow($nome_colonne);
        echo "</tfoot>
        </table>";
}
?>
<h1 class="mt-4">I report</h1>
<br>
<script >tabellaprivata()</script>
 <table id="search" style="width: 100%">
        <thead>
            <tr>
                <th><strong>nome colonna</strong></td>
                <th><strong>nome colonna</strong></td>
		<th><strong>nome colonna</strong></td>
                <th><strong>nome colonna</strong></td>
            </tr>
        </thead>
        <tbody>
            <?php
            if (isset($_GET['pageno'])) {
                $pageno = $_GET['pageno'];
            } else {
                $pageno = 1;
            }
            $no_of_records_per_page = 10;
            $offset = ($pageno - 1) * $no_of_records_per_page;

            $conn =DataConnect();

            $total_pages_sql = "SELECT COUNT(*) ";// fai il count in modo che ti ritorni la somma delle righe
            $result = mysqli_query($conn, $total_pages_sql);
            $total_rows = mysqli_fetch_array($result)[0];
            $total_pages = ceil($total_rows / $no_of_records_per_page);
            $sql = "qui fai la sua select e ci lasci questo => LIMIT $offset, $no_of_records_per_page";
            if (  $res_data = mysqli_query($conn, $sql)) {
                while ($row = mysqli_fetch_array($res_data)) {
            ?>
                    <tr>
                        <td><?php echo $row["dataapertura"]; ?></td>
                        <td><?php echo $row["nome"];  ?></td>
                        <td><?php echo $row['oggetto'] ?></td>
//al bottone cambiaci location href in base alla tua  pagina di reindirizzamento
                       <?php echo '<td><button id="unico" onclick="location.href='."'AssegnaTicket.php?id=".$row['id']."'".'"'.">edit</button></td>"?> 
                    </tr>
                <?php }
                mysqli_close($conn);
            } else {
                ?>
                <tr>
                    <td colspan="5">No results found.</td>
                <?php
            }
                ?>
        </tbody>
        <tfoot>
            <tr>
                <th><strong>nome colonna</strong></td>
                <th><strong>nome colonna</strong></td>
		<th><strong>nome colonna</strong></td>
                <th><strong>nome colonna</strong></td>
            </tr>
        </tfoot>
    </table> 
    <div  class="contiene"> 
   <?php echo "<p class='inlineLeft'  >pagina ".$pageno." su ".$total_pages." pagine</p>";?>
    <ul  class='inlineRight'  id="navlist">
        <li ><a href="?pageno=1">First</a></li>
        <li  class="<?php if ($pageno <= 1) {
                        echo 'disabled';
                    } ?>">
            <a href="<?php if ($pageno <= 1) {
                            echo '#';
                        } else {
                            echo "?pageno=" . ($pageno - 1);
                        } ?>">Prev</a>
        </li>
        <li  class="<?php if ($pageno >= $total_pages) {
                        echo 'disabled';
                    } ?>">
            <a href="<?php if ($pageno >= $total_pages) {
                            echo '#';
                        } else {
                            echo "?pageno=" . ($pageno + 1);
                        } ?>">Next</a>
        </li>
        <li ><a href="?pageno=<?php echo $total_pages; ?>">Last</a></li>
    </ul>
    </div>
<br>
</div>
</body>
</html>