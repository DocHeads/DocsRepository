<?php
include ('../templates/header.php');
include ('../Lib/DocsMailer.php');
$errMsg = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
  if (isset($_POST['email']))
  {
    if (Users::requestPasswordReset($_POST['email']))
    {
      $errMsg = 'Password reset request sent. Please check your email to confirm your request. Email may be in your spam folder';
    }
  }
}
?>

<h2>Forgot Your Password!</h2>
<p>
  To reset your password, please enter the email address associated with your account and choose submit.
  <br />
  <br />
  You will recieve a temporary password shortly.
</p>
<br />
<?php print '<p align="center"><span style="color: #b11117"><b>' . $errMsg . '</b></span></p>'; ?>
<form style="width: 450px;" action="forgotPassword.php" method="post">
  <fieldset>
    <legend>
      <strong>Enter Your Email Address:</strong>
    </legend>
    <br>
    <table>
      <tr>
        <td width="180px">Email Address:</td>
        <td>
        <input type="text" size="30" name="email">
        </td>
      </tr>
    </table>
  </fieldset>
  <p>
    <input type="submit" name="submit" value="Submit" />
  </p>
</form>

<?php
include ('../templates/footer.html');
?>