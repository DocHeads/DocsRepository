<?php
include '../Lib/Session.php';
Session::validateSession();
include ('../templates/header.php');

$errMsg = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
  // send the email off to the webmaster; needs to change
  $to = ConfigProperties::$ContactUsFormRecipient;
  $name = trim($_SESSION['name']);
  $email = trim($_SESSION['email']);
  $subject = trim($_POST['subject']);
  $body = trim($_POST['body']);
  
  // send it from the logged in user
  $from = $name . " <". $email .">";
  
  if (sendMail($to, 
               $from, 
               $subject, 
               $body))
  {
    $errMsg = 'Email sent successfully';
  }
}
?>

<h2>Contact Us</h2>
<p>
  Complete the form and submit!
</p>
<?php print '<p align="center"><span style="color: #b11117"><b>' . $errMsg . '</b></span></p><br/>'; ?>
<form style="border:1px solid #c6bebb; width: 450px;" action="contactUs.php" method="post">
  <fieldset>
    <legend>
      <strong>Contact Us</strong>
    </legend>
    <table>
      <tr>
        <td height="30px" width="200px"> Subject:</td>
        <td>
        <input name="subject" type="text" size="30">
        </td>
      </tr>
      <tr>
        <td style="vertical-align: top; padding-top: 10px;"> Message:</td>
        <td>        <textarea   id="body" name="body" value="" wrap="virtual" 
                        rows="5em" cols="30em"
                        valign="top"
                        align="left">
                    </textarea></td>
      </tr> 
    </table>
  </fieldset>
  <p>
    <input type="submit" name="submit" value="Send Message" />
  </p>
</form>

<?php
include ('../templates/footer.html');
?>