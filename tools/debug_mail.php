<?php  
require_once __DIR__ . '/../inc/Mailer.php';  
$to = $argv[1] ?? 'kusulema7@gmail.com';  
$subject = 'DEBUG VOID SMTP test';  
$body = 'Test body';  
$autoload = __DIR__ . '/../vendor/autoload.php';  
require_once $autoload;  
try {  
    $mail = new PHPMailer\PHPMailer\PHPMailer(true);  
    $mail->isSMTP();  
    $mail->Host = 'smtp.gmail.com';  
    $mail->SMTPAuth = true;  
    $mail->Username = 'kusulema7@gmail.com';  
    $mail->Password = 'qpliobctknwbvrwc';  
    $mail->Port = 587;  
    $mail->SMTPSecure = 'tls';  
    $mail->setFrom('thevoid@gmail.com', 'VOID & IRON');  
    $mail->addAddress($to);  
    $mail->isHTML(true);  
    $mail->Subject = $subject;  
    $mail->Body = $body;  
    $mail->SMTPDebug = 2;  
    $mail->send();  
    echo 'Success';  
} catch (Exception $e) {  
    echo 'Error: ' . $e->getMessage();  
} 
