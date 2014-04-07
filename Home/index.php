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
 echo '<table align="center" border="2">
                <tbody style="display: block; height: 300px;">
                    <tr height="300">
                        <td width="420" style="vertical-align:top;">';
                        
                        echo '<h2 style="font-size: 14px"><b>My Recent Submissions:</b></h2>';
                        
                        $con=mysqli_connect("localhost","root","","docdatabase");
                        // Check connection
                        if (mysqli_connect_errno())
                          {
                          echo "Failed to connect to MySQL: " . mysqli_connect_error();
                          }
                        
                        $result = mysqli_query($con,"SELECT * FROM submissions WHERE emailAddress = '$emailAddress' ORDER BY createDate DESC LIMIT 0,5");

                        echo "<table class='customTable' width='350' align='center'>
                        <tr>
                        <thead align='left'>
                        <th height='20px'>Submission</th>
                        <th height='20px'>File</th>
                        <th height='20px'><strong>Created On</strong></th>
                        <th height='20px'><strong>Action</strong></th>
                        </thead>
                        </tr>";
                        
                        while($row = mysqli_fetch_array($result))
                          {
                          echo "<tr>";
                          echo "<td>" . $row['docName'] . "</td>";
                          echo "<td>" . $row['submissionFile'] . "</td>";
                          echo "<td>" . $row['createDate'] . "</td>";
                          echo "<td><a href=\"../Submission/submissionProfile.php?subID=" . $row['subID'] . "\"><img width='13px' src=\"../Images/edit.png\"></a></td>";
                          echo "</tr>";
                          }
                        echo "</table>";
                        
                        mysqli_close($con);
                        
                        echo '</td>
                    </tr>
                </tbody>        
            </table>';
         

            echo '<table style="top-margin: 20px;" align="center" border="2">
                <tbody>
                    <tr>
                        <td>';
                                $subTable = new ajaxCRUD("Item", "submissions", "subID", "../");
                            
                                $subTable->omitPrimaryKey();
                                
                                #the table fields have prefixes; i want to give the heading titles something more meaningful
                                $subTable->displayAs("emailAddress", "User Name");
                                $subTable->displayAs("docName", "Submission");
                                $subTable->displayAs("deptName", "Department");
                                $subTable->displayAs("courseName", "Course");
                                $subTable->displayAs("comments", "Comments");
                                $subTable->displayAs("rubricFileName", "Grading Rubric");
                                $subTable->displayAs("willYouGrade", "Grade?");
                                $subTable->displayAs("createDate", "Created On");                                
                                $subTable->displayAs("submissionFile", "File Name"); 
                                $subTable->displayAs("instructorInstruction", "Instructor Inst");
                                $subTable->displayAs("studentInstruction", "Student Inst");

                                #i could omit a field if I wanted
                                #http://ajaxcrud.com/api/index.php?id=omitField
                                $subTable->omitField("willYouGrade");
                                $subTable->omitField("updateDate");
                                $subTable->omitField("comments");   
                                $subTable->omitField("rubricFileName");
                                                            
                                #i could disable fields from being editable
                                $subTable->disallowEdit('emailAddress');
                                $subTable->disallowEdit('createDate');
                                $subTable->disallowEdit('deptName');
                                $subTable->disallowEdit('courseName');                                
                                $subTable->disallowEdit('submissionFile');
                                $subTable->disallowEdit('instructorInstruction');
                                $subTable->disallowEdit('studentInstruction');                             
                                #set the number of rows to display (per page)
                                $subTable->setLimit(10);
                                
                                #i can order my table by whatever i want
                                $subTable->addOrderBy("ORDER BY emailAddress ASC");
                                
                                #if really desired, a filter box can be used for all fields
                                $subTable->addAjaxFilterBoxAllFields();
                                
                                #i can disallow deleting of rows from the table
                                #http://ajaxcrud.com/api/index.php?id=disallowDelete
                                $subTable->disallowDelete();
                            
                                #i can disallow adding rows to the table
                                #http://ajaxcrud.com/api/index.php?id=disallowAdd
                                $subTable->disallowAdd();
                                echo '<h2 style="font-size: 14px;"><b>User Submissions:</b></h2>';
                                #actually show the table
                                $subTable->showTable();
                        
                        echo '</td>
                    </tr>
                </tbody>        
            </table><br style="clear:both;" />';
                        }
      }
    
    else
    {
      print '<p>Your account will be verified within 24-48 hours!  <br /><br />Please contact the site administrator for more information.</p>';
    }
?>