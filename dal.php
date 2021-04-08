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
            header("location:../private/DashBoard.php");
            exit();
        } else
            $errore = "Password non corrispondente";
    } else
        $errore = "Username o password non corrispondenti";
    $conn->close();
    return $errore;
}

function GetIDGivenUsername()
{
    $conn = DataConnect();
    $query = "SELECT id FROM utenza WHERE username=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $_SESSION['utente']);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row["id"];
    } else {
        $stmt->close();
        return "error";
    }
}

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

function WriteTicket($oggetto, $tipologia, $settore, $descrizione)
{
    $errore = '';
    $conn = DataConnect();
    $stmt = $conn->prepare('SELECT id FROM settore WHERE nome=?');
    $stmt->bind_param('s', $settore);
    $errore = '2';
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $stmt->close();
        $row = $result->fetch_assoc();
        $settore = $row['id'];
        $stmt = $conn->prepare('INSERT INTO ticket (oggetto,tipologia,descrizione,dataapertura,fk_cliente,fk_settore) VALUES (?,?,?,?,?,?)');
        $stmt->bind_param('ssssii', $oggetto, $tipologia, $descrizione, date('Y-m-d H:i:s'), GetIDGivenUsername(), $settore);
        $errore = '3';
        if ($stmt->execute() === true) {
            $stmt->close();
            $conn->close();
            header("location:DashBoard.php");
            exit();
            return $errore;
        } else {
            $stmt->close();
            $conn->close();
            $errore = "C'è stato un problema, riprova più tardi";
            return $errore;
        }
    } else {
        $error = "C'è stato un problema, riprova più tardi";
        return $error;
    }
}

function ShowTicket()
{
    $conn = DataConnect();
    $oggetto = array();
    $tipologia = array();
    $descrizione = array();
    $dataapertura = array();
    $stmt = $conn->prepare('SELECT oggetto,tipologia,descrizione,dataapertura FROM ticket WHERE fk_utenza=?');
    $stmt->bind_param('i', GetIDGivenUsername());
    $stmt->execute();
    $result = $stmt->get_result();
    foreach ($result as $r) {
        array_push($oggetto, $r['oggetto']);
        array_push($tipologia, 'Tipo di intervento' . $r['tipologia']);
        array_push($descrizione, 'Descrizione del problema' . $r['descrizione']);
        array_push($dataapertura, 'Ticket aperto il ' . $r['dataapertura']);
    }
    $stmt->close();
    $conn->close();
    for ($i = 0; $i < count($oggetto); $i++) {
        if ($i == 0) echo "<div class='containerone'>";
        $template = "
            <div class='container'>
            <p>$oggetto[$i]</p>
            </br>
            <a>$tipologia[$i]</a>
            </br>
            <p>$descrizione[$i]</p>
            </br>
            <p>$dataapertura[$i]</p>
            </div>";
        echo $template;
        if (count($oggetto) == $i) echo "</div>";
    }
}
