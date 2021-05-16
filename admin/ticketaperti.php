<?php
session_start();
$title = 'Ticket aperti';
include_once '../dal.php';
include_once '../template/privatepage_params.php';
Session();
?>

<h1 class="mt-4">I ticket Aperti</h1>
<br>
<script>
    tabellaprivata()
</script>
<table id="search" style="width: 100%">
    <thead>
        <tr>
            <th><strong>data apertura</strong></td>
            <th><strong>settore</strong></td>
            <th><strong>oggetto</strong></td>
            <th>
                </td>
        </tr>
    </thead>
    <tbody>
        <?php
          
 $no_of_records_per_page = 10;

        $total_pages_sql = "SELECT count( DISTINCT t.id) as count FROM  ticket t INNER JOIN settore s ON s.id=t.fk_settore LEFT join report on report.fk_ticket=t.id where t.isaperto=1 and isassegnato=0 and (isrisolto= 0 or isrisolto is null) ";
        $total_pages = PagineTotali($total_pages_sql, $no_of_records_per_page);      
 if (isset($_GET['pageno'])) {
            if (is_int((int)$_GET['pageno'])&&(int)$_GET['pageno']>0 && (int)$_GET['pageno']<=$total_pages )
                $pageno = $_GET['pageno'];
	else{
            $pageno = 1;
        }

        } else {
            $pageno = 1;
        }
     
        $offset = ($pageno - 1) * $no_of_records_per_page;
        $sql = "SELECT DISTINCT t.id,t.dataapertura,t.descrizione,t.oggetto,t.tipologia,s.nome, report.isrisolto FROM  ticket t
         INNER JOIN settore s ON s.id=t.fk_settore LEFT join report on report.fk_ticket=t.id where t.isaperto=1 and isassegnato=0 and (isrisolto= 0 or isrisolto is null) LIMIT $offset, $no_of_records_per_page";
        $result = Tabella($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                if (!is_null($row['isrisolto']) && $row['isrisolto']==0 ) {
                    $style = "unicodue";
                } else {
                    $style = "unico";
                }
        ?>
                <tr>
                    <td><?php echo $row["dataapertura"]; ?></td>
                    <td><?php echo $row["nome"];  ?></td>
                    <td><?php echo $row['oggetto'] ?></td>
                    <?php echo '<td><button id="' . $style . '" onclick="location.href=' . "'assegnaticket.php?id=" . $row['id'] . "'" . '"' . ">assegna</button></td>" ?>
                </tr>
            <?php }
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
            <th></td>
        </tr>
    </tfoot>
</table>
<?php Paginazione($pageno, $total_pages); ?>

<br>
</div>
</body>

</html>