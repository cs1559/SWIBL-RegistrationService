<?php
namespace swibl\core\email;

use PHPMailer\PHPMailer\PHPMailer;

class Mailer {
    
    var $fromemail = null;
    var $fromname = null;
    var $toemail = null;
    
    function __construct($fromemail, $fromname) {
        $this->fromemail = $fromemail;
        $this->fromname = $fromname;
    }
    
    function send($toemail, $subject, $message, $html=true, $cc=null, $bcc=null) {
  
        $mail = new PHPMailer;
        
//         if (class_exists('PHPMailer')) {
//             //PHPMailer Object
//             $mail = new PHPMailer;
//         } else {
//             throw new \Exception("PHPMailer not defined");
//         }
          
        //From email address and name
        $mail->From = $this->fromemail;
        $mail->FromName = $this->fromname;
        
        //To address and name
        $mail->addAddress($toemail);
       // $mail->addAddress("recepient1@example.com"); //Recipient name is optional
        
        //Address to which recipient will reply
        $mail->addReplyTo("$this->fromemail");
        
        //CC and BCC
/*
         $mail->addCC("cc@example.com");
         $mail->addBCC("bcc@example.com");
*/
        
        //Send HTML or Plain Text email
        $mail->isHTML($html);
        
        $mail->Subject = $subject;
        $mail->Body = $message;
//         $mail->AltBody = "";

        if(!$mail->send())
        {
            throw new \Exception($mail->ErrorInfo);
        }
        
    }
    
}