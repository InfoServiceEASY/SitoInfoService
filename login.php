<?php
session_start();
include_once('../dbconfig.php');
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $myusername = $_POST['username'];
    $mypassword = $_POST['password'];
    $stmt = $db->prepare("SELECT * FROM users WHERE username=?");
    $stmt->bind_param("s", $myusername);
    $stmt->execute();
    $result = $stmt->get_result();
    $numrow = $result->num_rows;
    $stmt->close();
    if ($numrow > 0) {
        $result = $db->query("SELECT passcode FROM users WHERE username='$myusername'");
        $row = $result->fetch_assoc();
        if (password_verify($mypassword, $row['passcode'])) {
            $_SESSION['login_user'] = $myusername;
            header("location:../admin/welcome");
            exit();
        } else
            $error = 'Wrong password!';
    } else {
        $error = "Wrong username!";
    }
}
?>
<html>

<head>

    <title>Login Page</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
</head>
<?php
include_once('../template/template_1.php');
?>

</html>