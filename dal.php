<?php
function DataConnect()
{
    $servername = "lmc8ixkebgaq22lo.chr7pe7iynqr.eu-west-1.rds.amazonaws.com";
    $username = "htgt3cv7fwksdcw4";
    $password = "lh21vdy7t1yjk7bk";
    $dbname = "k113bann4ponykr2";
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error)
        die("Connection failed: " . $conn->connect_error);
    return $conn;
}

function Login($usr, $pass)
{
    $errore = "";
    $usr2 = $usr;
    $conn = DataConnect();
    $query = "SELECT * FROM utenza WHERE (email=? OR username=?) and status='active'";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ss', $usr, $usr2);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($pass, $row['password'])) {
            $_SESSION['login'] = $usr;
            $_SESSION['utente'] = $row['username'];
            if ($row["IsAdmin"] && $row["IsDipendente"]) $_SESSION["member"] = "helpdesk";
            else (!$row["IsDipendente"] ? $_SESSION["member"] = "cliente" : $_SESSION["member"] = "dipendente");
            header("location:../admin/DashBoard.php");
            exit();
        } else
            $errore = "Password non corrispondente";
    } else
        $errore = "Username o password non corrispondenti";
    $conn->close();
    return $errore;
}

function GetIDGivenUsername($username){
    $conn = DataConnect();
   
    $query = "SELECT member.id FROM ".$_SESSION["member"]." as member inner join utenza u on u.id=member.fk_utenza WHERE u.username=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row["id"];
}
else return "error";}

function Register($firstname, $lastname, $username, $phone, $email, $password)
{
    $errore = "";
    $conn = DataConnect();
    $stato = "disabled";
    $query = "INSERT INTO utenza (username,password,email,status) VALUES (?,?,?,?)";
    $stmt = $conn->prepare($query);
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt->bind_param('ssss', $username, $hash, $email, $stato);

    if ($stmt->execute() === true) {
        $conn->close();
        $conn = DataConnect();
        $query = "INSERT INTO cliente (nome,cognome,cellulare,fk_utenza) VALUES (?,?,?,(SELECT MAX(id) FROM utenza))";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('sss', $firstname, $lastname, $phone);
        if ($stmt->execute() === true) {
            $errore .= "<script>window.sendEmail('$email','$username')</script>";
            $errore .= "<div>You have registered and the activation mail is sent to your email. Click the activation link to activate you account.</div><br>";
        } else
            $errore .= $conn->error;
    } else
        $errore = "primo if";
    $conn->close();
    return $errore;
}

function Session()
{
    if (!isset($_SESSION['login'])) {
        header('location:../index.php');
        exit();
    }
}

function Contact($firstname, $lastname, $phone, $email, $description)
{
    $errore = "";
    $conn = DataConnect();
    $query = "INSERT INTO contatto (nome,cognome,cellulare,email,descrizione) VALUES (?,?,?,?,?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('sssss', $firstname, $lastname, $phone, $email, $description);
    if ($stmt->execute() === true) {
        header("location:index.php");
        exit();
    } else
        $errore = "C'è stato un problema, riprova più tardi";
    $conn->close();
    return $errore;
}

