<?php
use PHPMailer\PHPMailer\PHPMailer;
require 'vendor/autoload.php';
$mail = new PHPMailer;
$mail->isSMTP();
$mail->SMTPDebug = 0;
$mail->Host = gethostbyname('smtp.gmail.com');
$mail->Port = 465;
$mail->SMTPSecure = 'ssl';
$mail->SMTPAuth = true;
$mail->Username = "pivomat2019@gmail.com";
$mail->Password = "craftpiva";
$mail->setFrom('pivomat2019@gmail.com', 'Pivomat');
$mail->addAddress('anze.gn@gmail.com');
$mail->Subject = 'PHPMailer GMail SMTP test';
$mail->Body = "Hello World 3";
$mail->SMTPOptions = array(
    'ssl' => array(
        'verify_peer' => false,
        'verify_peer_name' => false,
        'allow_self_signed' => true
    )
);

if (!$mail->send()) {
    echo "Mailer Error: " . $mail->ErrorInfo;
} else {
    echo "Message sent!";
}