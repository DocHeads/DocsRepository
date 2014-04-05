<?php
include '../Lib/Session.php';
include ('../templates/header.php');
$errMsg = '';
$newPass = '';
$confPass = '';
$email = '';

print '<h2>Reset Password</h2>';
print '<p>Change Password for the UC Document Repository</p><br />';

if (isset($_GET['email']) && (isset($_GET['tempKey'])))
{
  // get the email and tempKey value out of the URL to verify in db
  $sentEmail = $_GET['email'];
  $sentTempKey = $_GET['tempKey'];
  if (Users::confirmPasswordReset($sentEmail, $sentTempKey))
  {
    // print the password reset from below
    print '<form style="width: 450px;" action="resetPassword.php" method="post">';
    print '<fieldset>';
    print '<legend>';
    print '<strong>Reset Password:</strong>';
    print '</legend>';
    print '<br><table>';
    print '<tr><td height="30px" width="200px"> Email Address:</td>';
    printf('<td>%s<input type="text" name="emailAddress" value="%s" hidden></td>', $sentEmail, $sentEmail);
    print '</tr>';
    print '<tr><td> New Password:</td><td><input type="password" name="newPass" size="30"></td></tr>';
    print '<tr><td> Verify New Password:</td><td><input type="password" name="confPass" size="30"></td></tr>';
    print '</table>';
    print '</fieldset>';
    print '<p><input type="submit" name="submit" value="Submit" /></p>';
    print '</form>';
  }
  else
  {
    $errMsg = "Invalid password reset key for email address\n\n";
    $errMsg .= "Please try resetting the password again. If problems persist, please contact a site administrator for help.";
  }
}
else
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
  if (!empty($_POST['newPass']) ? $newPass = $_POST['newPass'] : $newPass = null)
    ;
  if (!empty($_POST['confPass']) ? $confPass = $_POST['confPass'] : $confPass = null)
    ;
    if (!empty($_POST['emailAddress']) ? $email = $_POST['emailAddress'] : $email = null)
    ;

  if (isset($newPass) || isset($confPass))
  {
    if ($newPass != $confPass)
    {
      $errMsg = 'Password/Verify mismatch. Please verify password input';
    }
    else
    {
      // reset user's password
      if (Users::resetPassword($_POST['emailAddress'], $_POST['newPass']))
      {
        $errMsg = "Password successfully reset. Redirecting to the login page...";
        header("refresh:3;url=../Authentication/login.php");
      }
    }
  }
  else
  {
    $errMsg = 'Password/Confirm password is required';
  }
  
  print '<p align="center"><span style="color: #b11117"><b>' . $errMsg . '</b></span></p><br/>';
  print '<form style="width: 450px;" action="resetPassword.php" method="post">';
  print '<fieldset>';
  print '<legend>';
  print '<strong>Reset Password:</strong>';
  print '</legend>';
  print '<br><table>';
  print '<tr><td height="30px" width="200px"> Email Address:</td>';
  printf('<td>%s<input type="text" name="emailAddress" value="%s" hidden></td>', $email, $email);
  print '</tr>';
  printf ('<tr><td> New Password:</td><td><input type="password" name="newPass" value="%s" size="30"></td></tr>', $newPass);
  printf ('<tr><td> Verify New Password:</td><td><input type="password" name="confPass" value="%s" size="30"></td></tr>', $confPass);
  print '</table>';
  print '</fieldset>';
  print '<p><input type="submit" name="submit" value="Submit" /></p>';
  print '</form>';
  $errMsg = '';
}
else
{
  $errMsg = "Invalid password reset key for email address\n\n";
  $errMsg .= "Please try resetting the password again. If problems persist, please contact a site administrator for help.";
}
print '<p align="center"><span style="color: #b11117"><b>' . $errMsg . '</b></span></p>';
include ('../templates/footer.html');
?>