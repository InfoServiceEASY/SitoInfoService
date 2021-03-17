<?php
function DataConnect()
{
    $servername = "127.0.0.1";
    $username = "root";
    $password = "root";
    $dbname = "infoservice";
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error)
        die("Connection failed: " . $conn->connect_error);
    return $conn;
}

function Login($usr, $pass)
{
    $errore = "";
    $usr2=$usr;
    $conn = DataConnect();
    $query = "SELECT * FROM `utenza` WHERE email=? or  username=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ss', $usr,$usr2);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($pass, $row['password'])) {
            $_SESSION['login'] = $usr;
            $_SESSION["utente"]=$row['username'];
            if ($row["IsAdmin"] && $row["IsDipendente"]) $_SESSION["member"] = "helpdesk";
            else (!$row["IsDipendente"]? $_SESSION["member"] = "customer": $_SESSION["member"] = "employee");
            header("location:../admin/Dashboard.php");
            exit();
        } else
            $errore = "password sbagliata";
    } else
        $errore = "username o password sbagliati";
    $conn->close();
    return $errore;
}
