<?php
include_once("../template/privatepage_params.php");
include_once("../dal.php");
$conn = DataConnect();
$stmt = $conn->prepare('SELECT oggetto,tipologia,descrizione,dataapertura FROM ticket WHERE fk_utenza=?');
$stmt->bind_param('i', GetIDGivenUsername());
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    for ($x = 0; $x < count($row); $x++) {
        
    }
} else {
}
