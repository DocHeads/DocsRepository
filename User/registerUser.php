<?php
ini_set('display_errors', true);
error_reporting(E_ALL);
include ('../Lib/ConfigProperties.php');
include ('../templates/header.php');
$errMsg = '';
$firstName = null;
$lastName = null;
$email = null;
$password = null;
$passConfirm = null;
$emailOptIn = null;

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
  // check first name
  if (!empty($_POST['fname']))
  {
    $firstName = $_POST['fname'];
    // check last name
    if (!empty($_POST['lname']))
    {
      $lastName = $_POST['lname'];
      // check email
      if (!empty($_POST['email']))
      {
        $email = $_POST['email'];
        // check to see if an existing user
        if (!empty($_POST['password']))
        {
          $password = $_POST['password'];

          if (!empty($_POST['passConfirm']))
          {
            $passConfirm = $_POST['passConfirm'];

            // confirm the password matches
            if ($password == $passConfirm)
            {
              // validate the opt-in check box
              if (isset($_POST['optIn']))
              {
                  $emailOptIn = 'YES';
              }
              else {
                  $emailOptIn = 'NO';
              }
              
              // check to see if user exists before inserting record
              if (Users::exists(trim($email)))
              {
                $errMsg = 'User already exists for that email. Please pick a different email or reset your password';
              }
              else
              {
                if (Users::registerUser($password, $firstName, $lastName, $email, $emailOptIn))
                {
                  // validate the user and set $_SESSION variables
                  Users::validateUser($email, $password);

                  // redirect to the landing page
                  header("location: ../Home/index.php");
                }
              }
            }
            else
            {
              $errMsg = 'Passwords do not match. Please verify input';
            }
          }
          else
          {
            $errMsg = 'Please confirm password';
          }
        }
        else
        {
          $errMsg = 'Password required';
        }

      }
      else
      {
        $errMsg = 'Email required';
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
?>

<h2>Create a new User!</h2>
<p>
  Complete the form below and choose submit.  You will receive a confirmation email shortly after.
</p>
<?php print '<br /><p><span style="color: #b11117"><b>' . $errMsg . '</b></span></p>'; ?>
<br />
<form id="register" name="register" style="border:1px solid #c6bebb; width: 450px;" action="registerUser.php" method="POST">
  <fieldset>
    <legend>
      <strong>Personal Information:</strong>
    </legend>
    <br>
    <table>
      <tr>
        <td width="200px"> First Name: </td>
        <td>
        <input type="text" name="fname" size="30" onblur="this.value = toTitleCase(this.value)" value="<?php
        if (isset($_POST['fname']))
        {
          print $_POST['fname'];
        }
        ?>">
        </td>
      </tr>
      <tr>
        <td> Last Name: </td>
        <td>
        <input type="text" onblur="this.value = toTitleCase(this.value)" name="lname" size="30" value="<?php
        if (isset($_POST['lname']))
        {
          print $_POST['lname'];
        }
        ?>">
        </td>
      </tr>
      <tr>
        <td> E-mail: </td>
        <td>
        <input type="text" name="email" onblur="this.value= validateEmail(this.value)" size="30" value="<?php
        if (isset($_POST['email']))
        {
          print $_POST['email'];
        }
        ?>">
        </td>
      </tr>
      <tr>
        <td colspan="2"><br /> <input type="checkbox" name="optIn" <?php
        if (isset($_POST['optIn']))
        {
          if ($_POST['optIn'] == 'on')
          {
            print 'checked';
          }
        }
        ?>> Check to Receive e-mail documents are submitted</td>
        </tr>
        </table>
        </fieldset>
        <br />
        <fieldset>
        <legend>
        <strong>Account Information:</strong>
        </legend>
        <br>
        <table>
          <tr>
            <td width="200"> Password:</td>
            <td>
            <input type="password" name="password" size="30" value="<?php
            if (isset($_POST['password']))
            {
              print $_POST['password'];
            }
            ?>">
            </td>
          </tr>
          <tr>
            <td> Verify Password:</td>
            <td>
            <input type="password" name="passConfirm" size="30" value="<?php
            if (isset($_POST['passConfirm']))
            {
              print $_POST['passConfirm'];
            }
            ?>">
            </td>
          </tr>
        </table>
  </fieldset>
  <hr />
  <p align="center">
    <input type="submit" name="submit" value="Submit" />
    <br /><br />
  </p>
</form>

<?php
include ('../templates/footer.html');
?>