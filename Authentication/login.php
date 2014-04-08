<?php
include ('../Lib/ConfigProperties.php');
include ('../templates/header.php');
$errMsg = '';
$email = null;
$password = null;

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
  // check email
  if (!empty($_POST['email']))
  {
    $email = trim($_POST['email']);

    if (!empty($_POST['password']))
    {
      $password = trim($_POST['password']);

      if (Users::validateUser($email, $password))
      {
        // forward the user to the user portal
        header( 'Location: ../Home/index.php' ) ;
        $errMsg = 'valid email/password';
      }
			else 
				{
					$errMsg = 'Invalid email/password';
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
?>

<h2>UC Faculty Document Managment System Login</h2>
<p align="center">
	Please enter your username and password.
</p>
<br />

<form style="width: 380px; border:1px solid #c6bebb;" action="login.php" method="post">
	<table align="center">
		<?php print '<p align="center"><span style="color: #b11117"><br/><b>' . $errMsg . '</b></span></p>'; ?>
		<tr>
			<td style="width: 120px;">
			<p>
				Email Address:
			</p></td>
			<td style="width: 200px;">
			<p>
				<input type="text" name="email" width="120" size="20" value="<?php
        if (isset($_POST['email']))
        {
          print $_POST['email'];
        }
				?>"/>
			</p></td>
		</tr>
		<tr>
			<td style="width: 120px;">
			<p>
				Password:
			</p></td>
			<td style="width: 200px;">
			<p>
				<input type="password" name="password" size="20" value="<?php
        if (isset($_POST['password']))
        {
          print $_POST['password'];
        }
				?>"/>
			</p></td>
		</tr>
		<tr>
			<td colspan="2">
			<p align="center"><br/>
				<input type="submit" name="submit" value="Log In" />
			</p></td>
		</tr>
		<tr>
			<td colspan="2"><hr></td>
		</tr>
		<tr>
			<td colspan="2" align="center"><a href="../User/registerUser.php">Register</a> | <a href="../Authentication/forgotPassword.php">Forgot Password</a></td>
		</tr>
	</table>
</form>

<?php
include ('../templates/footer.html');
?>
