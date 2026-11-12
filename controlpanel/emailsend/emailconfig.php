<?php
// Include PHPMailer classes manually
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
$mail = new PHPMailer(true);
$mail->SMTPKeepAlive = true;

function sendemail($to, $subject, $body) {
    
    global $mail;
    
    try {
        // --- Gmail Server Settings ---
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        
        // Your full Gmail address
        $mail->Username   = 'info.xereq@gmail.com'; 
        
        // Your 16-character Google App Password (NOT your regular password)
        // Format: 'xxxx xxxx xxxx xxxx' or 'xxxxxxxxxxxxxxxx'
        $mail->Password   = 'bxuhavvupxlpryyg'; 
        
        // Security configuration for Port 587
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // --- Sender & Recipient ---
        // Sender address MUST match your Gmail account or an authorized alias
        $mail->clearAddresses();  // Clears TO addresses
		$mail->clearCCs();        // Clears CC addresses
		$mail->clearBCCs();       // Clears BCC addresses
		$mail->clearAttachments();
        $mail->setFrom('info.xereq@gmail.com', 'XEREQ');
        $mail->addAddress($to);

        // --- Message Content ---
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $body;
        $mail->AltBody = strip_tags($body); // Plain text fallback

        $mail->send();
        return true;
    } catch (Exception $e) {
        // Log the exact error to your PHP error log for troubleshooting
        error_log("Gmail SMTP Error: {$mail->ErrorInfo}");
        return false;
    }
}