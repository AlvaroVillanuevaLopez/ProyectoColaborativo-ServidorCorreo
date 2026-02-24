<?php
function recorrerDirectorio($tematica)
{
    chdir("./" . $tematica);
    $arrayContenido = scandir(getcwd());
    chdir("../");
    return $arrayContenido;
}
function enviarEmail($para, $asunto, $mensaje, $imagen)
{
    require('PHPMailer-master/src/PHPMailer.php');
    require('PHPMailer-master/src/SMTP.php');
    $recipients = $para;
    $mail = new PHPMailer();
    $mail->isSMTP();
    $mail->Mailer = "SMTP";
    $mail->SMTPAuth = false;
    $mail->isHTML(true);
    $mail->SMTPAutoTLS = false;
    $mail->Port = 25;
    $mail->CharSet = 'UTF-8';
    $mail->Host = "192.168.1.42";
    $mail->Username = "postmaster";
    $mail->Password = ".";
    $mail->setFrom('postmaster@domenico.com');
    $mail->addAttachment($imagen);
    //Compruebo si es un correo o son varios
    if (is_array($para)) {
        foreach ($recipients as $correo) {
            $mail->addAddress($correo);
        }
    } else {
        $mail->addAddress($para);
    }
    $mail->Subject = $asunto;
    $mail->Body = $mensaje;

    if (!$mail->send()) {
        echo $mail->ErrorInfo;
    } else {
        echo "<p>El mensaje ha sido enviado correctamente. Revise su bandeja de entrada.</p>";
    }
}
/*
    $token = uniqid();
    $_SESSION['token'] = $token;
    echo "<input type='hidden' name='token' value='" . $token . "'/>";
*/
function comprobarToken()
{
    session_start();
    if (isset($_SESSION['identificado']) && isset($_SESSION['token']) && isset($_POST['token'])) {
        if ($_SESSION['token'] == $_POST['token']) {
            unset($_SESSION['token']);
        } else {
            die("<p>Token disntintos, error de validación</p>");
        }
    } else {
        die("<p>Token no recuperado, error de validación</p>");
    }
}
