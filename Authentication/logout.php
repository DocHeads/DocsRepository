<?php
include '../Lib/Session.php';
include ('../templates/header.php');
if(Session::logOutSession())
{
	print '<h2>You Are Now Logged Out!</h2>';
	print '<p style="font-size: 20px; color: red;"><strong>Thank you for using this site!</strong></p><br />';
    print '<p align="center"><img height="500" src="../Images/bearcatGoodbye.png"></p>';
}
include ('../templates/footer.html');
?>