<?php

include_once ('../templates/preheader.php');
// <-- this include file MUST go first before any HTML/output
include ('../ajaxCRUD.class.php');
// <-- this include file MUST go first before any HTML/output
include ('../Lib/Session.php');
Session::validateSession();
include ('../templates/header.php');
include ('../Lib/Departments.php');
?>

<h2>Welcome to the UC Faculty Document Managment System</h2>

<?php
if (Users::isAuthorized())
{
    $emailAddress = $_SESSION['email'];
    $errMsg = '';
  if (Session::getLoggedInUserType() == "ADMIN")
  {
    header('Location: ../Administration/adminHome.php');
  }
  else
      {
         echo '<div style="padding: 0px 20px 0px 20px">';
         echo '<table width="800" align="center" border="2">
                <tbody style="display: block; height: 250px;">
                    <tr width="800" height="250">
                        <td width="800" style="vertical-align:top;">';
                        
                        echo '<h2 style="font-size: 14px"><b>My Recent Submissions:</b></h2>';
                        
                        $con=mysqli_connect("localhost","root","","docdatabase");
                        // Check connection
                        if (mysqli_connect_errno())
                          {
                          echo "Failed to connect to MySQL: " . mysqli_connect_error();
                          }
                        
                        $result = mysqli_query($con,"SELECT * FROM submissions WHERE emailAddress = '$emailAddress' ORDER BY createDate DESC LIMIT 0,5");
                        
                        if (empty($result)) {
                            echo "No description available";
                        } else {

                            echo "<table class='customTable' width='800' align='center'>
                                <tr>
                                <thead align='left'>
                                <th height='20px'>Submission Name</th>
                                <th height='20px'>File</th>
                                <th height='20px'>Department</th>
                                <th height='20px'>Course</th>                                
                                <th height='20px'>Instructor Instructions</th>
                                <th height='20px'>Student Instructions</th>
                                <th height='20px'><strong>Created On</strong></th>
                                <th height='20px'><strong>Action</strong></th>
                                </thead>
                                </tr>";
                            
                            while($row = mysqli_fetch_array($result))
                              {
                                  echo "<tr>";
                                  echo "<td>" . $row['docName'] . "</td>";
                                  echo "<td>" . $row['submissionFile'] . "</td>";
                                  echo "<td>" . $row['deptName'] . "</td>";
                                  echo "<td>" . $row['courseName'] . "</td>";
                                  echo "<td>" . $row['instructorInstruction'] . "</td>";
                                  echo "<td>" . $row['studentInstruction'] . "</td>";
                                  echo "<td>" . $row['createDate'] . "</td>";
                                  echo "<td><a href=\"../Submission/submissionProfile.php?subID=" . $row['subID'] . "\"><img width='13px' src=\"../Images/edit.png\"></a></td>";
                              echo "</tr>";
                              }
                            echo "</table>";
                            
                            mysqli_close($con);
                            
                            echo '</td>
                                  </tr>
                                  </tbody>        
                                  </table><br style="clear:both;" />';
                        }
      }
    }
    else
    {
      print '<p>Your account will be verified within 24-48 hours!  <br /><br />Please contact the site administrator for more information.</p>';
    }
?>