<?php
session_start();
$title = 'interventi dipendente'; 
include_once '../dal.php';
include_once '../template/privatepage_params.php';
Session();
?>
<h1 class="mt-4">I ticket Aperti</h1>
<br>
<?php
echo ShownewTickets();
?>
<br>
</div>
</body>
</html>