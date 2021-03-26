<?php/*
function sendemail($email,$username,$password)
{
    $nome="ME";
    $to      = $email; // Send email to our user
    $subject = 'Signup  Verification'; // Give the email a subject 
    $message = '
  
Thanks for signing up!
Your account has been created, you can login with the following credentials after you have activated your account by pressing the url below.
  
------------------------
Username: ' . $username . '
Password: ' . $password . '
------------------------
  
Please click this link to activate your account:
http://localhost:8000/verify.php?email=' . $email .'
  
'; // Our message above including the link
$email_headers= "From: marouanouadi@gmail.com \r\n";
   // Set from headers
    echo $message;
   // mail($to, $subject, $message, $headers); // Send our email
    if (mail($to, $subject, $message, $email_headers))
    {
        echo "Richiesta inviata con successo !";
    }
    else
    {
        echo "Oops! Qualcosa è andato storto e non possiamo processare la tua richiesta";
    }
}


sendemail("ouadimarouan@gmail.com","sds","dssad")
*//*
$to_email = 'ouadimarouan@gmail.com';
$subject = 'Testing PHP Mail';
$message = 'This mail is sent using the PHP mail function';
$headers = 'From: marouanouadi@gmail.com';
mail($to_email,$subject,$message,$headers);
?>*/
<!DOCTYPE html>
<html>

<head>
    <title>Send Mail</title>
    <script src="https://smtpjs.com/v3/smtp.js">
    </script>

    <script type="text/javascript">
        function sendEmail() {
            Email.send({
                    Host: "smtp.gmail.com",
                    Username: "infoservicehelps@gmail.com",
                    Password: "maroc100",
                    To: 'ouadimarouan@gmail.com',
                    From: "infoservicehelps@gmail.com",
                    Subject: "Sending Email using javascript",
                    Body: "Well that was easy!!",
                })
                .then(function(message) {
                    alert("mail sent successfully")
                });
        }
    </script>
</head>

<body>

    <form method="post">
        <input type="button" value="Send Email" onclick="sendEmail()" />
    </form>
</body>

</html>


/* SmtpJS.com - v3.0.0 */
var Email = { send: function (a)
{
return new Promise(function (n, e) { a.nocache = Math.floor(1e6 * Math.random() + 1), a.Action = "Send"; var t = JSON.stringify(a); Email.ajaxPost("https://smtpjs.com/v3/smtpjs.aspx?", t, function (e) { n(e) }) }) }, ajaxPost: function (e, n, t) { var a = Email.createCORSRequest("POST", e); a.setRequestHeader("Content-type", "application/x-www-form-urlencoded"), a.onload = function () { var e = a.responseText; null != t && t(e) }, a.send(n) }, ajax: function (e, n) { var t = Email.createCORSRequest("GET", e); t.onload = function () { var e = t.responseText; null != n && n(e) }, t.send() }, createCORSRequest: function (e, n) { var t = new XMLHttpRequest; return "withCredentials" in t ? t.open(e, n, !0) : "undefined" != typeof XDomainRequest ? (t = new XDomainRequest).open(e, n) : t = null, t } };