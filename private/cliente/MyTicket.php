<?php
session_start();
$title="My ticket";
include_once("../../dal.php");
include_once("../../template/privatepage_params.php");
ShowTicket();
?>

<br>
<form>
    <style>
        .containerone {
            display: flex;
            height: 300px;
        }

        div.container {
            flex: 1;
            border-radius: 25px;
            border: 2px solid white;
            margin-right: 10px;
            margin-top: 10px;
        }

        .container:hover {
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.8);
        }
    </style>
</form>
</div>
</body>


</html>