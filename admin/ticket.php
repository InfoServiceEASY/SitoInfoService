<?php
session_start();
$title = 'Lista interventi dipendente'; 
include_once '../dal.php';
include_once '../template/privatepage_params.php';
Session();
?>
<h1 class="mt-4">I ticket assegnati</h1>
<br><br>
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
<?php
/*
$uno = 1;
$conn = DataConnect();
$stmt = $conn->prepare('SELECT t.id,t.dataapertura,t.descrizione,t.oggetto,t.tipologia,s.nome FROM ticket t
INNER JOIN settore s on s.id=t.fk_settore LEFT JOIN report r ON t.id =r.fk_ticket 
AND t.isaperto=? ORDER BY t.dataapertura DESC limit 12');
$stmt->bind_param('i', $uno);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();
if ($result->num_rows > 0) {
  for ($i = 0; $i < $result->num_rows; $i++) {
    $row = $result->fetch_assoc();
    (strlen($row['descrizione']) > 5 ? $descrizione = substr($row['descrizione'], 0, 20) . "..." : $descrizione = $row['descrizione']);
    $href = "AssegnaTicket.php?id=" . $row['id'];
    if ($i % 3 == 0) echo "<div class='containerone'>";
    $template = "
        <div class='contenitore'>
        <p>Intervento n." . $row['id'] . "</p><p> aperto il " . $row['dataapertura'] . "</p>
        <p><strong>tipologia</strong> " . $row['tipologia'] . "</p>
        <p><strong>settore</strong> " . $row['nome'] . "</p>
        <p> <strong>oggetto</strong> " . $row['oggetto'] . "</p>
        <p> <strong>descrizione</strong> " . $descrizione . "</p>
        <a href='$href'> assegna ticket</a>
        </div>";
    echo $template;
    if ($i % 3 == 2) echo " </div>";
  }
}*/
?>
</html>
