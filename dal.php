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

function Login($username, $password)
{
    $conn = DataConnect();
    $error = '';
    $stmt = $conn->prepare('SELECT * FROM utenza WHERE (email=? OR username=?) AND status=1');
    $stmt->bind_param('ss', $username, $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    $conn->close();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['login'] = $username;
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
        } else
            $error = 'Password non corrispondente';
    } else
        $error = 'Username o password non corrispondenti';
    return $error;
}

function GetUser()
{
    $conn = DataConnect();
    $stmt = $conn->prepare('SELECT u.id,c.nome FROM ' . $_SESSION["member"] . ' c INNER JOIN utenza u ON c.fk_utenza = u.id AND u.username=?');
    $stmt->bind_param('s', $_SESSION['utente']);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    $conn->close();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return array($row['id'], $row['nome']);
    } else
        return 'Errore';
}
function GetTicketRowgivenId($id)
{
    $conn = DataConnect();
    $stmt = $conn->prepare('SELECT t.id, t.dataapertura,t.descrizione,t.oggetto,t.tipologia,s.nome FROM ticket t INNER JOIN settore s ON s.id=t.fk_settore AND t.id=?');
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
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
    } else
        $template = 'Errore';
    return $template;
}

function Register($firstname, $lastname, $username, $phone, $email, $password)
{
    $esito = '';
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
        $esito = 'Username già utilizzato';
    else if ($resultE->num_rows > 0)
        $esito = 'Email già utilizzata';
    else {
        $conn = DataConnect();
        $stmt = $conn->prepare('INSERT INTO utenza (username,password,email) VALUES (?,?,?)');
        $stmt->bind_param('sss', $username, password_hash($password, PASSWORD_DEFAULT), $email);
        if ($stmt->execute()) {
            $stmt->close();
            $conn->close();
            $conn = DataConnect();
            $stmt = $conn->prepare('INSERT INTO cliente (nome,cognome,cellulare,fk_utenza) VALUES (?,?,?,(SELECT MAX(id) FROM utenza))');
            $stmt->bind_param('sss', $firstname, $lastname, $phone);
            if ($stmt->execute()) {
                $esito = "<script>window.sendEmail('$email','$username')</script>" .
                    "<div>Ti sei registrato e l'e-mail di attivazione è stata inviata alla tua casella di posta. Fare clic sul collegamento di attivazione per attivare il proprio account.</div><br>";
            } else
                $esito = 'C\'è stato un problema riprova più tardi';
        } else
            $esito = 'C\'è stato un problema riprova più tardi';

        $stmt->close();
        $conn->close();
        return $esito;
    }
}

function UpdateProfile($nome, $cognome, $cellulare, $username, $email)
{
    $conn = DataConnect();
    $esito = '';
    $stmt = $conn->prepare('UPDATE cliente SET nome=?,cognome=?,cellulare=? WHERE fk_utenza=?');
    $stmt->bind_param('sssi', $nome, $cognome, $cellulare, GetUser()[0]);
    if ($stmt->execute() === true) {
        $stmt->close();
        $stmt = $conn->prepare('UPDATE utenza SET username=?,email=? WHERE id=?');
        $stmt->bind_param('ssi', $username, $email, $id);
        if ($stmt->execute())
            $esito = '';
        else
            $esito = 'C\'è stato un problema riprova più tardi';
    } else
        $esito = 'C\'è stato un problema riprova più tardi';

    $stmt->close();
    $conn->close();
    return $esito;
}

function Contact($firstname, $lastname, $phone, $email, $description)
{
    $conn = DataConnect();
    $stmt = $conn->prepare('INSERT INTO contatto (nome,cognome,cellulare,email,descrizione) VALUES (?,?,?,?,?)');
    $stmt->bind_param('sssss', $firstname, $lastname, $phone, $email, $description);
    if ($stmt->execute()) {
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
    $esito = '';
    $stmt = $conn->prepare('INSERT INTO ticket (oggetto,tipologia,descrizione,dataapertura,fk_cliente,fk_settore) VALUES (?,?,?,Now(),?,(SELECT id FROM settore WHERE nome=?))');
    $stmt->bind_param('sssis', $oggetto, $tipologia, $descrizione, GetUser()[0], $settore);
    if ($stmt->execute())
        $esito = 'Ticket creato con successo.';
    else
        $esito = 'C\'è stato un problema, riprova più tardi';

    $stmt->close();
    $conn->close();
    return $esito;
}

function ConvalidTicket($choice, $comment, $id)
{
    $conn = DataConnect();
    $esito = '';
    $stmt = $conn->prepare('UPDATE report SET isrisolto=?,commento=?,isconvalidato=? WHERE fk_ticket=?');
    $stmt->bind_param('sssi', $choice, $comment, $choice, $id);
    if ($stmt->execute()) {
        $stmt->close();
        if ($choice == true) {
            $stmt = $conn->prepare('UPDATE ticket SET isaperto=? WHERE id=?');
            $cond = 0;
            $stmt->bind_param('ii', $cond, $id);
            if ($stmt->execute())
                $esito = 'Report convalidato correttamente.';
            else
                $esito = 'C\'è stato un problema, riprova più tardi.';
        } else
            $esito = 'Report convalidato correttamente.';
    } else
        $esito = 'C\'è stato un problema, riprova più tardi.';

    $stmt->close();
    $conn->close();
    return $esito;
}

function ShowTicket()
{
    $conn = DataConnect();
    $openedticket = '<div class="row justify-content-center">';
    $closedticket = '<div class="row justify-content-center">';
    $contopen = 0;
    $contclose = 0;
    $stmt = $conn->prepare('SELECT id,oggetto,tipologia,descrizione,dataapertura,isaperto FROM ticket WHERE fk_cliente=?');
    $stmt->bind_param('i', GetUser()[0]);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    $conn->close();
    foreach ($result as $r) {
        (strlen($r['descrizione']) > 20 ? $descrizione = substr($r['descrizione'], 0, 20) . "..." : $descrizione = $r['descrizione']);
        if ($r['isaperto'] == 1) {
            $contopen++;
            $openedticket .= '<div class="col-md-4 feature-box">' .
                '<label style="font-weight: bold;">Ticket numero:<h5>' . $r['id'] . '</h5></label></br>' .
                '<label style="font-weight: bold;">Oggetto:<h5>' . $r['oggetto'] . '</h5></label></br>' .
                '<label style="font-weight: bold;">Tipologia:<h5>' . $r['tipologia'] . '</h5></label></br>' .
                '<label style="font-weight: bold;">Descrizione:<h5>' . $descrizione . '</h5></label></br>' .
                '<label style="font-weight: bold;">Data apertura:<h5>' . $r['dataapertura'] . '</h5></label></br>' .
                '<input type="hidden" name="id" value="' . $r['id'] . '"/>' .
                '<a href="details.php?id=' . $r['id'] . '&page=ticket">Visualizza più dettagli</a>' .
                '</div>';
        } else if ($r['isaperto'] === 0) {
            $contclose++;
            $closedticket .= '<div class="col-md-4 feature-box">' .
                '<label style="font-weight: bold;">Ticket numero:<h5>' . $r['id'] . '</h5></label></br>' .
                '<label style="font-weight: bold;">Oggetto:<h5>' . $r['oggetto'] . '</h5></label></br>' .
                '<label style="font-weight: bold;">Tipologia:<h5>' . $r['tipologia'] . '</h5></label></br>' .
                '<label style="font-weight: bold;">Descrizione:<h5>' . $descrizione . '</h5></label></br>' .
                '<label style="font-weight: bold;">Data apertura:<h5>' . $r['dataapertura'] . '</h5></label></br>' .
                '<input type="hidden" name="id" value="' . $r['id'] . '"/>' .
                '<a href="details.php?id=' . $r['id'] . '&page=ticket">Visualizza più dettagli</a>' .
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
    $stmt->bind_param('i', GetUser()[0]);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    $conn->close();
    if ($result->num_rows > 0) {
        foreach ($result as $r) {
            (strlen($r['descrizione']) > 20 ? $descrizione = substr($r['descrizione'], 0, 20) . "..." : $descrizione = $r['descrizione']);
            if (is_null($r['isconvalidato']) && $r['attività'] != null) {
                $contopen++;
                $openedreport .= '<div class="col-md-4 feature-box">' .
                    '<label style="font-weight: bold;">Ticket numero:<h5>' . $r['id'] . '</h5></label></br>' .
                    '<label style="font-weight: bold;">Oggetto:<h5>' . $r['oggetto'] . '</h5></label></br>' .
                    '<label style="font-weight: bold;">Tipologia:<h5>' . $r['tipologia'] . '</h5></label></br>' .
                    '<label style="font-weight: bold;">Descrizione:<h5>' . $descrizione . '</h5></label></br>' .
                    '<label style="font-weight: bold;">Attività:<h5>' . $r['attività'] . '</h5></label></br>' .
                    '<input type="hidden" name="id" value="' . $r['id'] . '"/>' .
                    '<a href="details.php?id=' . $r['id'] . '&page=report">Convalida report</a>' .
                    '</div>';
            } else if ($r['isconvalidato'] == 1 && $r['isrisolto'] == 1) {
                $contclose++;
                $closedreport .= '<div class="col-md-4 feature-box">' .
                    '<label style="font-weight: bold;">Ticket numero:<h5>' . $r['id'] . '</h5></label></br>' .
                    '<label style="font-weight: bold;">Oggetto:<h5>' . $r['oggetto'] . '</h5></label></br>' .
                    '<label style="font-weight: bold;">Tipologia:<h5>' . $r['tipologia'] . '</h5></label></br>' .
                    '<label style="font-weight: bold;">Descrizione:<h5>' . $descrizione . '</h5></label></br>' .
                    '<label style="font-weight: bold;">Attività:<h5>' . $r['attività'] . '</h5></label></br>' .
                    '</div>';
            } else if ($r['isconvalidato'] === 0 || $r['isrisolto'] === 0) {
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
    $stmt->bind_param('i', GetUser()[0]);
    if ($stmt->execute() == true) {
        $result = $stmt->get_result();
        $result = $result->fetch_assoc();
        $template .= '<form action="" style="border-radius: 25px" method="POST">' . '<div class="form-group"><label for="email">Nome</label><input class="form-control item field" name="nome" type="text" value="' . $result['nome'] . '" disabled></div>' .
            '<div class="form-group"><label for="email">Cognome</label><input   class="form-control item field" name="cognome" type="text" value="' . $result['cognome'] . '" disabled></div>' .
            '<div class="form-group"><label for="email">Cellulare</label><input id="field" class="form-control item field" name="cellulare" type="text" value="' . $result['cellulare'] . '" disabled></div>' .
            '<div class="form-group"><label for="email">Username</label><input class="form-control item field " name="username" type="text" value="' . $result['username'] . '" disabled></div>' .
            '<div class="form-group"><label for="email">Email</label><input class="form-control item field" name="email" type="email" value="' . $result['email'] . '" disabled></div>' .
            '<label><input type="checkbox" id="action" onclick="myFunction()"> Abilita modifica</label>' .
            '<button class="btn btn-primary btn-block" type="submit" name="send">Conferma</button>' .
            '</form>' . '</div>';
    } else
        $template = 'C\'è stato un problema, riprova più tardi.';
    $stmt->close();
    $conn->close();
    return array($result['nome'], $result['cognome'], $template);
}

function ShowTicketDetails($id)
{
    $conn = DataConnect();
    $template = '<div class="row justify-content-center">';
    $stmt = $conn->prepare('SELECT t.id,t.oggetto,t.tipologia,t.descrizione,t.dataapertura,r.attività,r.datainizio,r.datafine,r.isrisolto,r.commento FROM ticket t INNER JOIN report r ON t.id = r.fk_ticket AND t.id=?');
    $stmt->bind_param('i', $id);
    if ($stmt->execute() == true) {
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $result = $result->fetch_assoc();
            if (is_null($result['datainizio']) ? $startdate = 'Non ancora iniziato' : $startdate = $result['datainizio']);
            if (is_null($result['datafine']) ? $endate = 'Non ancora terminato' : $endate = $result['datafine']);
            if (is_null($result['attività']) ? $activity = 'Ancora nessuna attività' : $activity = $result['attività']);
            if ($result['isrisolto'] == 0 ? $status = 'Non ancora risolto' : $status = 'Risolto');
            if (is_null($result['commento']) ? $comment = 'Non hai ancora <a href="report.php">commentato</a>' : $comment = $result['commento']);
            $template .= '<div class="col-md-4 feature-box">' .
                '<label style="font-weight: bold;">Ticket numero:<h5>' . $result['id'] . '</h5></label></br>' .
                '<label style="font-weight: bold;">Oggetto:<h5>' . $result['oggetto'] . '</h5></label></br>' .
                '<label style="font-weight: bold;">Tipologia:<h5>' . $result['tipologia'] . '</h5></label></br>' .
                '<label style="font-weight: bold;">Descrizione:<h5>' . $result['descrizione'] . '</h5></label></br>' .
                '<label style="font-weight: bold;">Data inzio risoluzione:<h5>' . $startdate . '</h5></label></br>' .
                '<label style="font-weight: bold;">Data fine risoluzione:<h5>' . $endate . '</h5></label></br>' .
                '<label style="font-weight: bold;">Attività:<h5>' . $activity . '</h5></label></br>' .
                '<label style="font-weight: bold;">Stato ticket:<h5>' . $status . '</h5></label></br>' .
                '<label style="font-weight: bold;">Commento:<h5>' . $comment . '</h5></label></br>' .
                '</div></div>';
        } else
            $template = 'Non c\'è nulla da visualizzare per questo ticket.';
    } else
        $template =  'C\'è stato un problema, riprova più tardi.';
    $stmt->close();
    $conn->close();
    return $template;
}

function ShowReportDetails($id)
{
    $conn = DataConnect();
    $template = '<div class="row justify-content-center">';
    $stmt = $conn->prepare('SELECT t.id,t.oggetto,t.tipologia,t.descrizione,r.attività,r.datainizio,r.datafine FROM ticket t INNER JOIN report r ON t.id = r.fk_ticket AND t.id=?');
    $stmt->bind_param('i', $id);
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $result = $result->fetch_assoc();
            if (is_null($result['datainizio']) ? $startdate = 'Non ancora iniziato' : $startdate = $result['datainizio']);
            if (is_null($result['datafine']) ? $endate = 'Non ancora terminato' : $endate = $result['datafine']);
            if (is_null($result['attività']) ? $activity = 'Ancora nessuna attività' : $activity = $result['attività']);
            $template .= '<div class="col-md-4 feature-box">' .
                '<label style="font-weight: bold;">Ticket numero:<h5>' . $result['id'] . '</h5></label></br>' .
                '<label style="font-weight: bold;">Oggetto:<h5>' . $result['oggetto'] . '</h5></label></br>' .
                '<label style="font-weight: bold;">Tipologia:<h5>' . $result['tipologia'] . '</h5></label></br>' .
                '<label style="font-weight: bold;">Descrizione:<h5>' . $result['descrizione'] . '</h5></label></br>' .
                '<label style="font-weight: bold;">Data inzio risoluzione:<h5>' . $startdate . '</h5></label></br>' .
                '<label style="font-weight: bold;">Data fine risoluzione:<h5>' . $endate . '</h5></label></br>' .
                '<label style="font-weight: bold;">Attività:<h5>' . $activity . '</h5></label></br>' .
                '<textarea class="form-control" name="commento" required></textarea></br>' .
                '<button class="btn btn-primary btn-block" type="submit" name="yes">Convalida</button>' .
                '<button class="btn btn-primary btn-block" type="submit" name="no">Non convalidare</button>' .
                '</div></div>';
        } else
            $template = 'Non c\'è nulla da visualizzare per questo ticket.';
    } else
        $template =  'C\'è stato un problema, riprova più tardi.';
    $stmt->close();
    $conn->close();
    return $template;
}


function ShownewTickets()
{
    $uno = 1;
    $template = "";
    $conn = DataConnect();
    $stmt = $conn->prepare('SELECT t.id,t.dataapertura,t.descrizione,t.oggetto,t.tipologia,s.nome FROM ticket t
    INNER JOIN settore s on s.id=t.fk_settore LEFT JOIN report r ON t.id =r.fk_ticket where r.fk_ticket IS NULL 
    AND t.isaperto=? ORDER BY t.dataapertura DESC limit 12');
    $stmt->bind_param('i', $uno);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    if ($result->num_rows > 0) {
        for ($i = 0; $i < $result->num_rows; $i++) {
            $row = $result->fetch_assoc();
            (strlen($row['descrizione']) > 30 ? $descrizione = substr($row['descrizione'], 0, 30) . "..." : $descrizione = $row['descrizione']);
            $href = "AssegnaTicket.php?id=" . $row['id'];
            if ($i % 3 == 0)
                echo  "<div class='containerone'>";
            echo  "
        <div class='contenitore'>
        <p>Intervento n." . $row['id'] . "</p><p> aperto il " . $row['dataapertura'] . "</p>
        <p><strong>tipologia</strong> " . $row['tipologia'] . "</p>
        <p><strong>settore</strong> " . $row['nome'] . "</p>
        <p> <strong>oggetto</strong> " . $row['oggetto'] . "</p>
        <p> <strong>descrizione</strong> " . $descrizione . "</p>
        <a href='$href'> assegna ticket</a>
        </div>";
            if ($i % 3 == 2) echo " </div>";
        }
    }
    $conn->close();
}

function createReport($idipendente, $idticket)
{
    $error = "";
    $conn = DataConnect();
    $query = "INSERT INTO report (datainizio,fk_dipendente,fk_ticket) VALUES (NOW(),(select fk_utenza FROM dipendente WHERE id=?),?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ii', $idipendente, $idticket);
    if ($stmt->execute() === true) {
        $conn->close();
        $uno = 1;
        $conn = DataConnect();
        $query = "update ticket set isassegnato=? where id=?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('ii', $uno, $idticket);
        if ($stmt->execute() === true) {
            $error = "fatto";
        }
    } else
        $error = "mi dispiace riprovera";

    $conn->close();
    return $error;
}

function deleteTicket($id)
{
    $error = "";
    $conn = DataConnect();
    $query = "delete from ticket where";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ii', $id);
    if ($stmt->execute() === true) {
        $error = "fatto";
    } else
        $error = "mi dispiace riprovera";

    $conn->close();
    return $error;
}

function Tabella($query)
{
    $conn = DataConnect();
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    $conn->close();
    return $result;
}
function PagineTotali($total_pages_sql,$num_records_per_page)
{
    $conn = DataConnect();
    $stmt = $conn->prepare($total_pages_sql);
    $stmt->execute();
    $result = $stmt->get_result();
    $total_rows = $result->fetch_assoc()['cont'];
    $total_pages = ceil($total_rows / $num_records_per_page);
    return $total_pages;
}
function Paginazione($pageno, $total_pages)
{
    echo  '<div  class="contiene">';
    echo "<p class='inlineLeft'  >pagina " . $pageno . " su " . $total_pages . " pagine</p>";
    echo ' <ul  class="inlineRight"  id="navlist">';
    echo '<li ><a href="?pageno=1">First</a></li>';
    echo '<li  class="';
    if ($pageno <= 1) {
        echo 'disabled';
    };
    echo '">';
    echo '   <a href="';
    if ($pageno <= 1) {
        echo '#';
    } else {
        echo "?pageno=" . ($pageno - 1);
    };
    echo '">Prev</a></li>';

    echo '<li  class="';
    if ($pageno >= $total_pages) {
        echo 'disabled';
    };
    echo '">';
    echo ' <a href="';
    if ($pageno >= $total_pages) {
        echo '#';
    } else {
        echo "?pageno=" . ($pageno + 1);
    };
    echo '">Next</a></li>';
    echo '<li ><a href="?pageno=' . $total_pages . '">Last</a></li></ul></div>';
}
