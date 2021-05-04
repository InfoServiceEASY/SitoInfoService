<?php
session_start();
$title = 'commenti';
include_once '../dal.php';
include_once '../template/privatepage_params.php';
Session();
?>
<h1>Contatti senza risposta</h1>
<?php
ShowCommenti(0);
?>
<br>
<h1>Contatti con risposta</h1>
<?php
ShowCommenti(1);
?>
</div>
</body>
</html>