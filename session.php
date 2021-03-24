<?php
include_once('../dbconfig.php');
$user_check = $_SESSION['login_user'];
$ses_sql = $db->query("SELECT username FROM users WHERE username = '$user_check'");
$row = $ses_sql->fetch_assoc();
$login_session = $row['username'];
if(!isset($_SESSION['login_user'])){
    header('location:../index');
    exit();
}
