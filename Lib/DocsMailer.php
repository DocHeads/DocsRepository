<?php
 require_once "Mail.php";
 
 /**
  * Method used to send email programmatically from the system
  * 
  * @param $to - string value for the email recipient
  * @param $from - string value the email is sent from
  * @param $subject - string value of the email subject line
  * @param $body - string value of the email body
  * @return TRUE if the email is sent  
  */
 function sendMail($to, $from, $subject, $body)
 {
 error_reporting(E_ALL ^ E_STRICT);
 $isSent = FALSE;
 
 $host = ConfigProperties::$EmailServer;
 $username = ConfigProperties::$EmailServerUsername;
 $password = ConfigProperties::$EmailServerPassword;
 
 $headers = array ('From' => $from,
   'To' => $to,
   'Subject' => $subject);
 $smtp = Mail::factory('smtp',
   array ('host' => $host,
     'auth' => true,
     'username' => $username,
     'password' => $password));
 
 $mail = $smtp->send($to, $headers, $body);
 
 if (PEAR::isError($mail)) {
   $isSent = $mail->getMessage();
  } else {
   $isSent = TRUE;
  }
  
  return $isSent;
 }
 ?>