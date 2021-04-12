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
            if ($row["IsAdmin"] && $row["IsDipendente"]) {
                $_SESSION["member"] = "admin";
                header("location:../private/DashBoard.php");
            }
            //else (!$row["IsDipendente"] ? $_SESSION["member"] = "cliente" : $_SESSION["member"] = "dipendente");
            else if (!$row["IsDipendente"]) {
                $_SESSION['member'] = 'cliente';
                header("location:../cliente/dashboard.php");
            } else {
                $_SESSION['member'] = 'dipendente';
                header("location:../private/DashBoard.php");
            }
            //header("location:../private/DashBoard.php");
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

function GetNameGivenID()
{
    $conn = DataConnect();
    $query = "SELECT nome FROM cliente INNER JOIN utenza ON cliente.fk_utenza = utenza.id AND utenza.username=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $_SESSION['utente']);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['nome'];
    } else {
        $stmt->close();
        return "error";
    }
}

function GetSectors()
{
    $conn = DataConnect();
    $stmt = $conn->prepare('SELECT nome FROM settore');
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    $conn->close();
    if ($result->num_rows > 0) {
        $template = '';
        foreach ($result as $x) {
            $template .= '<option>' . $x['nome'] . '</option>';
        }
        return $template;
    } else {
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
            $errore .= "<div>Ti sei registrato e l'e-mail di attivazione è stata inviata alla tua casella di posta. Fare clic sul collegamento di attivazione per attivare il proprio account.</div><br>";
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
    $conn = DataConnect();
    $stmt = $conn->prepare("INSERT INTO contatto (nome,cognome,cellulare,email,descrizione) VALUES (?,?,?,?,?)");
    $stmt->bind_param('sssss', $firstname, $lastname, $phone, $email, $description);
    if ($stmt->execute() === true) {
        $stmt->close();
        $conn->close();
        header("location:index.php");
        exit();
    } else {
        $stmt->close();
        $conn->close();
        return 'C\'è stato un problema, riprova più tardi';
    }
}

function WriteTicket($oggetto, $tipologia, $settore, $descrizione)
{
    $conn = DataConnect();
    $id=GetIDGivenUsername();
    $data=date('Y-m-d H:i:s');
    $stmt = $conn->prepare('INSERT INTO ticket (oggetto,tipologia,descrizione,dataapertura,fk_cliente,fk_settore) VALUES (?,?,?,?,?,(SELECT id FROM settore WHERE nome=?))');
    $stmt->bind_param('ssssis', $oggetto, $tipologia, $descrizione,$data ,$id , $settore);
    if ($stmt->execute() === true) {
        $stmt->close();
        $conn->close();
        return 'Ticket creato con successo.';
    } else {
        $stmt->close();
        $conn->close();
        return 'C\'è stato un problema, riprova più tardi';
    }
}

function ConvalidTicket($choice, $comment, $id)
{
    $conn = DataConnect();
    $stmt = $conn->prepare('UPDATE report SET isrisolto=?,commento=?,isconvalidato=? WHERE fk_ticket=?');
    $stmt->bind_param('sssi', $choice, $comment, $choice, $id);
    if ($stmt->execute() === true) {
        $stmt->close();
        if ($choice == true) {
            $stmt = $conn->prepare('UPDATE ticket SET isaperto=? WHERE id=?');
            $cond = 0;
            $stmt->bind_param('ii', $cond, $id);
            if ($stmt->execute() === true) {
                $stmt->close();
                $conn->close();
                return 'Report convalidato correttamente.';
            } else {
                $stmt->close();
                $conn->close();
                return 'C\'è stato un problema, riprova più tardi.';
            }
        } else
            return 'Report convalidato correttamente.';
    } else {
        $stmt->close();
        $conn->close();
        return 'C\'è stato un problema, riprova più tardi.';
    }
}

function ShowTicket()
{
    $conn = DataConnect();
    $openedticket = '<div class="row justify-content-center">';
    $closedticket = '<div class="row justify-content-center">';
    $contopen = 0;
    $contclose = 0;
    $id=GetIDGivenUsername();
    $stmt = $conn->prepare('SELECT oggetto,tipologia,descrizione,dataapertura,isaperto FROM ticket WHERE fk_cliente=?');
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    $conn->close();
    foreach ($result as $r) {
        if ($r['isaperto'] == true) {
            $contopen++;
            $openedticket .= '<div class="col-md-4 feature-box">' .
                '<h4>' . $r['oggetto'] . '</h4>' .
                '<p>' . $r['tipologia'] . '</p>' .
                '<p>' . $r['descrizione'] . '</p>' .
                '<p>' . $r['dataapertura'] . '</p>' .
                '</div>';
        } else if ($r['isaperto'] == false) {
            $contclose++;
            $closedticket .= '<div class="col-md-4 feature-box">' .
                '<h4>' . $r['oggetto'] . '</h4>' .
                '<p>' . $r['tipologia'] . '</p>' .
                '<p>' . $r['descrizione'] . '</p>' .
                '<p>' . $r['dataapertura'] . '</p>' .
                '</div>';
        }
    }
    if ($contopen == 0) {
        $openedticket .= '<h5>Non hai ancora ticket aperti.</h5>';
    }
    if ($contclose == 0) {
        $closedticket .= '<h5>Non hai ticket chiusi in precedenza.</h5>';
    }
    return array($openedticket .= '</div>', $closedticket .= '</div>');
}

function ShowReport()
{
    $conn = DataConnect();
    $waitingreport = '<div class="row justify-content-center">';
    $openedreport = '<div class="row justify-content-center">';
    $closedreport = '<div class="row justify-content-center">';
    $refusedreport = '<div class="row justify-content-center">';
    $contwait = 0;
    $contopen = 0;
    $contclose = 0;
    $contrefused = 0;
    $stmt = $conn->prepare('SELECT t.id,t.oggetto,t.tipologia,t.descrizione,t.dataapertura,r.attività,r.isconvalidato,r.isrisolto,r.commento FROM ticket t LEFT JOIN report r ON t.id = r.fk_ticket WHERE t.fk_cliente=?');
    $id=GetIDGivenUsername();
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    $conn->close();
    if ($result->num_rows > 0) {
        foreach ($result as $r) {
            if (is_null($r['isconvalidato']) && is_null($r['attività'])) {
                $contwait++;
                $waitingreport .= '<div class="col-md-4 feature-box">' .
                    '<h4>' . $r['oggetto'] . '</h4>' .
                    '<p>' . $r['tipologia'] . '</p>' .
                    '<p>' . $r['descrizione'] . '</p>' .
                    '<p>' . $r['dataapertura'] . '</p>' .
                    '</div>';
            } else if (is_null($r['isconvalidato']) && $r['attività'] != null) {
                $contopen++;
                $openedreport .= '<div class="col-md-4 feature-box">' .
                    '<h4>Ticket numero: ' . $r['id'] . '</h4>' .
                    '<h4>' . $r['oggetto'] . '</h4>' .
                    '<p>' . $r['tipologia'] . '</p>' .
                    '<p>' . $r['descrizione'] . '</p>' .
                    '<p>' . $r['attività'] . '</p>' .
                    '<form action="" method="POST"' .
                    '<div class="form-group">
                    <label for="Commento">Commento</label>
                    <textarea class="form-control" name="commento" required></textarea>' .
                    '<input type="hidden" name="id" value="' . $r['id'] . '" />' .
                    '<button class="btn btn-primary btn-block" type="submit" name="yes">Sono d\'accordo</button>' .
                    '<button class="btn btn-primary btn-block" type="submit" name="no">Non sono d\'accordo</button>' .
                    '</div>' .
                    '</form>';
            } else if ($r['isconvalidato'] == true && $r['isrisolto'] == true) {
                $contclose++;
                $closedreport .= '<div class="col-md-4 feature-box">' .
                    '<h4>' . $r['oggetto'] . '</h4>' .
                    '<p>' . $r['tipologia'] . '</p>' .
                    '<p>' . $r['descrizione'] . '</p>' .
                    '<p>' . $r['attività'] . '</p>' .
                    '<p>' . $r['commento'] . '</p>' .
                    '</div>';
            } else if ($r['isconvalidato'] == false || $r['isrisolto'] == false) {
                $contrefused++;
                $refusedreport .= '<div class="col-md-4 feature-box">' .
                    '<h4>' . $r['oggetto'] . '</h4>' .
                    '<p>' . $r['tipologia'] . '</p>' .
                    '<p>' . $r['descrizione'] . '</p>' .
                    '<p>' . $r['attività'] . '</p>' .
                    '<p>' . $r['commento'] . '</p>' .
                    '</div>';
            }
        }
    }
    if ($contwait == 0) {
        $waitingreport .= '<h5>Non hai ancora ticket aperti.</h5>';
    }
    if ($contopen == 0) {
        $openedreport .= '<h5>Non hai report da convalidare.</h5>';
    }
    if ($contclose == 0) {
        $closedreport .= '<h5>Non hai report già convalidati.</h5>';
    }
    if ($contrefused == 0) {
        $refusedreport .= '<h5>Non hai report in attesa.</h5>';
    }
    return array($waitingreport .= '</div>', $openedreport .= '</div>', $closedreport .= '</div>', $refusedreport .= '</div>');
}
