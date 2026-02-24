<?php
include 'functions.php';
if (isset($_POST['enviar'])) {
    comprobarToken();
    $para = $_POST['receptor'];
    $asunto = $_POST['asunto'];
    $mensaje = $_POST['mensaje'];
    $imagen = $_POST['foto'];
    $tematica = $_POST['tema'];
    $imagenEntera = "./" . $tematica . "/" . $imagen;
    echo "
        <!DOCTYPE html>
        <html lang='en'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>INDEX</title>
            <link rel='stylesheet' href='hojaEstilos.css'>
        </head>
        <body>
            <div class='contenedor'>
                <h2>ENVÍO DEL CORREO</h2>
    ";
    enviarEmail($para, $asunto, $mensaje, $imagenEntera);
    echo "</div>
        </body>
    </html>";
    die();
}
if (isset($_POST['actualizar'])) {
    $tematica = $_POST['tema'];
    $pasado = true;
    $arrayContenido = recorrerDirectorio($tematica);
    session_start();
    $_SESSION['identificado'] = true;
} else {
    $pasado = false;
}
?>
<!DOCTYPE html>
<html lang='en'>

<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>INDEX</title>
    <link rel='stylesheet' href='hojaEstilos.css'>
</head>

<body>
    <div class='contenedor'>
        <h2>ENVIO DE POSTALES</h2>
        <form action='' method='post'>
            <p>DESTINATARIO:
                <select name='receptor[]' multiple>
                    <?php
                    try {
                        $laCone = new PDO('mysql:dbname=correos;host=127.0.0.1', 'web', 'web');
                        $laCone->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                        $senten = $laCone->prepare('SELECT email FROM cuentas');
                        $senten->execute();
                        while ($filas = $senten->fetch()) {
                            echo "<option value='" . $filas[0] . "'>" . $filas[0] . "</option>";
                        }
                    } catch (PDOException $e) {
                        echo 'Error: ' . $e->getMessage();
                    }
                    ?>
                </select>
            </p>
            <p>ASUNTO: <input type='text' name='asunto' value='<?php if (isset($_POST['asunto'])) echo htmlspecialchars($_POST['asunto']); ?>' required></p>
            <p>MENSAJE: <input type='text' name='mensaje' value='<?php if (isset($_POST['mensaje'])) echo htmlspecialchars($_POST['mensaje']); ?>' required></p>
            <p>TEMÁTICA SELECCIONADA:
                <select name='tema'>"
                    <?php
                    $arrayCarpetas = scandir(getcwd());
                    foreach ($arrayCarpetas as $valor) {
                        if ($valor == 'navidad' || $valor == 'sanvalentin' || $valor == 'halloween') {
                            echo "<option value='" . $valor . "'";
                            if (isset($_POST['tema']) && $_POST['tema'] == $valor) echo 'selected';
                            echo ">" . $valor . "</option>";
                        }
                    }
                    ?>
                </select>
                <input type='submit' name='actualizar' value='MOSTRAR' id='chiquito' />
            </p>
            <?php
            if ($pasado) {
                $token = uniqid();
                $_SESSION['token'] = $token;
                echo "
                    <input type='hidden' name='token' value='" . $token . "'/>
                    <div class='contenedorT'>
                        <table>
                            <tr>";
                foreach ($arrayContenido as $valor) {
                    if ($valor != "." && $valor != "..") {
                        echo "<td><img src='" . $tematica . "/" . $valor . "' alt='" . $valor . "'/></td>";
                        echo "<td><input type='radio' name='foto' value='" . $valor . "'/></td>";
                    }
                }
                echo "        </tr>
                        </table>
                    </div>
                    <input type='submit' id='cartero' name='enviar' value='ENVIAR' />
                ";
            }
            ?>
        </form>
    </div>
</body>