<?php
function enviarEmail($email, $asunto, $body, $attach)
{
    require('./PHPMailer-master/src/PHPMailer.php');
    require('./PHPMailer-master/src/SMTP.php');

    $recipients = $email;

    $mail = new PHPMailer();
    $mail->isSMTP();
    $mail->Mailer = "SMTP";
    $mail->SMTPAuth = false;
    $mail->isHTML(true);
    $mail->SMTPAutoTLS = false;
    $mail->Port = 25;
    $mail->CharSet = 'UTF-8';
    $mail->Host = "localhost";
    $mail->Username = "postmaster";
    $mail->Password = ".";
    $mail->setFrom('postmaster@domenico.com');

    //$mail->addAttachment($attach);
    //Compruebo si es un correo o son varios
    if (is_array($email)) {
        foreach ($recipients as $correo) {
            $mail->addAddress($correo);
        }
    } else {
        $mail->addAddress($email);
    }
    $mail->Subject = $asunto;
    $mail->Body = $body;

    if (!$mail->send()) {
        echo $mail->ErrorInfo;
    } else {
        echo 'El mensaje ha sido enviado correctamente. Revise su bandeja de entrada.';
    }
}
$email = "ana.toli@domenico.com";
$asunto = "navidad";
$body = "Esto es un mensaje";
$attach = " ";
enviarEmail($email, $asunto, $body, $attach);
echo "sads";
