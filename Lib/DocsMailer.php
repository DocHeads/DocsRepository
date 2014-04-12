<?php
require_once ('../Lib/PHPMailer/PHPMailerAutoload.php');

/**
 * Class used to send email programmatically from the system
 */
class DocsMailer extends PHPMailer
{
  var $to_name;
  var $to_email;
  var $fromEmail;
  var $fromName;
  var $sender;

  function DocsMailer()
  {
    $this -> Host = ConfigProperties::$EmailServer;
    $this -> SMTPAuth = true;
    $this -> Username = ConfigProperties::$EmailServerUsername;
    $this -> Password = ConfigProperties::$EmailServerPassword;
    $this -> Mailer = 'smtp';

    $this -> From = ConfigProperties::$EmailServerUsername;
    $this -> FromName = 'UC Document Repository';
    $this -> Sender = ConfigProperties::$EmailServerUsername;
    $this -> Priority = 3;
  }

}
?>