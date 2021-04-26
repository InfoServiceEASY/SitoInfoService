<?php
session_start();
$title = 'interventi dipendente';
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

        $total_pages_sql = "SELECT COUNT(*) as cont FROM  ticket t
            INNER JOIN settore s on s.id=t.fk_settore  where isassegnato=0
            AND t.isaperto=1";
        $total_pages = PagineTotali($total_pages_sql,$no_of_records_per_page);

        $offset = ($pageno - 1) * $no_of_records_per_page;
        $sql = "SELECT t.id,t.dataapertura,t.descrizione,t.oggetto,t.tipologia,s.nome FROM  ticket t
            INNER JOIN settore s on s.id=t.fk_settore  where isassegnato=0
            AND t.isaperto=1 ORDER BY t.dataapertura DESC LIMIT $offset, $no_of_records_per_page";
        $result = Tabella($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
        ?>
                <tr>
                    <td><?php echo $row["dataapertura"]; ?></td>
                    <td><?php echo $row["nome"];  ?></td>
                    <td><?php echo $row['oggetto'] ?></td>
                    <?php echo '<td><button id="unico" onclick="location.href=' . "'AssegnaTicket.php?id=" . $row['id'] . "'" . '"' . ">edit</button></td>" ?>
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
            <th><strong>modifica</strong></td>
        </tr>
    </tfoot>
</table>
<?php Paginazione($pageno,$total_pages); ?>

<br>
</div>
</body>

</html>