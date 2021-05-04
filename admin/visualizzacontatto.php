<?php
session_start();
$title = 'commenti';
include_once '../dal.php';
include_once '../template/privatepage_params.php';
Session();

ShowCommenti();
?>
</div>
</body>
</html>