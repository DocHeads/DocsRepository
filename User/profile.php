<?php
include '../Lib/ConfigProperties.php';
include '../Lib/Session.php';
Session::validateSession();
include ('../templates/header.php');
$errMsg = '';
// set the variables to the corresponding db lookup
$emailAddress = Session::getLoggedInUserEmail();
$userProps = Users::getUserProperties($emailAddress);
$fName = null;
$lName = null;
$emailOptIn = null;
$userType = null;
$newPass = null;
$confPass = null;

// if server request method is a post, set them to the post value and validate
// the entries
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
  if (isset($_POST['fName']) ? $fName = $_POST['fName'] : $fName = null);
  if (isset($_POST['lName']) ? $lName = $_POST['lName'] : $lName = null);
  if (isset($_POST['userType']) ? $userType = $_POST['userType'] : $userType = 'STANDARD');
  if (isset($_POST['emailOptIn']) ? $emailOptIn = $_POST['emailOptIn'] : $emailOptIn = $userProps['emailOptIn']);
  if (isset($_POST['newPass']) ? $newPass = $_POST['newPass'] : $newPass = null);
  if (isset($_POST['confPass']) ? $confPass = $_POST['confPass'] : $confPass = null);

  // check the boolean value of the POST array and convert it for the
  // boolean in
  // db
  if (!empty($emailOptIn))
  {
    if ($emailOptIn == 'on')
    {
      $emailOptIn = 'YES';
    }
    else
    {
      $emailOptIn = 'NO';
    }
  }

  if (!empty($fName))
  {
    if (!empty($lName))
    {
      if (isset($newPass) || isset($confPass))
      {
        if ($newPass != $confPass)
        {
          $errMsg = 'Password/Verify mismatch. Please verify password input';
        }
        else
        {
          // update the user
          if (Users::updateUser($emailAddress, $fName, $lName, $userType, $emailOptIn, $newPass))
          {
            $_SESSION['name'] = $fName ." ". $lName;
            $_SESSION['userType'] = $userType;
            $errMsg = 'User ' . $emailAddress . ' updated';
          }
        }
      }
      else
      {
        // update the user
        if (Users::updateUser($emailAddress, $fName, $lName, $userType, $emailOptIn, $newPass))
        {
          $_SESSION['name'] = $fName ." ". $lName;
          $_SESSION['userType'] = $userType;
          $errMsg = 'User ' . $emailAddress . ' updated';
        }
      }
    }
    else
    {
      $errMsg = 'Last name required';
    }
  }
  else
  {
    $errMsg = 'First name required';
  }
}
else
{
  // set the variables to the corresponding db lookup
  $emailAddress = Session::getLoggedInUserEmail();
  $userProps = Users::getUserProperties($emailAddress);
  $fName = trim($userProps['fName']);
  $lName = trim($userProps['lName']);
  $emailOptIn = trim($userProps['emailOptIn']);
  $userType = 'STANDARD';
  $newPass = null;
  $confPass = null;
}

?>

<h2>Edit your Profile</h2>
<p>
  Edit your profile below.  Choose submit when finished.
</p>
<?php print '<br /><p style="text-align: center"><span style="color: #b11117"><b>' . $errMsg . '</b></span></p>'; ?>
<br />
<form style="border:1px solid #c6bebb; width: 450px;" action="profile.php" method="post">
  <fieldset>
    <legend>
      <strong>Personal Information:</strong>
    </legend>
    <br>
    <table>
      <tr>
        <td width="200px"> Username/Email: </td>
        <?php echo '<td>' . $emailAddress . '</td>'; ?>
      </tr>
      <tr>
        <td width="200px"> First Name: </td>
        <?php echo '<td><input type="text" name="fName" onblur="this.value = toTitleCase(this.value)"  size="30" value="' . $fName . '"></td>'; ?>
      </tr>
      <tr>
        <td> Last Name: </td>
        <?php echo '<td><input type="text" name="lName" onblur="this.value = toTitleCase(this.value)" size="30" value="' . $lName . '"></td>'; ?>
      </tr>
      <tr>
        <td> Email Opt In: </td>
        <?php
        if ($emailOptIn == 'YES' ? $check = 'checked' : $check = '');
        print '<td><input type="checkbox" name="emailOptIn" size="30" ' . $check . '></td>';
        ?>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td style="font-size: 11px" >**Check if you'd like to get email updates when submission repository is updated</td>
      </tr>
      <?php
      if (Session::getLoggedInUserType() == 'ADMIN')
      {
        $userTypeArray = Users::getUserTypesArray();
        echo '
      <tr>
        <td> User Type:</td>';
        echo '<td>';
        echo '
        <select name="userType">
          ';

        for ($i = 0; $i < count($userTypeArray); $i++)
        {
          if ($userTypeArray[$i]['userTypeName'] == Session::getLoggedInUserType())
          {
            echo '<option value="' . $userTypeArray[$i]['userTypeName'] . '" selected>' . $userTypeArray[$i]['userTypeName'] . '</option>';
          }
          else
          {
            echo '<option value="' . $userTypeArray[$i]['userTypeName'] . '">' . $userTypeArray[$i]['userTypeName'] . '</option>';
          }
        }
        echo '
        </select>';
        echo '</td>
      </tr>';
      }
      ?>
      <tr>
        <td colspan="2">
        <hr />
        </td>
      </tr>
      <tr>
        <td> New Password:</td>
        <td> <?php echo '
        <input type="password" size="30" name="newPass" value="' . $newPass . '">
        ';
        ?> </td>
      </tr>
      <tr>
        <td> Verify New Password:</td>
        <td> <?php echo '
        <input type="password" size="30" name="confPass" value="' . $confPass . '">
        ';
        ?> </td>
      </tr>
    </table>
  </fieldset>

  <p>
    <input type="submit" name="submit" value="Submit" />
  </p>

</form>
<br />

<?php
include ('../templates/footer.html');
?>