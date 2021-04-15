<?php

error_reporting(E_ERROR | E_WARNING | E_PARSE);

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

function Session()
{
    if (!isset($_SESSION['login'])) {
        header('location:../index.php');
        exit();
    }
}

function Login($usr, $pass)
{
    $conn = DataConnect();
    $stmt = $conn->prepare('SELECT * FROM utenza WHERE (email=? OR username=?) AND status=true');
    $stmt->bind_param('ss', $usr, $usr);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($pass, $row['password'])) {
            $_SESSION['login'] = $usr;
            $_SESSION['utente'] = $row['username'];
            if ($row['IsAdmin'] && $row["IsDipendente"]) {
                $_SESSION['member'] = "admin";
                header("location:../private/DashBoard.php");
            } else if (!$row["IsDipendente"]) {
                $_SESSION['member'] = 'cliente';
                header("location:../cliente/dashboard.php");
            } else {
                $_SESSION['member'] = 'dipendente';
                header("location:../private/DashBoard.php");
            }
            $conn->close();
            exit();
        } else {
            $conn->close();
            return 'Password non corrispondente';
        }
    } else {
        $conn->close();
        return 'Username o password non corrispondenti';
    }
}

function GetIDGivenUsername()
{
    $conn = DataConnect();
    $stmt = $conn->prepare('SELECT id FROM utenza WHERE username=?');
    $stmt->bind_param('s', $_SESSION['utente']);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    $conn->close();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['id'];
    } else
        return 'Errore';
}

function GetNameGivenID()
{
    $conn = DataConnect();
    $stmt = $conn->prepare('SELECT nome FROM cliente INNER JOIN utenza ON cliente.fk_utenza = utenza.id AND utenza.username=?');
    $stmt->bind_param('s', $_SESSION['utente']);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    $conn->close();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['nome'];
    } else
        return 'Errore';
}

function GetSectors()
{
    $conn = DataConnect();
    $template = '';
    $stmt = $conn->prepare('SELECT nome FROM settore');
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    $conn->close();
    if ($result->num_rows > 0) {
        foreach ($result as $r) {
            $template .= '<option>' . $r['nome'] . '</option>';
        }
        return $template;
    } else {
        return 'Errore';
    }
}

function Register($firstname, $lastname, $username, $phone, $email, $password)
{
    $conn = DataConnect();
    $stmt = $conn->prepare('SELECT * FROM utenza WHERE username=?');
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $resultU = $stmt->get_result();
    $stmt->close();
    $stmt = $conn->prepare('SELECT * FROM utenza WHERE email=?');
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $resultE = $stmt->get_result();
    $stmt->close();
    $conn->close();
    if ($resultU->num_rows > 0)
        return 'Username già utilizzato';
    else if ($resultE->num_rows > 0)
        return 'Email già utilizzata';
    else {
        $stmt = $conn->prepare('INSERT INTO utenza (username,password,email) VALUES (?,?,?)');
        $stmt->bind_param('sss', $username, password_hash($password, PASSWORD_DEFAULT), $email);
        if ($stmt->execute() === true) {
            $stmt->close();
            $conn->close();
            $conn = DataConnect();
            $stmt = $conn->prepare('INSERT INTO cliente (nome,cognome,cellulare,fk_utenza) VALUES (?,?,?,(SELECT MAX(id) FROM utenza))');
            $stmt->bind_param('sss', $firstname, $lastname, $phone);
            if ($stmt->execute() === true) {
                $stmt->close();
                $conn->close();
                return "<script>window.sendEmail('$email','$username')</script>" .
                    "<div>Ti sei registrato e l'e-mail di attivazione è stata inviata alla tua casella di posta. Fare clic sul collegamento di attivazione per attivare il proprio account.</div><br>";
            } else {
                $stmt->close();
                $conn->close();
                return 'C\'è stato un problema riprova più tardi';
            }
        } else {
            $stmt->close();
            $conn->close();
            return 'C\'è stato un problema riprova più tardi';
        }
    }
}

function UpdateProfile($nome, $cognome, $cellulare, $username, $email)
{
    $conn = DataConnect();
    $id = GetIDGivenUsername();
    $stmt = $conn->prepare('UPDATE cliente SET nome=?,cognome=?,cellulare=? WHERE fk_utenza=?');
    $stmt->bind_param('sssi', $nome, $cognome, $cellulare, $id);
    if ($stmt->execute() === true) {
        $stmt->close();
        $stmt = $conn->prepare('UPDATE utenza SET username=?,email=? WHERE id=?');
        $stmt->bind_param('ssi', $username, $email, $id);
        if ($stmt->execute() === true) {
            $stmt->close();
            $conn->close();
        } else {
            $stmt->close();
            $conn->close();
            return 'C\'è stato un problema riprova più tardi';
        }
    } else {
        $stmt->close();
        $conn->close();
        return 'C\'è stato un problema riprova più tardi';
    }
}

function Contact($firstname, $lastname, $phone, $email, $description)
{
    $conn = DataConnect();
    $stmt = $conn->prepare('INSERT INTO contatto (nome,cognome,cellulare,email,descrizione) VALUES (?,?,?,?,?)');
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
    $id = GetIDGivenUsername();
    $stmt = $conn->prepare('INSERT INTO ticket (oggetto,tipologia,descrizione,dataapertura,fk_cliente,fk_settore) VALUES (?,?,?,Now(),?,(SELECT id FROM settore WHERE nome=?))');
    $stmt->bind_param('sssis', $oggetto, $tipologia, $descrizione, $id, $settore);
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
        } else {
            $stmt->close();
            $conn->close();
            return 'Report convalidato correttamente.';
        }
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
    $id = GetIDGivenUsername();
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
    $openedreport = '<div class="row justify-content-center">';
    $closedreport = '<div class="row justify-content-center">';
    $refusedreport = '<div class="row justify-content-center">';
    $contopen = 0;
    $contclose = 0;
    $contrefused = 0;
    $stmt = $conn->prepare('SELECT t.id,t.oggetto,t.tipologia,t.descrizione,t.dataapertura,r.attività,r.isconvalidato,r.isrisolto,r.commento FROM ticket t INNER JOIN report r ON t.id = r.fk_ticket WHERE t.fk_cliente=?');
    $id = GetIDGivenUsername();
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    $conn->close();
    if ($result->num_rows > 0) {
        foreach ($result as $r) {
            if (is_null($r['isconvalidato']) && $r['attività'] != null) {
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
            } else if ($r['isconvalidato'] == 1 && $r['isrisolto'] == 1) {
                $contclose++;
                $closedreport .= '<div class="col-md-4 feature-box">' .
                    '<h4>' . $r['oggetto'] . '</h4>' .
                    '<p>' . $r['tipologia'] . '</p>' .
                    '<p>' . $r['descrizione'] . '</p>' .
                    '<p>' . $r['attività'] . '</p>' .
                    '<p>' . $r['commento'] . '</p>' .
                    '</div>';
            } else if ($r['isconvalidato'] == 0 || $r['isrisolto'] == 0) {
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
    if ($contopen == 0) {
        $openedreport .= '<h5>Non hai report da convalidare.</h5>';
    }
    if ($contclose == 0) {
        $closedreport .= '<h5>Non hai report già convalidati.</h5>';
    }
    if ($contrefused == 0) {
        $refusedreport .= '<h5>Non hai report in attesa.</h5>';
    }
    return array($openedreport .= '</div>', $closedreport .= '</div>', $refusedreport .= '</div>');
}

function ShowProfile()
{
    $conn = DataConnect();
    $template = '<div class="getting-started-info">';
    $stmt = $conn->prepare('SELECT c.nome,c.cognome,c.cellulare,u.username,u.email FROM cliente c INNER JOIN utenza u ON c.fk_utenza = u.id AND u.id=?');
    $id = GetIDGivenUsername();
    $stmt->bind_param('i', $id);
    if ($stmt->execute() === true) {
        $result = $stmt->get_result();
        $result = $result->fetch_assoc();
        $stmt->close();
        $conn->close();
        $template .= '<form action="" style="border-radius: 25px" method="POST">' . '<div class="form-group"><label for="email">Nome</label><input class="form-control item field" name="nome" type="text" value="' . $result['nome'] . '" disabled></div>' .
            '<div class="form-group"><label for="email">Cognome</label><input   class="form-control item field" name="cognome" type="text" value="' . $result['cognome'] . '" disabled></div>' .
            '<div class="form-group"><label for="email">Cellulare</label><input id="field" class="form-control item field" name="cellulare" type="text" value="' . $result['cellulare'] . '" disabled></div>' .
            '<div class="form-group"><label for="email">Username</label><input class="form-control item field " name="username" type="text" value="' . $result['username'] . '" disabled></div>' .
            '<div class="form-group"><label for="email">Email</label><input class="form-control item field" name="email" type="email" value="' . $result['email'] . '" disabled></div>' .
            '<label><input type="checkbox" id="action" onclick="myFunction()"> Abilita modifica</label>' .
            '<button class="btn btn-primary btn-block" type="submit" name="send">Conferma</button>' .
            '</form>' . '</div>';
        return array($result['nome'], $result['cognome'], $template);
    } else {
        $stmt->close();
        $conn->close();
        return 'C\'è stato un problema, riprova più tardi.';
    }
}
