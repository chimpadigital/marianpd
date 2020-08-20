<?php

session_cache_limiter('nocache');
//header('Expires: ' . gmdate('r', 0));
//header('Content-type: application/json');
date_default_timezone_set('America/Argentina/Buenos_Aires');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Load Composer's autoloader
require 'vendor/autoload.php';

// Enter your email address. If you need multiple email recipes simply add a comma:conacto@marianpd.com
$to = "contacto@marianpd.com";

// Form Fields
$name = isset($_POST["name"]) ? $_POST["name"] : null;
$email = isset($_POST["email"]) ? $_POST["email"] : null;
$wp = isset($_POST["wp"]) ? $_POST["wp"] : null;
$face = isset($_POST["face"]) ? $_POST["face"] : null;
$insta = isset($_POST["insta"]) ? $_POST["insta"] : null;
$otras = isset($_POST["otras"]) ? $_POST["otras"] : null;
$subject = 'Nuevo Registro Cafecito virtual';
$subject_user = 'Tu inscripción al cafecito virtual está confirmada';


// $recaptcha = $_POST['g-recaptcha-response'];

//inicio script grabar datos en csv
$fichero = 'cafecito 3er sesion.csv';//nombre archivo ya creado
//crear linea de datos separado por coma
$fecha=date("Y-m-d H:i:s");
$linea = $fecha.";".$name.";".$email.";".$wp.";".$face.";".$insta.";".$otras."\n";
// Escribir la linea en el fichero
file_put_contents($fichero, $linea, FILE_APPEND | LOCK_EX);
//fin grabar datos


$name2 = isset($name) ? "Nombre y Apellido: $name<br><br>" : '';
$email2 = isset($email) ? "Email: $email<br><br>" : '';
$wp = isset($wp) ? "Whatsapp: $wp<br><br>" : '';
$face = isset($face) ? "Facebook: $face<br><br>" : '';
$insta = isset($insta) ? "Instagram: $insta<br><br>" : '';
$otras = isset($otras) ? "Otras redes: $otras<br><br>" : '';

$cuerpo1 = $name2 . $email2 . $wp . $face . $insta . $otras . '<br><br><br>Mensaje enviado de: ' . $_SERVER['HTTP_REFERER'];

$cuerpo2='
<div style="background-color:#f9f9f9;padding-top:50px;padding-bottom:50px;width: 100%;">
    <table width="600px" align="center" cellpadding="0" cellspacing="0" style="background-color:white">
        <tr style="background-color:#EAE4E1;">
            <td style="width:500px; height:40px; padding:15px 34px;font-family: Arial, Helvetica, sans-serif">
                <h3 style="color:#474747">¡Hola, hola! '.$name.'</h3>
                <h1 style="color:#B16063">¡Tú inscripción está confirmada!</h1>
            </td>
        </tr>
    </table>
    <table width="600px" align="center" cellpadding="0" cellspacing="0" style="background-color:white">
        <tr>
            <td width="600px" style="padding:20px 40px 10px;font-family: Arial, Helvetica, sans-serif;text-align: center;">
                <h3 style="margin-bottom:0px; color: #333333;font-size: 22px;">Ya tenés tu lugar asegurado en el cafecito virtual:<br>Creatividad y Diseño para tus Redes Sociales
                </h3>
            </td>
        </tr>
        <tr>
            <td width="600px" style="padding:0px 40px;font-family: Arial, Helvetica, sans-serif;text-align: center;">
                <p style="margin-bottom:0px">Te enviaré el link de acceso a la reunión el día previo a nuestro cafecito virtual.<br>
                Nos vemos el sábado 29 de Agosto a las 14:30 PM. Vía Zoom.
                </p>
                <p style="padding-top:30px">¡Gracias por sumarte!<br>Saluditos</p>
                <img src="https://marianpd.com/images/logo.png">
            </td>
        </tr>
        
    </table>
</div>
        ';


$to1=$to;
$to2=$_POST["email"];
$asunto1=$subject;
$asunto2=$subject_user;



function enviarMail($to,$asunto,$cuerpo){
    $mail = new PHPMailer(true);
    
    try {
        //Server settings
        $mail->SMTPDebug = SMTP::DEBUG_OFF;                      // Enable verbose debug output
        $mail->isSMTP();      
        
        $mail->Host = 'c1800221.ferozo.com';
        $mail->Port = 465;
        $mail->CharSet="UTF-8";
        $mail->SMTPSecure = 'ssl';
        $mail->SMTPAuth = true;
        $mail->Username = "contacto@marianpd.com";
        $mail->Password = "h6/U6bx5xM";   
        // Send using SMTP
        
        //Recipients
        $mail->setFrom('contacto@marianpd.com', 'Marian PD');
        $mail->addCC('marianapd.tur@gmail.com');
        $mail->addAddress($to);               // Name is optional
        
        // Content
        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->Subject = $asunto;
        $mail->Body    = $cuerpo;
        $mail->AltBody = $cuerpo;
        
        $mail->send();
        return true;
    } catch (Exception $e) {
        return "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
if( $_SERVER['REQUEST_METHOD'] == 'POST') {
    //If you don't receive the email, enable and configure these parameters below: 
    $mail_enviado=enviarMail($to1,$asunto1,$cuerpo1);
    //echo 'envio 1 '.$mail_enviado;
    $mail_enviado2=enviarMail($to2,$asunto2,$cuerpo2);
    //echo 'envio 2 '.$mail_enviado2;
    if($mail_enviado2)
                {
                // echo "<script>location.href='../gracias.html';</script>";
                header("Location: gracias.html");exit;
                }
                else
                {
                    echo "no se pudo enviar".$mail_enviado2 ;
                }          
    
}
?>
