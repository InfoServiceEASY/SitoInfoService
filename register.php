<?php
session_start();
include_once('session.php');
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $myusername = $_POST['username'];
    $mypassword = $_POST['password'];
    $hashPassword = password_hash($mypassword, PASSWORD_DEFAULT);
    $stmt = $db->prepare("SELECT username FROM users WHERE username=?");
    $stmt->bind_param("s", $myusername);
    $stmt->execute();
    $result = $stmt->get_result();
    $numrow = $result->num_rows;
    if ($numrow > 0) {
        $error = "Username già occupato";
        $stmt->close();
    } else {
        $stmt = $db->prepare("INSERT INTO users (username,passcode) VALUES(?,?)");
        $stmt->bind_param("ss", $myusername, $hashPassword);
        $stmt->execute();
        $stmt->close();
        header('location:../public/login');
        exit();
    }
}
?>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Sign In</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<?php
include_once('../template/template_1.php');
?>

</html>