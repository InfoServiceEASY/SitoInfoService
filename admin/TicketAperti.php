<?php
session_start();
$title = 'Interventi dipendente'; 
include_once '../dal.php';
include_once '../template/privatepage_params.php';
Session();
?>
<h1 class="mt-4">I ticket Aperti</h1>
<br>
<script >tabellaprivata()</script>
 <table id="search" style="width: 100%">
        <thead>
            <tr>
                <th><strong>data apertura</strong></td>
                <th><strong>settore</strong></td>
                <th><strong>oggetto</strong></td>
                <th><strong>modifica</strong></td>
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

            $total_pages_sql = "SELECT COUNT(*) FROM  ticket t
            INNER JOIN settore s on s.id=t.fk_settore  where isassegnato=0
            AND t.isaperto=1";
            $result = mysqli_query($conn, $total_pages_sql);
            $total_rows = mysqli_fetch_array($result)[0];
            $total_pages = ceil($total_rows / $no_of_records_per_page);
            $sql = "SELECT t.id,t.dataapertura,t.descrizione,t.oggetto,t.tipologia,s.nome FROM  ticket t
            INNER JOIN settore s on s.id=t.fk_settore  where isassegnato=0
            AND t.isaperto=1 ORDER BY t.dataapertura DESC LIMIT $offset, $no_of_records_per_page";
            if (  $res_data = mysqli_query($conn, $sql)) {
                while ($row = mysqli_fetch_array($res_data)) {
            ?>
                    <tr>
                        <td><?php echo $row["dataapertura"]; ?></td>
                        <td><?php echo $row["nome"];  ?></td>
                        <td><?php echo $row['oggetto'] ?></td>
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
                <th><strong>data apertura</strong></td>
                <th><strong>settore</strong></td>
                <th><strong>oggetto</strong></td>
                <th><strong>modifica</strong></td>
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